<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_visitor', function (Blueprint $table) {
            $table->integer('visitor_id')->autoIncrement();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->integer('age')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->date('first_visit_date')->nullable();
            $table->time('first_visit_time')->nullable();
            
            // Foreign key column
            $table->integer('location_id')->nullable();

            // Explicit Foreign Key Constraint
            $table->foreign('location_id')
                  ->references('location_id')
                  ->on('tbl_location')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_visitor');
    }
};