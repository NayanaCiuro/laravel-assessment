<?php

namespace Tests\Unit;

use App\Models\Tour as ModelsTour;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Http\Response;

class TourTest extends TestCase
{

    
     /**
      * @test
     * Checking if api is responding successfully
     */
    public function test_the_api_returns_a_successful_response()
    {
    $response = $this->get('/tours');

    $response->assertStatus(200);
    }
    /**
     * @test
     * Creating a new tour test
     */
    public function tour_created_successfully() {
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 years', '+1 month')->getTimestamp());

        $data = [
            'start' => $startDate,
            'end'  => $startDate->addHours( $this->faker->numberBetween( 1, 8 )),
            'price' => '20.2',
        ];
    
        $this->post(route('tours.store'), $data)
            ->assertStatus(200);
    }

    /**
     * @test
     * Displaying a specific tour test
     */
    public function tour_shown_correctly() {
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 years', '+1 month')->getTimestamp());

        $tour = ModelsTour::create(
            [
            'start' => $startDate,
            'end'  => $startDate->addHours( $this->faker->numberBetween( 1, 8 )),
            'price' => '20.2',
            ]
        );
            
        $this->json('get', "tours/$tour->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['id'  => $tour->id]);
    }

     /**
      * @test
     *  Soft Deleting a specific tour test
     */
    public function tour_is_destroyed() {
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 years', '+1 month')->getTimestamp());

        $data =
            [
            'id' => 1,
            'start' => $startDate,
            'end'  => $startDate->addHours( $this->faker->numberBetween( 1, 8 )),
            'price' => '20.2',
            ];

        $tour = ModelsTour::create($data);
        
        $this->json('delete', "tours/$tour->id")
             ->assertNoContent();
       $this->assertSoftDeleted('tours', $data);
    }

    /**
      * @test
     *  Updating a specific tour test
     */
    public function updates_tour_returns_correct_data() {
        $startDate = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 years', '+1 month')->getTimestamp());
        
        $tour = ModelsTour::create(
            [
                'start' => $startDate,
                'end'   => $startDate->addHours( $this->faker->numberBetween( 1, 8 )),
                'price' => '20.2'
            ]
        );
            
        $data = [
            'start' => Carbon::now()->format('Y-m-d H:i:s'),
            'end'   => Carbon::now()->format('Y-m-d H:i:s'),
            'price' => '20.9'
        ];
            
        $this->json('put', "tours/$tour->id", $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['id' => $tour->id]);
    }
}
