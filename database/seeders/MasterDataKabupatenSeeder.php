<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataKabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idJawaTengah = DB::table('data_masters')->where('tipe', 'provinsi')->where('name', 'Jawa Tengah')->value('id');
        $idYogya = DB::table('data_masters')->where('tipe', 'provinsi')->where('name', 'DI Yogyakarta')->value('id');
        DB::table('data_masters')->insert([
            // jawa tengah
            ['tipe' => 'kabupaten', 'name' => 'Klaten', 'id_parent' => $idJawaTengah],
            //yogya
            ['tipe' => 'kabupaten', 'name' => 'Bantul', 'id_parent' => $idYogya],
            ['tipe' => 'kabupaten', 'name' => 'Sleman', 'id_parent' => $idYogya],
        ]); 
    }
}
