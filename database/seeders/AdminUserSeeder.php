<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@admin.com');
        $pass  = env('ADMIN_PASSWORD', 'admin1234');

        User::updateOrCreate(
            ['email' => $email],
            ['name' => 'NurulHisan Muso', 'password' => Hash::make($pass), 'is_admin' => true]
        );
    }
}
