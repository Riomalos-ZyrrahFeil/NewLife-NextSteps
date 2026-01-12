<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Zee',
            'last_name' => 'Olindan',
            'email' => 'zeeolindan@gmail.com',
            'password_hash' => Hash::make('zeeolindan123'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }
}