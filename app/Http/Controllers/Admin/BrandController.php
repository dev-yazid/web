<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\Brand;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Image;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class BrandController extends Controller
{
    public function __construct() {
       $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){  
        return view('admin/Brand/index', ['title_for_layout' => 'Brand']);
    }
    
    /**
     * Fetch data tobe used in datatable
    */
    public function getData(){ 
        return Datatables::of(Brand::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/Brand/create', ['title_for_layout' => 'Add Brand']);
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
            'brand'         => 'required|max:255',
            'status'   => 'required',
            ]);

        if ($validator->fails()) {
            return redirect('/admin/brand/create')
            ->withInput()
            ->withErrors($validator);
        }
        if($validator->passes() && $request->hasFile('image'))
        {
            $file = $request->file('image');           
            $destinationPath = public_path().'/asset/brand/';           
            $timestamp = time().  uniqid(); 
            $filename = $timestamp.'_'.trim($file->getClientOriginalName());
            $file->move($destinationPath,$filename);  
        }
        $brand = new Brand;
        $brand->brand   = $request->brand;
        if($request->hasFile('image'))
        $brand->image   = $filename;  
        $brand->status  = $request->status;     
        $brand->save();

        $msg = "Brand Added Successfully.";
        $log = ActivityLog::createlog(Auth::Id(),"Brand",$msg,Auth::Id());              

        Session::flash('success_msg', $msg);
        return redirect('/admin/brand');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $page = Brand::find($id);

        if(empty($page)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/BrandController');
        }
        return view('admin/Brand/show', ['title_for_layout' => 'Page View', 'page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Brand::find($id);
       // echo $brandedit;//exit;
        if(empty($page)) {
            Session::flash('error_msg', 'Brand not found.');
            return redirect('/admin/user');
        }
        return view('admin/Brand/edit', ['title_for_layout' => 'Edit Brand', 'page' => $page]);

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
            'brand'         => 'required|max:255',
            'status'   => 'required',
            ]);

        if ($validator->fails()) {
            return redirect('admin/brand/' . $id . '/edit')
            ->withInput()
            ->withErrors($validator);
        }

        $file = $request->file('image');
        $rules = array('file' => 'mimes:png,jpg,jpeg,gif| max:50012');
        $validator = Validator::make(array('file'=> $file), $rules);
        if($validator->passes() && $request->hasFile('image'))
        {
            $destinationPath = public_path().'/asset/brand/';   
            $timestamp = time().  uniqid();
            $filename = $timestamp.'_'.trim($file->getClientOriginalName());
            $upload_success = $file->move($destinationPath, $filename);
          
            $path = public_path().'/asset/brand/';
            $destinationPath = public_path('/asset/brand');
            $img = Image::make($path.$filename);
            $img->resize(270, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$filename);     
        }

        //Update Community
        $page              = Brand::find($id);
        $page->brand       = $request->brand;
        $page->status      = $request->status;
        if($request->hasFile('image'))
            $page->image       = $filename;
        
        $page->save();

        $msg = "Brand Updated Successfully.";
       // $log = ActivityLog::createlog(Auth::Id(),"OurCommunity",$msg,Auth::Id()); 
        Session::flash('success_msg', $msg);
        return redirect('/admin/brand');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        DB::table('brands')->where('id', $id)->delete();
        echo 1; exit;
    } 
}
