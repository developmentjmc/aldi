<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use jeemce\models\CrudTrait;
use jeemce\models\MainTrait;

class Employee extends Model
{
    use CrudTrait, MainTrait;

    protected $table = 'data_employees';

    protected $guarded = [];

    protected $casts = [
        'pendidikan' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->no_hp = '+62' . $model->no_hp;
        });
    }

    public function rules($scenario = null)
    {
        return [
            'nip' => ['required', 'regex:/^[0-9]+$/', 'min:8', 'unique:data_employees,nip,' . ($this->id ?? 'NULL') . ',id'],
            'name' => ['required', 'regex:/^[a-zA-Z0-9\' ]+$/'],
            'email' => ['required', 'email', 'unique:data_employees,email,' . ($this->id ?? 'NULL') . ',id'],
            'no_hp' => ['required', 'min:10'],
            'alamat_provinsi_id' => ['required', 'exists:data_masters,id,tipe,provinsi'],
            'alamat_kabupaten_id' => ['required', 'exists:data_masters,id,tipe,kabupaten'],
            'alamat_kecamatan_id' => ['required', 'exists:data_masters,id,tipe,kecamatan'],
            'alamat_kelurahan_id' => ['required', 'exists:data_masters,id,tipe,kelurahan'],
            'alamat_detail' => ['required'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
            'tempat_lahir_kabupaten_id' => ['required', 'exists:data_masters,id,tipe,kabupaten'],
            'tanggal_lahir' => ['required', 'date'],
            'status_kawin' => ['required', 'string'],
            'jumlah_anak' => ['nullable', 'integer', 'max:99'],
            'tanggal_masuk' => ['required', 'date'],
            'jabatan' => ['required', 'string'],
            'departemen' => ['required', 'string'],
            'usia' => ['required', 'integer', 'max:150'],
            'pendidikan' => ['required', 'array'],
            'status' => ['required', 'string'],
            'jenis_pegawai' => ['required', 'string'],
        ];
    }

}
