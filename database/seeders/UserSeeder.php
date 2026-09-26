<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STAFF,
        ]);

        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PETUGAS,
        ]);
    }
}
