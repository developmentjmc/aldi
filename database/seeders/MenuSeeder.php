<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'id_menu' => null,
                'name' => 'Dashboard',
                'type' => 'menu',
                'status' => 'publish',
                'route_name' => 'backend.dashboard',
                'route_params' => null,
                'href' => null,
                'sort' => 1,
                'icon' => 'bi bi-house fs-3 me-3',
                'target' => null,
            ],
            [
                'id_menu' => null,
                'name' => 'Kelola User',
                'type' => 'menu',
                'status' => 'publish',
                'route_name' => 'backend.user.index',
                'route_params' => null,
                'href' => null,
                'sort' => 2,
                'icon' => 'bi bi-person-fill-add fs-3 me-3',
                'target' => null,
            ],
            [
                'id_menu' => null,
                'name' => 'Data Pegawai',
                'type' => 'menu',
                'status' => 'publish',
                'route_name' => 'backend.pegawai.index',
                'route_params' => null,
                'href' => null,
                'sort' => 3,
                'icon' => 'bi bi-person-lines-fill fs-3 me-3',
                'target' => null,
            ],
            [
                'id_menu' => null,
                'name' => 'Tunjangan Transport',
                'type' => 'menu',
                'status' => 'publish',
                'route_name' => 'backend.tunjangan-transport.index',
                'route_params' => null,
                'href' => null,
                'sort' => 4,
                'icon' => 'bi bi-truck fs-3 me-3',
                'target' => null,
            ],
            [
                'id_menu' => null,
                'name' => 'Log Aktivitas',
                'type' => 'menu',
                'status' => 'publish',
                'route_name' => 'backend.log.index',
                'route_params' => null,
                'href' => null,
                'sort' => 5,
                'icon' => 'bi bi-truck fs-3 me-3',
                'target' => null,
            ],
            [
                'id_menu' => null,
                'name' => 'Data Presensi',
                'type' => 'menu',
                'status' => 'publish',
                'route_name' => 'backend.presensi.index',
                'route_params' => null,
                'href' => null,
                'sort' => 6,
                'icon' => 'bi bi-people fs-3 me-3',
                'target' => null,
            ],
            
        ]);
    }
}
