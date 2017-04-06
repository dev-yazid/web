<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\Product;
use App\Brand;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class ProductController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/Product/index', ['title_for_layout' => 'Product']);
    }

    /**
     * Fetch data tobe used in datatable
     */
    public function getData() {
        $prodDetails = Product::query()
            ->join('brands', 'products.brand', '=', 'brands.id')
            ->select('products.*','brands.brand')
            ->get();

        return Datatables::of($prodDetails)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $brandname = DB::table('brands')->where('status',1)->orderBy('brand')->lists('brand', 'id');
        $startyear = 1980;
        $now = date('Y');
        $yeararr = array();
        for ($i = $now; $i >= $startyear; $i--) {
            $yeararr[$i] = $i;
        }
        return view('admin/Product/create', ['title_for_layout' => 'Add Product', 'brandname' => $brandname, 'year' => $yeararr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'pname' => 'required|max:100',
                    'year' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/product/create')
                            ->withInput()
                            ->withErrors($validator);
        }

        $prod = new Product;
        $prod->pname = trim($request->pname);
        $prod->brand = trim($request->brand);
        $prod->year = trim($request->year);
        $prod->status = $request->status;
        $prod->save();

        $msg = "Product Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(), "Product Add", $msg, Auth::Id());

        Session::flash('success_msg', $msg);
        return redirect('/admin/product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $prod = Product::find($id);
        if (empty($prod)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/product');
        }
        return view('admin/Product/show', ['title_for_layout' => 'Product View', 'prod' => $prod]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $brandname = DB::table('brands')->lists('brand', 'id');
        $startyear = 1980;
        // current year
        $now = date('Y');
        $yeararr = array();
        for ($i = $now; $i >= $startyear; $i--) {
            $yeararr[$i] = $i;
        }
        $qual = Product::find($id);
       /* echo '<pre>';
        print_r($qual);
        exit;*/
        if (empty($qual)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/product');
        }
        return view('admin/Product/edit', ['title_for_layout' => 'Edit Product', 'qual' => $qual, 'year' => $yeararr, 'brandname' => $brandname]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
                    'pname' => 'required|max:100',
                    'brand' => 'required',
                    'year' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/product/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        }

        $prod = Product::find($id);
        $prod->pname = trim($request->pname);
        $prod->year = $request->year;
        $prod->brand = $request->brand;
        $prod->status = $request->status;
        $prod->update();

        $msg = "Product Updated Successfully.";
        Session::flash('success_msg', $msg);

        $log = ActivityLog::createlog(Auth::Id(), "Product Edit", $msg, Auth::Id());


        return redirect('/admin/product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::table('products')->where('id', $id)->delete();
        echo 1;
        exit;
    }

}
