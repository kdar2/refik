<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@refik.test'],
            [
                'name'              => 'Refik Admin',
                'password'          => Hash::make('password'),
                'role'              => 'admin',
                'newsletter'        => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
