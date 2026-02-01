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
        Schema::create('tunjangan_transports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('base_fare', 10, 2);
            $table->decimal('jarak', 5, 2);
            $table->integer('hari_kerja');
            $table->decimal('jarak_bulat', 5, 0)->default(0);
            $table->decimal('tunjangan', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('kantor')->nullable();
            $table->string('bulan_tunjangan')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('data_employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunjangan_transports');
    }
};
