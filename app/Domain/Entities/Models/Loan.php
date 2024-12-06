<?php

namespace App\Domain\Entities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'loan_date',
        'return_date',
    ];
}
