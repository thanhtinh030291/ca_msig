<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRoomAndBoardAPIRequest;
use App\Http\Requests\API\UpdateRoomAndBoardAPIRequest;
use App\Models\RoomAndBoard;
use App\Repositories\RoomAndBoardRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RoomAndBoardController
 * @package App\Http\Controllers\API
 */

class RoomAndBoardAPIController extends AppBaseController
{
    /** @var  RoomAndBoardRepository */
    private $roomAndBoardRepository;

    public function __construct(RoomAndBoardRepository $roomAndBoardRepo)
    {
        $this->roomAndBoardRepository = $roomAndBoardRepo;
    }

    /**
     * Display a listing of the RoomAndBoard.
     * GET|HEAD /roomAndBoards
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $roomAndBoards = $this->roomAndBoardRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($roomAndBoards->toArray(), 'Room And Boards retrieved successfully');
    }

    /**
     * Store a newly created RoomAndBoard in storage.
     * POST /roomAndBoards
     *
     * @param CreateRoomAndBoardAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRoomAndBoardAPIRequest $request)
    {
        $input = $request->all();

        $roomAndBoard = $this->roomAndBoardRepository->create($input);

        return $this->sendResponse($roomAndBoard->toArray(), 'Room And Board saved successfully');
    }

    /**
     * Display the specified RoomAndBoard.
     * GET|HEAD /roomAndBoards/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RoomAndBoard $roomAndBoard */
        $roomAndBoard = $this->roomAndBoardRepository->find($id);

        if (empty($roomAndBoard)) {
            return $this->sendError('Room And Board not found');
        }

        return $this->sendResponse($roomAndBoard->toArray(), 'Room And Board retrieved successfully');
    }

    /**
     * Update the specified RoomAndBoard in storage.
     * PUT/PATCH /roomAndBoards/{id}
     *
     * @param int $id
     * @param UpdateRoomAndBoardAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoomAndBoardAPIRequest $request)
    {
        $input = $request->all();

        /** @var RoomAndBoard $roomAndBoard */
        $roomAndBoard = $this->roomAndBoardRepository->find($id);

        if (empty($roomAndBoard)) {
            return $this->sendError('Room And Board not found');
        }

        $roomAndBoard = $this->roomAndBoardRepository->update($input, $id);

        return $this->sendResponse($roomAndBoard->toArray(), 'RoomAndBoard updated successfully');
    }

    /**
     * Remove the specified RoomAndBoard from storage.
     * DELETE /roomAndBoards/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RoomAndBoard $roomAndBoard */
        $roomAndBoard = $this->roomAndBoardRepository->find($id);

        if (empty($roomAndBoard)) {
            return $this->sendError('Room And Board not found');
        }

        $roomAndBoard->delete();

        return $this->sendResponse($id, 'Room And Board deleted successfully');
    }
}
