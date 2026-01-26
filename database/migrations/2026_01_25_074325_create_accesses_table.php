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
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_menu');
            $table->string('read', 16)->nullable();
            $table->string('view', 16)->nullable();
            $table->string('create', 16)->nullable();
            $table->string('update', 16)->nullable();
            $table->string('delete', 16)->nullable();
            $table->string('publish', 16)->nullable();
            $table->unique(['id_role', 'id_menu'], 'unik');
            $table->foreign('id_menu')->references('id')->on('menus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesses');
    }
};
