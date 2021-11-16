<?php namespace Tests\Repositories;

use App\RoomAndBroad;
use App\Repositories\RoomAndBroadRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class RoomAndBroadRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var RoomAndBroadRepository
     */
    protected $roomAndBroadRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->roomAndBroadRepo = \App::make(RoomAndBroadRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->make()->toArray();

        $createdRoomAndBroad = $this->roomAndBroadRepo->create($roomAndBroad);

        $createdRoomAndBroad = $createdRoomAndBroad->toArray();
        $this->assertArrayHasKey('id', $createdRoomAndBroad);
        $this->assertNotNull($createdRoomAndBroad['id'], 'Created RoomAndBroad must have id specified');
        $this->assertNotNull(RoomAndBroad::find($createdRoomAndBroad['id']), 'RoomAndBroad with given id must be in DB');
        $this->assertModelData($roomAndBroad, $createdRoomAndBroad);
    }

    /**
     * @test read
     */
    public function test_read_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->create();

        $dbRoomAndBroad = $this->roomAndBroadRepo->find($roomAndBroad->id);

        $dbRoomAndBroad = $dbRoomAndBroad->toArray();
        $this->assertModelData($roomAndBroad->toArray(), $dbRoomAndBroad);
    }

    /**
     * @test update
     */
    public function test_update_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->create();
        $fakeRoomAndBroad = factory(RoomAndBroad::class)->make()->toArray();

        $updatedRoomAndBroad = $this->roomAndBroadRepo->update($fakeRoomAndBroad, $roomAndBroad->id);

        $this->assertModelData($fakeRoomAndBroad, $updatedRoomAndBroad->toArray());
        $dbRoomAndBroad = $this->roomAndBroadRepo->find($roomAndBroad->id);
        $this->assertModelData($fakeRoomAndBroad, $dbRoomAndBroad->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_room_and_broad()
    {
        $roomAndBroad = factory(RoomAndBroad::class)->create();

        $resp = $this->roomAndBroadRepo->delete($roomAndBroad->id);

        $this->assertTrue($resp);
        $this->assertNull(RoomAndBroad::find($roomAndBroad->id), 'RoomAndBroad should not exist in DB');
    }
}
