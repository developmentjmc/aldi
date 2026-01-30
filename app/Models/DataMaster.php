<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use jeemce\models\CrudTrait;
use jeemce\models\MainTrait;

class DataMaster extends Model
{
    use CrudTrait, MainTrait;

    protected $table = 'data_masters';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->tipe = 'master';
        });
    }

    public function rules($scenario = null)
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }
}
