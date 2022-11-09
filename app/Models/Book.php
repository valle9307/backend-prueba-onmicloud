<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'editorial_id',
        'title',
        'publish_at',
        'price'
    ];

    //Relations
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'books_authors');
    }

    public function editorial()
    {
        return $this->belongsTo(Editorial::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    //Scopes
    public function scopeOfSearch($query, $param)
    {
        if (!empty($param)) {
            return $query->where('title', 'like', $param . '%');
        }

        return $query;
    }
}
