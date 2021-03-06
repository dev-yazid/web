<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\BrodRequest;
use App\BrodResponse;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class ResponseController extends Controller
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
        return view('admin/Response/index', ['title_for_layout' => 'Broadcast Response']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData() {
        
        //$brodResponse = BrodResponse::query()
        /*->join('products', 'brod_requests.prod_id', '=', 'products.id')
        ->join('brands', 'brod_requests.brand_id', '=', 'brands.id')
        ->join('users', 'brod_requests.user_id', '=', 'users.id')*/
        //->get();

        return Datatables::of( BrodResponse::query() )->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/Response/create', ['title_for_layout' => 'Add Response']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
       
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brodRequests = BrodRequest::query()
        ->join('products', 'brod_requests.prod_id', '=', 'products.id')
        ->leftJoin('brands', 'brod_requests.brand_id', '=', 'brands.id')
        ->leftJoin('users', 'brod_requests.user_id', '=', 'users.id')
        ->first();

        $brodResponse = BrodResponse::query()
        ->join('users', 'brod_responses.seller_id', '=', 'users.id')
        ->where('brod_responses.id',$id )
        ->first();

        //print_r($brodResponse );

        if(empty($brodRequests) && empty($brodResponse)) {

            Session::flash('error_msg', 'Details Not Found.');
            return redirect('/admin/request');
        }
        return view('admin/Response/show', ['title_for_layout' => 'Response Details', 'brodRequests' => $brodRequests, 'brodResponse' => $brodResponse]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        

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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('blog')->where('id', $id)->delete();
        echo 1; exit;
    } 
}
