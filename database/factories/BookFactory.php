<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->catchPhrase(),
            //            'name'      => $this->faker->sentence(3),
            'author'    => $this->faker->name(),
            'publisher' => $this->faker->company(),
            'category'  => $this->faker->word(),
            'edition'   => $this->faker->numerify('第#次印刷'),
            'ISBN'      => $this->faker->unique()->isbn13(),
            'price'     => $this->faker->randomFloat(2, 5, 10),
            'num'       => $this->faker->randomNumber(2),
            'lend'      => 0,
            'location'  => $this->faker->bothify('??-##')
        ];
    }
}
