<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * default roles
         */
        DB::table('roles')->insert([
            ['name' => 'Superadmin'],
            ['name' => 'Manager HRD'],
            ['name' => 'Admin HRD'],
        ]);

        /**
         * default super admin users
         */
        User::factory()->create([
            'id_role' => 1,
            'name' => 'Aldi Pradana',
            'username' => 'aldi',
            'email' => 'aldi@aldi.com',
            'password'=> bcrypt('password'),
        ]);

        /**
         * default menus
         */
        $this->call([
            MenuSeeder::class,
        ]);

        /**
         * default access for superadmin role
         */
        DB::table('accesses')->insert([
            [
                'id_role' => 1,
                'id_menu' => 1,
                'read' => 'all',
                'view' => 'all',
                'create' => 'all',
                'update' => 'all',
                'delete' => 'all',
                'publish' => 'all',
            ],
            [
                'id_role' => 1,
                'id_menu' => 2,
                'read' => 'all',
                'view' => 'all',
                'create' => 'all',
                'update' => 'all',
                'delete' => 'all',
                'publish' => 'all',
            ],
            [ // ini harusnya superadmin tidak punya akses, keperluan deebug aja
                'id_role' => 1,
                'id_menu' => 3,
                'read' => 'all',
                'view' => 'all',
                'create' => 'all',
                'update' => 'all',
                'delete' => 'all',
                'publish' => 'all',
            ],
        ]);

        $this->call([
            MasterDataProvinsiSeeder::class,
            MasterDataKabupatenSeeder::class,
            MasterDataKecamatanSeeder::class,
            MasterDataKelurahanSeeder::class,
        ]);

        // base fare
        DB::table('data_master')->insert([
            [
                'tipe' => 'base fare',
                'description' => '5000',
            ],
        ]);
    }
}
