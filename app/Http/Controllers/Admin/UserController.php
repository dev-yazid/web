<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\User;
use App\Countries;
use App\States;
use App\Cities;
use App\ActivityLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\UserProfiles;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\EmailTemplates;

class UserController extends Controller
{
    
    public function __construct() {
       $this->middleware('auth'); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/User/index', ['title_for_layout' => 'List Users']);
    }
    
    public function getData() { 

        $userDetails = User::query()
        ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
        ->select('users.*','user_profiles.*','users.id as id')
        ->where("users.usertype",'!=',"Super Admin")
        ->get();

        return Datatables::of($userDetails)->make(true);       
       
        //return Datatables::of(User::query()->where("usertype",'!=',"Super Admin"))->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create() {
        
        $usertype = [
            'Seller'        => 'Seller',
            'Customer'      => 'Customer',            
        ];

        //$states = DB::table('states')->orderBy('name')->lists('name','id');
        $cities = DB::table('cities')->orderBy('name')->lists('name','id');

        return view('admin/User/create', ['title_for_layout' => 'Add Seller','usertype' => $usertype, 'cities' => $cities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
     
        $validator = Validator::make($request->all(), [
            
            //'name'              => 'required|max:100',            
            'email'             => 'email|max:100',            
            'phone_number'      => 'required|min:8|max:12|unique:users',
            'shop_name'         => 'required|max:100',
            'shop_city'         => 'required|max:100',
        ]);
        
        if ($validator->fails()) {

            return redirect('/admin/user/create')
            ->withInput()
            ->withErrors($validator);
        }

        $seller_mobile_verify_code = rand (1000 , 9999);
        
        $user = new User;
        $user->email                        = $request->email;        
        $user->status                       = $request->status;
        $user->phone_number                 = $request->phone_number;
        //$user->is_customer_updated          = 0;
        $user->is_seller_updated            = 1;
        //$user->name                         = $request->seller_name;
        $user->email                        = $request->email;
        $user->email_verified               = 'No';
        $user->seller_mobile_verified       = 'No';
        $user->seller_mobile_verify_code    = $seller_mobile_verify_code;
        $user->save();
        /******************************/
    
        $lastUserinsertedId = $user->id;
        $userProfile = new UserProfiles;
        $userProfile->user_id           = $lastUserinsertedId;
        $userProfile->seller_name       = $request->seller_name ? $request->seller_name : "Feeh User";
        $userProfile->shop_name         = $request->shop_name;
        //$userProfile->shop_email        = $request->email;
        $userProfile->shop_mobile       = $request->phone_number;
        $userProfile->shop_address      = $request->shop_address;           
        $userProfile->shop_city         = $request->shop_city;
        $userProfile->shop_zipcode      = $request->shop_zipcode;
        $userProfile->shop_start_time   = $request->shop_start_time;
        $userProfile->shop_close_time   = $request->shop_close_time;
        $userProfile->shop_location_map = $request->map_url;

        $bserUrlImg = asset('/public/asset/shopLicence/thumb/');
        if($request->hasFile('shop_document'))
        {           
            $file = $request->file('shop_document');
            $path = public_path().'/asset/shopLicence/';
            $thumbPath = public_path('/asset/shopLicence/thumb/');

            $timestamp = time().  uniqid(); 
            $filename = $timestamp.'_'.trim($file->getClientOriginalName());
            File::makeDirectory(public_path().'asset/', 0777, true, true);
            $file->move($thumbPath,$filename);

            $userProfile->shop_document     = $filename; 
        }
        if($userProfile->save() && $user->save())
        {
            $sendSms = User::sendSms(trim($request->phone_number), trim($seller_mobile_verify_code));
      
            $msg = "Seller Registered Successfully.";
            $log = ActivityLog::createlog(Auth::Id(),"New Seller Added Sucessfully.",$msg,Auth::Id());       
            
            Session::flash('success_msg', $msg);
            return redirect('/admin/user');
        } 
        else
        {
            Session::flash('error_msg', 'Problem in Registration Please Try Again.');
            return redirect('/admin/user'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $user = DB::table('users')
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        ->leftJoin('cities', 'user_profiles.customer_city', '=', 'cities.id')
        //->leftJoin('cities', 'user_profiles.shop_city', '=', 'cities.id')
        //->select('users.name','users.phone_number','users.password','user_profiles.customer_address','user_profiles.customer_zipcode','user_profiles.customer_email','user_profiles.customer_city')
        ->select('users.*','users.id as userId','user_profiles.*','cities.name','users.name as fullname')
        ->where('users.id',$id)
        ->first();        
        
        if(empty($user))
        {
            Session::flash('error_msg', 'User Not Exist.');
            return redirect('/admin/user');
        }
        else
        {
            
            if($user->customer_city)
            {
                $cusromerCity = DB::table('cities')->where('id',$user->customer_city)->select('name')->first();
                $user->cust_city_name = $cusromerCity->name;
            }
            else
            {
                $user->cust_city_name = "Not Available";
            }
            if($user->shop_city)
            {
                $shopCity = DB::table('cities')->where('id',$user->shop_city)->select('name')->first();
                $user->shop_city_name = $shopCity->name;
            }
            else
            {
                $user->cust_city_name = "Not Available";
            }
        }       
        
        return view('admin/User/show', ['title_for_layout' => 'User Details', 
            'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $user = User::find($id);       
        $isAdmin = false;
        if(!empty($user))
        {           
            $userId   = $user->id;
            $userType = $user->usertype;
            if($userType == "Super Admin")
            {
                $isAdmin = true;
            } 
        }
        else
        {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user');
        }
        return view('admin/User/edit', ['title_for_layout' => 'Edit Details','user' => $user,'isAdmin' => $isAdmin]);
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
        $user = User::find($id);
        $error = false;

        if($user->usertype == "Super Admin")
        {             
            $validator = Validator::make($request->all(), [
                'name'              => 'required|max:100',
                'email'             => 'required|email|max:100',
                'phone_number'      => 'required|max:15',                
            ]);

            if ($validator->fails()) 
            {
                return redirect('admin/user/'. $id .'/edit')
                ->withInput()
                ->withErrors($validator);
            }
            else
            {
                $user->name         = $request->name;
                $user->phone_number = $request->phone_number;
                $user->email        = $request->email;

                $files = Input::file('profile_image'); 
                if($files && !empty($files))
                {
                    foreach($files as $file)
                    {
                        $oldPicture = $user->profile_image; 
                        $rules = array('file' => 'mimes:jpg,jpeg,png,gif|max:512');
                        $validator = Validator::make(array('file'=> $file), $rules);
                        if($validator->passes())
                        {
                            $destinationPath = public_path().'/asset/User/Profile/thumb';                        
                            $timestamp = time();
                            $filename = $timestamp. '_' .trim($file->getClientOriginalName());
                            $upload_success = $file->move($destinationPath, $filename);
                            /*if($upload_success)
                            {             
                                $unlink_success = File::delete($destinationPath.$oldPicture); 
                            }*/
                        }
                        else
                        {                
                            $error = true;
                            $filename = $oldPicture;
                            return Redirect::to('/admin/user/'.$id.'/edit')->withInput()->withErrors($validator);
                        }
                    }

                    $user->profile_image = $filename;
                }

                if($request->update_password && $request->update_password == "Yes")
                {                   
                    $validator = Validator::make($request->all(), [
                
                        'password'          => 'required|min:6|same:confirmpassword',
                        'confirmpassword'   => 'required|min:6|same:password',
                    ]);

                    if ($validator->fails()) 
                    {
                        $error = true;
                        return redirect('admin/user/'. $id .'/edit')
                        ->withInput()
                        ->withErrors($validator);
                    }
                    else
                    {                        
                        $user->password  = bcrypt($request->password);
                    }
                }
                
                if($error == false)
                {
                    $user->save();
                    $msg = "Admin Details Updated Successfully.";
                    $log = ActivityLog::createlog(Auth::Id(),"Admin User",$msg,Auth::Id());
                    Session::flash('success_msg', $msg);
                    if($request->update_password && $request->update_password == "Yes")
                    {
                        return redirect('/admin/logout/');
                    }
                    else
                    {                        
                        return redirect('/admin/user/'.$id.'/edit');
                    }                    
                }
            }
        }
        else
        {           
            $validator = Validator::make($request->all(), [
                'firstname'         => 'required|max:100',
                'lastname'          => 'required|max:100',
                'email'             => 'required|email|max:100',
            ]);

            if ($validator->fails()) {
                return redirect('admin/user/'. $id .'/edit')
                ->withInput()
                ->withErrors($validator);
            }
            else
            {                
                $user->firstname   = $request->firstname;
                $user->lastname    = $request->lastname;          
                $user->status      = $request->status;
                $user->save();

                $msg = "User Updated Successfully.";
                $log = ActivityLog::createlog(Auth::Id(),"Admin User",$msg,Auth::Id());
                
                Session::flash('success_msg', $msg);
                return redirect('/admin/user');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)  {
        $user = User::findOrFail($id);
        $user->delete();
        echo 1;
        return redirect('/admin/user');
    } 


    public function statusChange(Request $request){

        if($request->id)
        {
            echo $id = $request->id;       
            $user = User::find($id);
           
            if(count($user) > 0 )
            {                
                if($user->status != $request->status )
                {
                    $user->status = trim($request->status);
                    $user->save();

                    User::sendSmsAdmin(trim($user->phone_number));
                                   
                    $msg = "User Status Changed Successfully";
                    Session::flash('success_msg', $msg);
                    $log = ActivityLog::createlog(Auth::Id(),"Admin User",$msg,Auth::Id());
                }
                else
                {
                    $msg = "User Status Already In Selected Status.";
                    Session::flash('error_msg', $msg);
                    $log = ActivityLog::createlog(Auth::Id(),"Admin User",$msg,Auth::Id());
                }              
            }
            else
            {
                $msg = "User Details Not Found.";
                Session::flash('error_msg', $msg);                
            }
        }
        else
        {
            $msg = "User Id Not Found.";
            Session::flash('error_msg', $msg);                
        }

        return redirect('/admin/user');
    }   
}
