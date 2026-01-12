<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tbl_task_assignment', function (Blueprint $table) {
      $table->id('task_assignment_id'); // standard bigIncrements

      /**
       * Use unsignedBigInteger to match Laravel's default id() type.
       * If your parent tables use increments(), use unsignedInteger() instead.
       */
      $table->unsignedBigInteger('visitor_id');
      $table->unsignedBigInteger('user_id');
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