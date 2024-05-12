<?php

namespace Database\Factories;

use App\Models\proyect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class proyectFactory extends Factory
{
    protected $model = proyect::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
