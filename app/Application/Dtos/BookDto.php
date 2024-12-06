<?php

namespace App\Application\Dtos;

class BookDto extends BaseDto
{
    public int $id;
    public string $title;
    public string $publication_year;
    public string $author_id;
}
