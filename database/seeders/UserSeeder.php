<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'roles' => 'teller',
            'name' => 'teller',
            'email' => 'teller@example.com',
            'password' => bcrypt('12345678'),
        ]);

        User::create([
            'roles' => 'cs',
            'name' => 'cs',
            'email' => 'cs@example.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
