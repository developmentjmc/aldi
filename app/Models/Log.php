<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use jeemce\models\CrudTrait;
use jeemce\models\MainTrait;
use jeemce\helpers\DBHelper;

class Log extends Model
{
    use CrudTrait, MainTrait;

    protected $table = 'logs';

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'jam' => 'datetime:H:i:s',
    ];

    public function rules($scenario = null)
    {
        return [
            'tanggal' => ['required', 'date'],
            'jam' => ['required'],
            'username' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'modul' => ['required', 'string', 'max:100'],
            'aksi' => ['required', 'string', 'max:20', 'in:create,read,update,delete,login,logout'],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function record($deskripsi, $modul, $aksi = 'read')
    {
        DBHelper::insert(<<<SQL
            INSERT INTO logs (tanggal, jam, user_id, username, deskripsi, modul, aksi, created_at, updated_at)
            VALUES (:tanggal, :jam, :user_id, :username, :deskripsi, :modul, :aksi, :created_at, :updated_at)
        SQL, [
            'tanggal' => now()->toDateString(),
            'jam' => now()->toTimeString(),
            'user_id' => auth()->id(),
            'username' => auth()->user()->username ?? auth()->user()->email ?? 'Guest',
            'deskripsi' => $deskripsi,
            'modul' => $modul,
            'aksi' => $aksi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}