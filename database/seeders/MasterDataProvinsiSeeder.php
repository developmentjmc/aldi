<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;

class MasterDataProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FacadesDB::table('data_masters')->insert([
            ['tipe' => 'provinsi', 'name' => 'Jawa Tengah'],
            ['tipe' => 'provinsi', 'name' => 'DI Yogyakarta']
        ]);
    }
}
