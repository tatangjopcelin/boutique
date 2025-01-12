<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Nkem berlin',
            'email' => 'nkem@gmail.com',
            'password' => Hash::make('password'),

        ]);
    }
}
