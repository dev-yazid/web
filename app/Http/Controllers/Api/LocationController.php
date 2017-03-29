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
use App\Countries;
use App\States;
use App\Cities;

class LocationController extends Controller
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
    public function getCountries() { 
        $countries = DB::table('countries')
        ->select('id','name')
        ->where('status', 1)
        ->get();

        return $this->resultapi('1','Success',$countries);
    }

    public function getStates(Request $request) {

        $cId = $request->input('countryId');
        $states =  DB::table('states')
        ->select('id','name')
        ->where('country_id', $cId)
        ->where('status', 1)->get();

        return $this->resultapi('1','Success',$states);
    }

    public function getCities(Request $request) {

        $sId = $request->input('stateId');

        $cities = DB::table('cities')
        ->select('id','name')
        ->where('state_id', $sId)
        ->where('status', 1)->get();

        /* This condition for those states have no cities */
        if(count($cities) < 1)
        {
            $dsId = 9999;
            $cities = DB::table('cities')
            ->select('id','name')
            ->where('state_id', $dsId)
            ->where('status', 1)->get();
        }

        return $this->resultapi('1','success',$cities);
    }



    public function getAllLocations(Request $request) {

        //All states id of switzerland  and fetching all locations of switzerland by state id//
        $statesId = ['3424','3425','3426','3427','3428','3429','3430','3431','3432','3433','3434','3435','3436','3437','3438','3439','3440','3441','3442','3443','3444','3445','3446','3447','3448','3449','3450','3451','3452','3453','3454','3455','9999'];
        
        $AllLocations = Cities::whereIn('state_id', $statesId)    
                    ->select('name','id')
                    ->where('status', "1")
                    ->get();
      
        return $this->resultapi('1','success',$AllLocations);        
    }

    public function getAutocompleteLocations(Request $request) {       
       
        //All states id of switzerland  and fetching all locations of switzerland by state id//
        $statesId = ['3424','3425','3426','3427','3428','3429','3430','3431','3432','3433','3434','3435','3436','3437','3438','3439','3440','3441','3442','3443','3444','3445','3446','3447','3448','3449','3450','3451','3452','3453','3454','3455','9999'];
        
        $AllLocations = Cities::whereIn('state_id', $statesId)    
            ->select('name','id')
            ->Where('name', 'LIKE', '%'.$request->q.'%')
            ->where('status', "1")
            ->take(10)
            ->get();
      
        return $this->resultapi('1','success',$AllLocations);        
    }

    
    
    public function resultapi($status, $message, $result = array()) {
        
        $finalArray['STATUS'] = $status;
        $finalArray['MESSAGE'] = $message;
        $finalArray['DATA'] = $result;
        
        echo json_encode($finalArray);
        die;
    }
}
