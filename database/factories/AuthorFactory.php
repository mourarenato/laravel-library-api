<?php

namespace Database\Factories;

use App\Domain\Entities\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'birthdate' => '2000-01-01',
        ];
    }
}
