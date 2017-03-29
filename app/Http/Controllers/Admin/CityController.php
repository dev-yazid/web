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

class CityController extends Controller
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
        return view('admin/City/index', ['title_for_layout' => 'Cities']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData() {
        
        $countries = Cities::query()      
        ->where('status','1')
        ->get();
     //   print_r($countries);exit;
        return Datatables::of($countries)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $states = DB::table('states')
        ->orderBy('name')
        ->where('status', '1')
        ->pluck('name', 'id');

        return view('admin/City/create', ['title_for_layout' => 'Add Cities', 'states' => $states,]);
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
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/city/create')
            ->withInput()
            ->withErrors($validator);
        }

        $city = new Cities;
        $city->name     = trim($request->name);
        $city->status   = $request->status;
        $city->state_id = 1;
        $city->save();

        $msg = "City Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"City Add",$msg,Auth::Id());

        Session::flash('success_msg', $msg);
        return redirect('/admin/city');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city   = Cities::find($id);
        
        if(!empty($city))
        {
            $state   = States::find($city->state_id);
            $country = Countries::find($state->country_id);
        }
        else
        {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/city');
        }
        return view('admin/City/show', ['title_for_layout' => 'City Details', 'city' => $city, 'state' => $state, 'country' => $country]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $city = Cities::find($id);
        $states = DB::table('states')
        ->orderBy('name')
        ->where('status', '1')
        ->pluck('name', 'id');

        if(empty($city)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/city');
        }

        return view('admin/City/edit', ['title_for_layout' => 'Edit Cities', 'city' => $city, 'states' => $states]);
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
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:100',            
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/city/' . $id . '/edit')
            ->withInput()
            ->withErrors($validator);
        }
 
        $city           = Cities::find($id);
        $city->name     = trim($request->name);
        $city->status   = $request->status;
        $city->state_id = 1;     
        $city->save();

        $msg = "City Updated Successfully.";
        Session::flash('success_msg', $msg);
        
        $log = ActivityLog::createlog(Auth::Id(),"City Edit",$msg,Auth::Id());          

        
        return redirect('/admin/city');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('city')->where('id', $id)->delete();
        echo 1;exit;
    } 
}
