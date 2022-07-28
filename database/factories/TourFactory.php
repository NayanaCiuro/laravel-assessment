<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    { 
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 years', '+1 month')->getTimestamp());
        
        return [
            'start' => $startDate,
            'end' => $startDate->addHours( $this->faker->numberBetween( 1, 8 )),
            'price' => $this->faker->randomFloat(2, 3, 5),
        ];
    }
}
