<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::truncate();

        Author::factory(15)->create()->each(function ($author) {
            $author->file()->create(['url' => env('APP_URL').'/storage/autor.png']);
        });
    }
}
