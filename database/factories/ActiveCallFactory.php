<?php

namespace Database\Factories;

use App\Models\ActiveCall;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveCallFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveCall::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'active_call_flg' => $this->faker->boolean(),
            'customer_id' => $this->faker->randomDigit(),
            'user_id' => $this->faker->randomDigit(),
        ];
    }
}
