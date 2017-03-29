<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\Transaction;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class TransactionController extends Controller
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
        return view('admin/Transaction/index', ['title_for_layout' => 'Transaction Details']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData() {
        return Datatables::of(Transaction::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/Transaction/create', ['title_for_layout' => 'Add']);
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

        $TransactionTransaction = new Transaction;
        $TransactionTransaction->title        = $request->title;
        $TransactionTransaction->content      = $request->content;
        $TransactionTransaction->postedby     = $request->postedby;
        $TransactionTransaction->status       = $request->status;    
        $TransactionTransaction->save();

        $msg = "Transaction Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"Transaction",$msg,Auth::Id());              

        Session::flash('success_msg', $msg);
        return redirect('/admin/transaction');
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
            return redirect('/admin/transaction');
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
        $page = Transaction::find($id);
        if(empty($page)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/user');
        }
        return view('admin/Transaction/edit', ['title_for_layout' => 'Edit Transaction', 'page' => $page]);

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
            'title'         => 'required|max:255',
            'content'       => 'required',
            'postedby'      => 'required|max:100',
            'status'        => 'required',
            ]);

        if ($validator->fails()) {
            return redirect('admin/transaction/' . $id . '/edit')
            ->withInput()
            ->withErrors($validator);
        }
        //Update Blog
        $Transaction               = Transaction::find($id);
        $Transaction->title        = $request->title;
        $Transaction->content      = $request->content;
        $Transaction->postedby     = $request->postedby;
        $Transaction->status       = $request->status;           
        $Transaction->save();

        $msg = "Transaction Updated Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"Transaction",$msg,Auth::Id()); 
        Session::flash('success_msg', $msg);
        return redirect('/admin/transaction');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('transaction')->where('id', $id)->delete();
        echo 1; exit;
    } 
}
