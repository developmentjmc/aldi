<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_presensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee');
            $table->enum('lokasi_absen', ['Gedung Utama', 'Gedung A', 'Gedung B']);
            $table->datetime('checkin')->nullable();
            $table->datetime('checkout')->nullable();
            $table->string('name');
            $table->string('jabatan');
            $table->integer('hadir')->default(0);
            $table->integer('cuti')->default(0);
            $table->integer('kuota_cuti')->default(12);
            $table->integer('izin')->default(0);
            $table->integer('kuota_izin')->default(6);
            $table->integer('durasi')->default(0);
            $table->decimal('durasi_hadir', 5, 2)->default(0);
            $table->enum('verifikasi', ['disetujui', 'ditolak'])->nullable();
            $table->string('verifikator')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status_hadir')->nullable();
            $table->timestamps();
            
            $table->foreign('id_employee')->references('id')->on('data_employees')->onDelete('cascade');
            $table->index(['id_employee', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_presensi');
    }
};
