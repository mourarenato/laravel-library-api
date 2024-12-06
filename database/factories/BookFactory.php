<?php

namespace Database\Factories;

use App\Domain\Entities\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'publication_year' => $this->faker->year(),
            'author_id' => $this->faker->randomDigit(),
        ];
    }
}
