<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;
    
    protected $table = 'editorials';

    protected $fillable = [
        'name',
        'address',
        'phone_number'
    ];

    //Relations
    public function books()
    {
        return $this->hasMany(Book::class);
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
