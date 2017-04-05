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

class BrodcastController extends Controller
{
    private $req;
    private $user;
    private $jwtAuth;
    function __construct(Request $request, User $user, ResponseFactory $responseFactory, JWTAuth $jwtAuth)
    {
        $this->user = $user;
        $this->jwtAuth = $jwtAuth;
        $this->req = $request;
        $this->res = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function header() {
       header('Content-Type: application/json');
    }
    
    public function getBrodcastInitData() { 
        
        $allBrands = Brand::getAllBrands();

        if(count($allBrands))
        {
            $this->resultapi('1','Brands Found.', $allBrands);  
        }
        else
        {
            $this->resultapi('0','No Brands Found.', $allBrands);
        }       
    }

    public function getProductsByBrandId(Request $request) {

        if($request->bid && $request->bid > 0)
        {   
            $productsByBrandId = Product::getProductsByBrandId($request->bid);
            if(count($productsByBrandId))
            {
                $this->resultapi('1','Product Found.', $productsByBrandId);
            }
            else
            {
                $this->resultapi('0','No Product Found.', $productsByBrandId);
            }  
        }
        else
        {
            $productsByBrandId = array();
            $this->resultapi('0','Brand Id Not Found.', $productsByBrandId);
        }
    }


    public function sendNewProductRequest(Request $request) { 

        if($request->all() && count($request->all()) > 0)
        {
            $validator = Validator::make($request->all(), [
                
                'brod_desc'  => 'required|max:255',
                'brod_bid'   => 'required|numeric',
                'brod_pid'   => 'required|numeric',
                'brod_year'  => 'required|numeric',
                'brod_uid'   => 'required|numeric',                
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
                $brodRequest->status               = 0; 

                if($request->hasFile('brod_img'))
                {
                    $file = $request->file('brod_img');           
                    $destinationPath = public_path().'/asset/BrodRequestImg/';           
                    $timestamp = time().  uniqid(); 
                    $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                    $file->move($destinationPath,$filename);

                    $brodRequest->req_image   = $filename;
                }
                else
                {   
                    $brodRequest->req_image   = "";
                }

                $brodRequest->save();

                $this->resultapi('1','Product Request Added Sucessfully.', true);
            }
        } 
        else
        {   
            $productsByBrandId = array();
            $this->resultapi('0','Request Details Not Found.', true);
        }        
    }

    public function getAllBrodRequest() {
          
        $allBrodRequest = BrodRequest::getAllBrodRequest();
        if(count($allBrodRequest))
        {
            $this->resultapi('1','Brodcast Request Found.', $allBrodRequest);
        }
        else
        {
            $this->resultapi('0','No Brodcast Request Found.', $allBrodRequest);
        }        
    }

    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }
}
