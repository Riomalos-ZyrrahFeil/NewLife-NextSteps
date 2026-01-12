<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_location', function (Blueprint $table) {
            $table->integer('location_id')->autoIncrement(); 
            $table->string('location_name', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_location');
    }
};