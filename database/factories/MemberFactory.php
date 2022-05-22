<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberFactory extends Factory
{


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->randomNumber(2),
            'gender' => $this->faker->numberBetween(0, 1),
            'telephone' => $this->faker->phoneNumber(),
            'profession' => $this->faker->randomElement(['IT民工', '工商个体', '学生', '客服', '医生', '教师', '销售人员', '']),
            'deposit' => $this->faker->randomElement([100, 200, 300]),
            'balance' => $this->faker->numberBetween(0, 200),
            'password' => Hash::make('123123'),
        ];
    }
}
