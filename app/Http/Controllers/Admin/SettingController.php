<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\Transaction;
use App\Setting;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class SettingController extends Controller
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
        return view('admin/Setting/index', ['title_for_layout' => 'Settings']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData() {
        return Datatables::of(Setting::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/Setting/create', ['title_for_layout' => 'Add']);
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
            'title'         => 'required|max:255',
            'content'       => 'required',
            'postedby'      => 'required|max:100',
            'status'        => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/transaction/create')
            ->withInput()
            ->withErrors($validator);
        }

        $msg = "Transaction Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"Transaction",$msg,Auth::Id());              

        Session::flash('success_msg', $msg);
        return redirect('/admin/setting');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Transaction::find($id);

        if(empty($page)) {
            Session::flash('error_msg', 'Transaction not found.');
            return redirect('/admin/setting');
        }
        return view('admin/Transaction/show', ['title_for_layout' => 'Page View', 'page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = Setting::find($id);
        if(empty($setting)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/user');
        }
        return view('admin/Setting/edit', ['title_for_layout' => 'Edit Setting', 'page' => $setting]);

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
        $Setting               = Setting::find($id);
        $Setting->status       = $request->status;           
        $Setting->save();

        $msg = "User Activation Status Changed Sucessfully.";
        $log = ActivityLog::createlog(Auth::Id(),"Setting",$msg,Auth::Id()); 
        Session::flash('success_msg', $msg);
        return redirect('/admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('setting')->where('id', $id)->delete();
        echo 1; exit;
    } 
}
