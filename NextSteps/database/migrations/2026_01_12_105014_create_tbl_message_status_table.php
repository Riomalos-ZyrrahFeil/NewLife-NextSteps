<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_message_status', function (Blueprint $table) {
            $table->id('message_status_id');
            $table->integer('visitor_id'); 
            $table->integer('follow_up_stage_id')->nullable(); 
            
            $table->date('scheduled_date')->nullable();
            $table->string('status', 50)->default('Texted'); //
            
            $table->timestamps();

            // Set up relationship index
            $table->index('visitor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_message_status');
    }
};