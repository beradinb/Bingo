<?php

namespace Modules\Bingo\Http\Controllers;

use Illuminate\Http\Request;
use App\Authorizable;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Log;
use Flash;
use Modules\Bingo\Entities\BingoCategory;
use Yajra\DataTables\DataTables;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Http;
use Storage;
use Modules\Bingo\Entities\BingoRoom;
use Carbon\Carbon;


class RoomsController extends Controller
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Rooms';

        // module name
        $this->module_name = 'rooms';

        // directory path of the module
        $this->module_path = 'rooms';

        // module icon
        $this->module_icon = 'fas fa-sitemap';

        // module model name, path
        $this->module_model = "Modules\Bingo\Entities\BingoRoom";

        //skin id of the brand from the env file for the api update
        $this->skinId = env('SKIN_ID');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::paginate();

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "bingo::backend.rooms.index_datatable",
            compact('module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    public function index_data()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::select('id', 'title', 'bingo_type','category_id','status');

        $data = $$module_name;

        return Datatables::of($$module_name)
                        ->addColumn('action', function ($data) {
                            $module_name = $module_name = 'bingo.'.$this->module_name;
                            $module_title = $this->module_title;
                            return view('backend.includes.action_column', compact('module_title', 'module_name', 'data'));
                        })
                        ->editColumn('title', function ($data) {
                            return $data->title.' '.$data->status_formatted;
                        })
                        ->editColumn('category_id', function ($data) {
                            return $data->category->title;
                        })
                        ->rawColumns(['title','bingo_type', 'action'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';
        $categories = BingoCategory::all();
        // Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "bingo::backend.rooms.create",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action','categories')
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $$module_name_singular = $module_model::create($request->all());

        Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/bingo/$module_name");
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $$module_name_singular = $module_model::findOrFail($id);

        // $promotions = $$module_name_singular->latest()->paginate();



        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "bingo::backend.rooms.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular")
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';

        $$module_name_singular = $module_model::findOrFail($id);

        $categories = BingoCategory::all();

        // dd($$module_name_singular);

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "bingo::backend.rooms.edit",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'categories')
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->update($request->all());

        Flash::success("<i class='fas fa-check'></i> '".Str::singular($module_title)."' Updated Successfully")->important();
        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');
        return redirect("admin/bingo/$module_name");
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        dd("dead");

        $module_action = 'destroy';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->delete();

        Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Deleted Successfully!')->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.', ID:'.$$module_name_singular->id." ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/bingo/$module_name");
    }

    /**
     * Update Bingo Rooms and their Categories based on the 888 API url 
     */
    public function update_bingo_api ()
    {
        $skin_id = $this->skinId;

        $response = Http::get("https://external-api-unicorn.bingosys.net/bingo-lobby?skinId=$skin_id");
        $data = $response->json();
        // dd($data);
        $rooms = $data['data']['rooms'];
        $categories= $data['data']['categories'];

        try {
            foreach($rooms as $r){

                switch ($r['cardType']) {
                    case 3:
                        $bingoType='90';
                        break;

                    case 4:
                        $bingoType='52';
                        break;

                    default:
                        $bingoType='75';
                        break;
                }

                $bingo_image = Str::slug($r['displayName']);

                $room = BingoRoom::updateOrCreate(
                    [
                        'room_888_id' =>$r['id']
                    ],
                    [
                        'title' =>$r['displayName'],
                        'image' => 'storage/bingo-rooms/'.$bingo_image.'.jpg',
                        'bingo_type'=> $bingoType,

                        'status' =>'1'
                    ]);
                    // dd($room);
            }
            foreach($categories as $c){
                $category = BingoCategory::updateOrCreate(
                    [
                        'category_888_id' =>$c['id']
                    ],
                    [
                        'title' =>$c['displayName'],
                        'type'=> $c['type']
                    ]);

                    if($category){
                        try {
                            foreach($c['rooms'] as $c_room){
                                $room = BingoRoom::where('room_888_id', $c_room['id'])->first();
                                $room->category_id = $category->id;
                                $room->save();
                            }
                        } catch (\Throwable $th) {
                            echo($th->getMessage());
                        }
                    }
            }
        } catch (\Throwable $th) {
            echo($th->getMessage());
        }
        return redirect()->back();
    }
}
