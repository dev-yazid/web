<?php //
/*namespace App\Http\Controllers\Api;
use App\Libraries\Miscellaneous;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Input;
use Validator;
use Hash;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiCityController extends Controller
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
  /*  public function getCities(Request $request) {

       $cities = DB::table('cities')
        ->select('id','name')
        ->where('status', 1)
        ->get(4);

        return $this->resultapi('1','Success',$cities);
    }*/
    
    /*public function getAllCities() {

        $allcities = DB::table('cities')
                ->select('id', 'name')
                ->where('status', '1')
                ->take(4)
                ->get();
      
        if (count($allcities) > 0) {
            $this->resultapi('1', 'Cities Found.', $allcities);
        } else {
            $this->resultapi('0', 'No City Found.', $allcities);
        }
    }

    public function resultapi($status, $message, $result = array()) {
        
        $finalArray['STATUS'] = $status;
        $finalArray['MESSAGE'] = $message;
        $finalArray['DATA'] = $result;
        
        echo json_encode($finalArray);
        die;
    }
}*/