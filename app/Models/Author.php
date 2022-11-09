<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    protected $fillable = [
        'name',
        'last_name',
        'email'
    ];

    //Relations
    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_autors');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    //Scopes
    public function scopeOfSearch($query, $param)
    {
        if (!empty($param)) {
            return $query->where('name', 'like', $param . '%');
        }

        return $query;
    }
}
