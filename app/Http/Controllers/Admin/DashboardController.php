<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\Brand;
use App\Transaction;
use App\BrodRequest;
use App\BrodResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response 
     */

    public function __construct() {
       $this->middleware('auth');
    }

    public function index()
    {
        $totalBrodResponse  = BrodResponse::all()->count();
        $totalBrodRequest   = BrodRequest::all()->count();
        $totalUsers         = User::all()->count();
        $totalProduct       = Product::all()->count();       
        $totalBrand         = Brand::all()->count();
        $totalTransactions  = Transaction::all()->count();

        return view('admin/Dashboard/index', [

            'title_for_layout'  => 'Dashboard', 
            'totalProduct'      => $totalProduct,
            'totalUsers'        => $totalUsers,
            'totalBrand'        => $totalBrand,
            'totalBrodRequest'  => $totalBrodRequest,
            'totalBrodResponse' => $totalBrodResponse,
            'totalTransactions' => $totalTransactions,
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
