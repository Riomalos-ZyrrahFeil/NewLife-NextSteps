<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tbl_task_assignment', function (Blueprint $table) {
      $table->integer('task_assignment_id')->autoIncrement();
      $table->integer('visitor_id');
      $table->integer('user_id');
      $table->datetime('assigned_at');

      // Foreign Key Constraints
      $table->foreign('visitor_id')
        ->references('visitor_id')
        ->on('tbl_visitor')
        ->onDelete('cascade');

      $table->foreign('user_id')
        ->references('user_id')
        ->on('tbl_user')
        ->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tbl_task_assignment');
  }
};