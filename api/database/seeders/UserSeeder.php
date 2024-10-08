<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'role' => Role::ADMIN,
            'approved' => true,
            'blocked' => false,
        ]);
    }
}
