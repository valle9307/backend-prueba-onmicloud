<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{BookAuthor,File};
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        BookAuthor::truncate();
        File::truncate();

        $this->call([
            AuthorsTableSeeder::class,
            EditorialsTableSeeder::class,
            BooksTableSeeder::class,
            UsersTableSeeder::class
        ]);

        Schema::disableForeignKeyConstraints();
    }
}
