<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\Qualifications;
use App\Countries;
use App\Cities;
use App\States;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class StateController extends Controller
{
    public function __construct() {
       $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return view('admin/State/index', ['title_for_layout' => 'States']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData() {
        $states = States::query()
        ->join('countries', 'states.country_id', '=', 'countries.id')
        ->select('states.*','countries.name as cname','countries.sortname')
        ->where('countries.status','1')
        ->get();
        
        return Datatables::of($states)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = DB::table('countries')
        ->orderBy('name')
        ->where('status',1)
        ->pluck('name', 'id');

        return view('admin/State/create', ['title_for_layout' => 'Add State', 'country' => $country]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:100',
            'orderno'      => 'required',
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/state/create')
            ->withInput()
            ->withErrors($validator);
        }

        $qual = new States;
        $qual->name     = trim($request->name);
        $qual->status   = $request->status;
        $qual->orderno  = trim($request->orderno);          
        $qual->save();

        $msg = "State Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"State",$msg,Auth::Id());

        Session::flash('success_msg', $msg);
        return redirect('/admin/state');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $state  = States::find($id);
        if(!empty($state))
        {
            $country = Countries::find($state->country_id);
        }
        else
        {
            Session::flash('error_msg', 'state not found.');
            return redirect('/admin/state');
        }
        return view('admin/State/show', ['title_for_layout' => 'View Details', 'state' => $state, 'country' => $country]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $state = States::find($id);
        $country = DB::table('countries')
        ->orderBy('name')
        ->where('status',1)
        ->pluck('name', 'id');

        if(empty($state)) {
            Session::flash('error_msg', 'state not found.');
            return redirect('/admin/state');
        }

        return view('admin/State/edit', ['title_for_layout' => 'Edit State', 'state' => $state, 'country' => $country]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        print_r($request->all());
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:100',
            'country_id'   => 'required',
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/state/' . $id . '/edit')
            ->withInput()
            ->withErrors($validator);
        }
 
        $state               = States::find($id);
        $state->name         = trim($request->name);
        $state->status       = $request->status;
        $state->country_id   = trim($request->country_id);         
        $state->save();

        $msg = "State Updated Successfully.";
        Session::flash('success_msg', $msg);
        
        $log = ActivityLog::createlog(Auth::Id(),"States",$msg,Auth::Id());          

        
        return redirect('/admin/state');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('state')->where('id', $id)->delete();
        echo 1;exit;
    } 
}
