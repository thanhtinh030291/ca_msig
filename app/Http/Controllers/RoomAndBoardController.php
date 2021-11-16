<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomAndBoardRequest;
use App\Http\Requests\UpdateRoomAndBoardRequest;
use App\RoomAndBoard;
use App\HBS_CL_CLAIM;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Arr;

class RoomAndBoardController extends Controller
{
    /** @var  RoomAndBoardRepository */
    
    public function __construct()
    {
        //$this->authorizeResource(RoomAndBoard::class);
        parent::__construct();
    }

    /**
     * Display a listing of the RoomAndBoard.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $search_params = [
            'created_user' => $request->get('created_user'),
            'created_at' => $request->get('created_at'),
            'updated_user' => $request->get('updated_user'),
            'updated_at' => $request->get('updated_at'),
        ];
        $admin_list = User::getListIncharge();
        $limit_list = config('constants.limit_list');
        $limit = $request->get('limit');
        $per_page = !empty($limit) ? $limit : Arr::first($limit_list);

        $roomAndBoards =  RoomAndBoard::findByParams($search_params)->orderBy('id', 'desc');
        $roomAndBoards  = $roomAndBoards->paginate($per_page);

        return view('room_and_boards.index', compact('search_params', 'admin_list', 'limit', 'limit_list', 'roomAndBoards' ));           
    }

    /**
     * Show the form for creating a new RoomAndBoard.
     *
     * @return Response
     */
    public function create()
    {

        return view('room_and_boards.create');
    }

    /**
     * Store a newly created RoomAndBoard in storage.
     *
     * @param CreateRoomAndBoardRequest $request
     *
     * @return Response
     */
    public function store(CreateRoomAndBoardRequest $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;
        $incur_date = $request->_incur_date;
        foreach ($incur_date as $key => $value) {
            $rp = getHourStartEnd($value);
            $data['line_rb'][] = ['hours_start' => $rp['hours_start'] , 'hours_end' =>  $rp['hours_end'] ];
        }
        RoomAndBoard::create($data);
        $request->session()->flash('status', 'Room And Board saved successfully.');

        return redirect(route('roomAndBoards.index'));
    }

    /**
     * Display the specified RoomAndBoard.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(RoomAndBoard $roomAndBoard)
    {
        $listCodeClaim = HBS_CL_CLAIM::where('clam_oid', $roomAndBoard->code_claim)->limit(20)->pluck('cl_no', 'clam_oid');
        return view('room_and_boards.show', compact('roomAndBoard', 'listCodeClaim'));
    }

    /**
     * Show the form for editing the specified RoomAndBoard.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(RoomAndBoard $roomAndBoard)
    {

        $listCodeClaim = HBS_CL_CLAIM::where('clam_oid', $roomAndBoard->code_claim)->limit(20)->pluck('cl_no', 'clam_oid');
        return view('room_and_boards.edit',  compact('roomAndBoard', 'listCodeClaim'));
    }

    /**
     * Update the specified RoomAndBoard in storage.
     *
     * @param int $id
     * @param UpdateRoomAndBoardRequest $request
     *
     * @return Response
     */
    public function update(RoomAndBoard $roomAndBoard, UpdateRoomAndBoardRequest $request)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        $incur_date = $request->_incur_date;
        foreach ($incur_date as $key => $value) {
            $rp = getHourStartEnd($value);
            $data['line_rb'][] = ['hours_start' => $rp['hours_start'] , 'hours_end' =>  $rp['hours_end'] ];
        }
        RoomAndBoard::updateOrCreate(['id' => $roomAndBoard->id], $data);

        $request->session()->flash('status', 'Room And Board updated successfully.'); 
        return redirect(route('roomAndBoards.index'));
    }

    /**
     * Remove the specified RoomAndBoard from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(RoomAndBoard $roomAndBoard)
    {
        $roomAndBoard->delete();
        return redirect(route('roomAndBoards.index'))->with('status', 'Room And Board deleted successfully.');
    }
}
