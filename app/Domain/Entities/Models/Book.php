<?php

namespace App\Domain\Entities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'id',
        'title',
        'publication_year',
        'author_id',
    ];
}
