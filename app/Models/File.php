<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'url'
    ];

    //Relations
    public function fileable()
    {
        return $this->morphTo();
    }
}
