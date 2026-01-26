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
        Schema::create('data_employees', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 64)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('no_hp', 64)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('status_kawin', 32)->nullable();
            $table->integer('jumlah_anak')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('jabatan', 128)->nullable();
            $table->string('departemen', 128)->nullable();
            $table->integer('usia')->nullable();
            $table->unsignedBigInteger('alamat_provinsi_id')->nullable();
            $table->unsignedBigInteger('alamat_kabupaten_id')->nullable();
            $table->unsignedBigInteger('alamat_kecamatan_id')->nullable();
            $table->unsignedBigInteger('alamat_kelurahan_id')->nullable();
            $table->text('alamat_detail')->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->unsignedBigInteger('tempat_lahir_kabupaten_id')->nullable();
            $table->text('pendidikan')->nullable();
            $table->string('status', 32)->default('active')->nullable();
            $table->string('jenis_pegawai', 32)->nullable();
            $table->decimal('cuti', 5, 2)->nullable();
            $table->decimal('kuota_cuti', 5, 2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('alamat_provinsi_id')->references('id')->on('data_masters')->onDelete('restrict');
            $table->foreign('alamat_kabupaten_id')->references('id')->on('data_masters')->onDelete('restrict');
            $table->foreign('alamat_kecamatan_id')->references('id')->on('data_masters')->onDelete('restrict');
            $table->foreign('alamat_kelurahan_id')->references('id')->on('data_masters')->onDelete('restrict');
            $table->foreign('tempat_lahir_kabupaten_id')->references('id')->on('data_masters')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_employees');
    }
};
