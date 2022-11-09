<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'name'     => 'Heriberto García Valle',
            'email'    => 'email1@hotmail.com',
            'password' => Hash::make('password') 
        ]);

        User::create([
            'name'     => 'Liliana Peralta Palma',
            'email'    => 'email2@hotmail.com',
            'password' => Hash::make('password') 
        ]);

        User::create([
            'name'     => 'Andres García Becerril',
            'email'    => 'email3@hotmail.com',
            'password' => Hash::make('password') 
        ]);
    }
}
