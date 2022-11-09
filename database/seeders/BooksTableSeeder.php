<?php

namespace Database\Seeders;

use App\Models\{Author, Autor,Book};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::truncate();

        Book::factory(30)->create()->each(function ($book) {
            $authors = Author::inRandomOrder()->take(2)->get();
            $authors = $authors->pluck('id');
            $book->authors()->attach($authors);

            $book->file()->create(['url' => env('APP_URL').'/storage/ImprimeCita.pdf']);
        });
    }
}
