<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations for tbl_follow_up_stages.
   */
  public function up(): void
  {
    Schema::create('tbl_follow_up_stages', function (Blueprint $table) {
    $table->increments('follow_up_stage_id'); // Correct for int(11)
    $table->string('stage_name', 100);
    $table->integer('day_offset');
    $table->text('sms_template')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tbl_follow_up_stages');
  }
};