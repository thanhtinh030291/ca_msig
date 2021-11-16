<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\RoomAndBroad;

class RoomAndBroadApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/room_and_broads', $roomAndBroad
        );

        $this->assertApiResponse($roomAndBroad);
    }

    /**
     * @test
     */
    public function test_read_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/room_and_broads/'.$roomAndBroad->id
        );

        $this->assertApiResponse($roomAndBroad->toArray());
    }

    /**
     * @test
     */
    public function test_update_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->create();
        $editedRoomAndBroad = factory(RoomAndBroad::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/room_and_broads/'.$roomAndBroad->id,
            $editedRoomAndBroad
        );

        $this->assertApiResponse($editedRoomAndBroad);
    }

    /**
     * @test
     */
    public function test_delete_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/room_and_broads/'.$roomAndBroad->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/room_and_broads/'.$roomAndBroad->id
        );

        $this->response->assertStatus(404);
    }
}
