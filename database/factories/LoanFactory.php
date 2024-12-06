<?php

namespace Database\Factories;

use App\Domain\Entities\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomDigit(),
            'book_id' => $this->faker->randomDigit(),
            'loan_date' => '2021-01-01',
            'return_date' => '2022-01-01',
        ];
    }
}
