<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataKelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idKalikotes = DB::table('data_masters')->where('tipe', 'kecamatan')->where('name', 'Kalikotes')->value('id');
        $idWedi = DB::table('data_masters')->where('tipe', 'kecamatan')->where('name', 'Wedi')->value('id');
        $idPajangan = DB::table('data_masters')->where('tipe', 'kecamatan')->where('name', 'Pajangan')->value('id');
        $idDlingo = DB::table('data_masters')->where('tipe', 'kecamatan')->where('name', 'Dlingo')->value('id');
        $idGodean = DB::table('data_masters')->where('tipe', 'kecamatan')->where('name', 'Godean')->value('id');
        $idMinggir = DB::table('data_masters')->where('tipe', 'kecamatan')->where('name', 'Minggir')->value('id');
        DB::table('data_masters')->insert([
            //kalikotes
            ['tipe' => 'kelurahan', 'name' => 'Jatinom', 'id_parent' => $idKalikotes],
            ['tipe' => 'kelurahan', 'name' => 'Gempol', 'id_parent' => $idKalikotes],
            //wedi
            ['tipe' => 'kelurahan', 'name' => 'Banjarharjo', 'id_parent' => $idWedi],
            ['tipe' => 'kelurahan', 'name' => 'Wedi', 'id_parent' => $idWedi],
            //pajangan
            ['tipe' => 'kelurahan', 'name' => 'Pajangan', 'id_parent' => $idPajangan],
            ['tipe' => 'kelurahan', 'name' => 'Girimulyo', 'id_parent' => $idPajangan],
            //dlingo
            ['tipe' => 'kelurahan', 'name' => 'Dlingo', 'id_parent' => $idDlingo],
            ['tipe' => 'kelurahan', 'name' => 'Terong', 'id_parent' => $idDlingo],
            //godean
            ['tipe' => 'kelurahan', 'name' => 'Godean', 'id_parent' => $idGodean],
            ['tipe' => 'kelurahan', 'name' => 'Sidoarum', 'id_parent' => $idGodean],
            //minggir
            ['tipe' => 'kelurahan', 'name' => 'Minggir', 'id_parent' => $idMinggir],
            ['tipe' => 'kelurahan', 'name' => 'Sambirejo', 'id_parent' => $idMinggir],
        ]);     
    }
}
