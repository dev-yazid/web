<?php
namespace App\Http\Controllers\Api;
use App\Libraries\Miscellaneous;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Hash;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Brand;
use App\Product;
use App\BrodRequest;
use App\BrodResponse;
use App\LanguageTag;
use File;
use Image;
use Auth;

class BrodcastController extends Controller
{
    private $req;
    private $user;
    private $jwtAuth;
    function __construct(Request $request, User $user, ResponseFactory $responseFactory, JWTAuth $jwtAuth)
    {
        header('Content-Type: application/json');

        $this->user = $user;
        $this->jwtAuth = $jwtAuth;
        $this->req = $request;
        $this->res = $responseFactory;

        $this->middleware('jwt.auth', ['except' => ['getBrodcastInitData', 'getProductsByBrandId','sendNewProductRequest']]);
    }
    
    public function getBrodcastInitData() { 
        $bserUrlImg = asset('/public/asset/brand/');       
        
        $allBrands = Brand::getAllBrands();
        if(count($allBrands))
        {
            $this->resultapi2('1',$bserUrlImg, $allBrands);  
        }
        else
        {
            $this->resultapi2('0',$bserUrlImg, $allBrands);
        }       
    }

    public function getProductsByBrandId(Request $request) {
        $messages = LanguageTag::DisplayMessages();
        if($request->bid && $request->bid > 0)
        {   
            $productsByBrandId = Product::getProductsByBrandId($request->bid);
            if(count($productsByBrandId))
            {
                $this->resultapi('1',$messages['Brod1'], $productsByBrandId);
            }
            else
            {
                $this->resultapi('0',$messages['Brod2'], $productsByBrandId);
            }  
        }
        else
        {
            $productsByBrandId = array();
            $this->resultapi('0',$messages['Brod3'], $productsByBrandId);
        }
    }

    public function sendNewProductRequest(Request $request) {        
        $messages = LanguageTag::DisplayMessages();
        $validator = Validator::make($request->all(), [
            
            'brod_desc'  => 'required|max:255',
            'brod_bid'   => 'required|numeric',
            'brod_pid'   => 'required|numeric',
            'brod_year'  => 'required|numeric',
            'brod_uid'   => 'required|numeric',
            'brod_img'   => 'mimes:jpeg,jpg,png,gif|max:1024',             
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0',$validator->errors()->all(), 0);
        }
        else
        {
            $brodRequest                       = new BrodRequest;               
            $brodRequest->description          = $request->brod_desc;
            $brodRequest->brand_id             = $request->brod_bid;
            $brodRequest->prod_id              = $request->brod_pid;
            $brodRequest->prod_year            = $request->brod_year;
            $brodRequest->user_id              = $request->brod_uid;
            //$brodRequest->req_image            = "";                
            $brodRequest->is_seller_replied    = 0;
            $brodRequest->status               = 1; 

            if($request->partrequest_image_upload =="YES")
            {
                if($request->hasFile('brod_img'))
                {
                    $file = $request->file('brod_img');
                    $path = public_path().'/asset/brodcastImg/';
                    $thumbPath = public_path('/asset/brodcastImg/thumb');

                    $timestamp = time().  uniqid(); 
                    $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                    File::makeDirectory(public_path().'/asset/', 0777, true, true);
                    $file->move($path,$filename);

                    $img = Image::make($path.$filename);
                    $img->resize(100, 100, function ($constraint) { 
                        $constraint->aspectRatio();
                    })->save($thumbPath.'/'.$filename);

                    $brodRequest->req_image   = $filename;
                }
            }
            
            $brodRequest->save();
            $this->resultapi('1',$messages['Brod4'], true);
        } 
    }

    public function getUpdateProductRequest(Request $request) {
        $messages = LanguageTag::DisplayMessages();
        $validator = Validator::make($request->all(), [
            
            'brod_id'    => 'required|numeric',
            'brod_desc'  => 'required|max:255',
            'brod_bid'   => 'required|numeric',
            'brod_pid'   => 'required|numeric',
            'brod_year'  => 'required|numeric',
            'brod_uid'   => 'required|numeric',
            'brod_img'   => 'mimes:jpeg,jpg,png,gif|max:1024'               
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0',$validator->errors()->all(), 0);
        }
        else
        {            
            $brodRequest = BrodRequest::find($request->brod_id);
            if($brodRequest->status < 3) 
            {           
                $brodRequest->description          = $request->brod_desc;
                $brodRequest->brand_id             = $request->brod_bid;
                $brodRequest->prod_id              = $request->brod_pid;
                $brodRequest->prod_year            = $request->brod_year;
                $brodRequest->is_details_updated   = 1;
                
                if($request->partrequest_image_upload === "YES")
                {
                    if($request->hasFile('brod_img'))
                    {
                        $file = $request->file('brod_img');
                        $path = public_path().'/asset/brodcastImg/';
                        $thumbPath = public_path('/asset/brodcastImg/thumb');                             
                        $timestamp = time().  uniqid(); 
                        $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                        File::makeDirectory(public_path().'/asset/', 0777, true, true);
                        $file->move($path,$filename);

                        $img = Image::make($path.$filename);
                        $img->resize(100, 100, function ($constraint) { 
                            $constraint->aspectRatio();
                        })->save($thumbPath.'/'.$filename);

                        $brodRequest->req_image   = $filename;
                    }
                }
                $brodRequest->save();
                $this->resultapi('1',$messages['Brod5'], true);
            }
            else
            {
                $this->resultapi('0',$messages['Brod6'], false);
            }                      
        }                    
    }

    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }

    public function resultapi2($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['imgPath']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }
}
