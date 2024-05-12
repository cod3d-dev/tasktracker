<?php

namespace Database\Factories;

use App\Models\type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class typeFactory extends Factory
{
    protected $model = type::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
