<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataKecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idKlaten = DB::table('data_masters')->where('tipe', 'kabupaten')->where('name', 'Klaten')->value('id');
        $idBantul = DB::table('data_masters')->where('tipe', 'kabupaten')->where('name', 'Bantul')->value('id');
        $idSleman = DB::table('data_masters')->where('tipe', 'kabupaten')->where('name', 'Sleman')->value('id');
        DB::table('data_masters')->insert([
            //klaten
            ['tipe' => 'kecamatan', 'name' => 'Kalikotes', 'id_parent' => $idKlaten],
            ['tipe' => 'kecamatan', 'name' => 'Wedi', 'id_parent' => $idKlaten],
            //bantul
            ['tipe' => 'kecamatan', 'name' => 'Pajangan', 'id_parent' => $idBantul],
            ['tipe' => 'kecamatan', 'name' => 'Dlingo', 'id_parent' => $idBantul],
            //sleman
            ['tipe' => 'kecamatan', 'name' => 'Godean', 'id_parent' => $idSleman],
            ['tipe' => 'kecamatan', 'name' => 'Minggir', 'id_parent' => $idSleman],
        ]);
    }
}
