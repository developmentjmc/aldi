<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use jeemce\models\CrudTrait;
use jeemce\models\MainTrait;

class TunjanganTransport extends Model
{
    use HasFactory, MainTrait, CrudTrait;

    protected $table = 'tunjangan_transports';
    
    protected $fillable = [
        'employee_id',
        'base_fare',
        'jarak',
        'hari_kerja',
        'jarak_bulat',
        'tunjangan',
        'keterangan',
        'kantor'
    ];

    protected $casts = [
        'base_fare' => 'decimal:2',
        'jarak' => 'decimal:2', 
        'jarak_bulat' => 'decimal:0',
        'tunjangan' => 'decimal:2',
        'hari_kerja' => 'integer'
    ];

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:data_employees,id',
            'base_fare' => 'required|numeric|min:0',
            'jarak' => 'required|numeric|min:0',
            'hari_kerja' => 'required|integer|min:0|max:31',
            'keterangan' => 'nullable|string|max:255',
            'kantor' => 'required'
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateTunjangan()
    {
        if (!$this->employee) {
            $this->load('employee');
        }

        $this->jarak = $this->jarak > 25 ? 25 : $this->jarak;

        if ($this->employee->jenis_pegawai !== 'Tetap') {
            $this->jarak_bulat = 0;
            $this->tunjangan = 0;
            return;
        }

        if ($this->hari_kerja < 19) {
            $this->jarak_bulat = 0;
            $this->tunjangan = 0;
            return;
        }

        $jarakEfektif = $this->jarak;
        if ($jarakEfektif < 5) {
            $this->jarak_bulat = 0;
            $this->tunjangan = 0;
            return;
        }

        if ($jarakEfektif > 25) {
            $jarakEfektif = 25;
        }

        $decimal = $jarakEfektif - floor($jarakEfektif);
        if ($decimal < 0.5) {
            $this->jarak_bulat = floor($jarakEfektif);
        } else {
            $this->jarak_bulat = ceil($jarakEfektif);
        }

        $this->tunjangan = $this->base_fare * $this->jarak_bulat * $this->hari_kerja;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateTunjangan();
        });

        static::creating(function ($model) {
            $userLogin = auth()->user()->name;
		    Log::record("{$userLogin } Menambah data tunjangan transport", 'Tunjangan Transport', 'create');
        });

        static::updating(function ($model) {
            $userLogin = auth()->user()->name;
            Log::record("{$userLogin} Mengupdate data tunjangan transport ID: {$model->id}", 'Tunjangan Transport', 'update');
        });
    }

    public function getIsEligibleAttribute()
    {
        return $this->employee->jenis_pegawai === 'Tetap' 
               && $this->hari_kerja >= 19 
               && $this->jarak >= 5 
               && $this->jarak <= 25;
    }

    public function getFormattedTunjanganAttribute()
    {
        return 'Rp ' . number_format($this->tunjangan, 0, ',', '.');
    }

    public function getFormattedBaseFareAttribute()
    {
        return 'Rp ' . number_format($this->base_fare, 0, ',', '.');
    }
}