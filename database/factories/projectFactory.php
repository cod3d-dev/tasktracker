<?php

namespace Database\Factories;

use App\Models\project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class projectFactory extends Factory
{
    protected $model = project::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
