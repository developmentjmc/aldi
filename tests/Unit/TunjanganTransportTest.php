<?php

namespace Tests\Unit;

use App\Models\DataMaster;
use App\Models\Employee;
use App\Models\TunjanganTransport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TunjanganTransportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create minimal master data for testing
        DataMaster::create(['id' => 1, 'tipe' => 'provinsi', 'name' => 'Test Provinsi']);
        DataMaster::create(['id' => 2, 'tipe' => 'kabupaten', 'name' => 'Test Kabupaten', 'id_parent' => 1]);
        DataMaster::create(['id' => 3, 'tipe' => 'kecamatan', 'name' => 'Test Kecamatan', 'id_parent' => 2]);
        DataMaster::create(['id' => 4, 'tipe' => 'kelurahan', 'name' => 'Test Kelurahan', 'id_parent' => 3]);
    }

    /**
     * Test pegawai yang memenuhi syarat
     */
    public function test_tunjangan_memenuhi_syarat()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $employee = Employee::create([
            'nip' => '123456789',
            'name' => 'Test Employee',
            'email' => 'test@example.com',
            'no_hp' => '081234567890',
            'jenis_pegawai' => 'Tetap',
            'alamat_provinsi_id' => 1,
            'alamat_kabupaten_id' => 1,
            'alamat_kecamatan_id' => 1,
            'alamat_kelurahan_id' => 1,
            'alamat_detail' => 'Test Address',
            'tempat_lahir_kabupaten_id' => 1,
            'tanggal_lahir' => '1990-01-01',
            'status_kawin' => 'Belum Kawin',
            'tanggal_masuk' => '2020-01-01',
        ]);

        $tunjangan = TunjanganTransport::create([
            'employee_id' => $employee?->id,
            'base_fare' => 5000,
            'jarak' => 10.3,
            'hari_kerja' => 22,
            'kantor' => 'Gedung A',
            'bulan_tunjangan' => now()->format('Y-m'),
        ]);

        // Assert
        $this->assertEquals(10, $tunjangan->jarak_bulat); 
        $this->assertEquals(1100000, $tunjangan->tunjangan); // 5000 * 10 * 22 = 1,100,000
        $this->assertTrue($tunjangan->is_eligible);
    }

    /**
     * Test pegawai yang tidak memenuhi syarat (hari kerja kurang)
     */
    public function test_tunjangan_tidak_memenuhi_syarat_hari_kerja_kurang()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Buat employee tetap
        $employee = Employee::create([
            'nip' => '987654321',
            'name' => 'Test Employee 2',
            'email' => 'test2@example.com',
            'no_hp' => '081234567891',
            'jenis_pegawai' => 'Tetap',
            'alamat_provinsi_id' => 1,
            'alamat_kabupaten_id' => 1,
            'alamat_kecamatan_id' => 1,
            'alamat_kelurahan_id' => 1,
            'alamat_detail' => 'Test Address 2',
            'tempat_lahir_kabupaten_id' => 1,
            'tanggal_lahir' => '1990-01-01',
            'status_kawin' => 'Belum Kawin',
            'tanggal_masuk' => '2020-01-01',
        ]);

        // tt dengan hari kerja < 19
        $tunjangan = TunjanganTransport::create([
            'employee_id' => $employee?->id,
            'base_fare' => 5000,
            'jarak' => 15,
            'hari_kerja' => 18, // < 19 hari
            'kantor' => 'Gedung B',
            'bulan_tunjangan' => now()->format('Y-m'),
        ]);

        $this->assertEquals(0, $tunjangan->jarak_bulat);
        $this->assertEquals(0, $tunjangan->tunjangan);
        $this->assertFalse($tunjangan->is_eligible);
    }
}
