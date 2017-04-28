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

class CountryController extends Controller
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
        return view('admin/Country/index', ['title_for_layout' => 'Countries']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData() {
        return Datatables::of(Countries::query()->orderBy('status','desc'))->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/Country/create', ['title_for_layout' => 'Add Country']);
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
            'name'         => 'required|unique:countries|max:100',
            'sortname'     => 'required|unique:countries|max:5',
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/Country/create')
            ->withInput()
            ->withErrors($validator);
        }

        $country = new Countries;
        $country->name     = trim($request->name);
        $country->status   = $request->status;
        $country->sortname = trim($request->sortname);         
        $country->save();      

        $msg = "Country Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"Country Add",$msg,Auth::Id());

        Session::flash('success_msg', $msg);
        return redirect('/admin/country');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $qual = Countries::find($id);
        if(empty($qual)){
            Session::flash('error_msg', 'country not found.');
            return redirect('/admin/country');
        }
        return view('admin/Country/show', ['title_for_layout' => 'Country View', 'qual' => $qual]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $country = Countries::find($id);
        if(empty($country)) {
            Session::flash('error_msg', 'country not found.');
            return redirect('/admin/country');
        }

        return view('admin/Country/edit', ['title_for_layout' => 'Edit Country', 'country' => $country]);
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
            'sortname'     => 'required',
            'status'       => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/country/' . $id . '/edit')
            ->withInput()
            ->withErrors($validator);
        }
 
        $country           = Countries::find($id);
        $country->name     = trim($request->name);
        $country->status   = $request->status;
        $country->sortname = trim($request->sortname);         
        $country->save();

        $msg = "Country Updated Successfully.";
        Session::flash('success_msg', $msg);
        
        $log = ActivityLog::createlog(Auth::Id(),"Country Edit",$msg,Auth::Id());          

        
        return redirect('/admin/country');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('country')->where('id', $id)->delete();
        echo 1;exit;
    } 
}
