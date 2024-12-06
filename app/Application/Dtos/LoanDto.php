<?php

namespace App\Application\Dtos;

class LoanDto extends BaseDto
{
    public string $id;
    public int $user_id;
    public int $book_id;
    public string $loan_date;
    public string $return_date;
}
