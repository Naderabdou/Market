<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

       User::create([
            'name' => 'admin',
            'phone' => '01147507444',
            'password' => 123456789,
           'type' => 'admin',
           'address' => 'cairo',

        ]);
    }
}
