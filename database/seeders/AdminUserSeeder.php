<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $pass  = env('ADMIN_PASSWORD', 'secret123');

        User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Administrator', 'password' => Hash::make($pass), 'is_admin' => true]
        );
    }
}
