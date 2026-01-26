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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('id_menu')->nullable();
            $table->string('name', 128)->nullable();
            $table->string('type', 16)->nullable();
            $table->string('status', 16)->nullable();
            $table->string('route_name', 256)->nullable();
            $table->string('route_params', 256)->nullable();
            $table->string('href', 256)->nullable();
            $table->tinyInteger('sort')->nullable();
            $table->string('icon', 128)->nullable();
            $table->string('target', 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
