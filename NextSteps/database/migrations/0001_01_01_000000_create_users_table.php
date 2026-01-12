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
        Schema::create('tbl_user', function (Blueprint $table) {
        $table->integer('user_id')->autoIncrement();
        $table->string('first_name', 100);
        $table->string('last_name', 100);
        $table->string('password_hash', 255);
        $table->string('email', 100)->unique();
        $table->enum('role', ['admin', 'volunteer'])->default('volunteer');
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->tinyInteger('is_deleted')->default(0);
        $table->datetime('created_at')->useCurrent();
        $table->rememberToken(); 
    });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id')->nullable()->index(); 
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_user');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
