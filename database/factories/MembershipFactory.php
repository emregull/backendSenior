<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MembershipFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'Plan 1',
            'price' => 5,
            'period' => 1,
        ];
    }
}
