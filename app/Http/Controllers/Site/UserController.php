<?php
namespace App\Http\Controllers\Site;
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

class UserController extends Controller
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
    public function index()
    {
        //
    }
    /**
     * Get a user details with token.
     *
     * @return Response
     */
    public function postLogincheck(Request $request) {
      
        $user = $this->user->authenticateSiteUser(
            $this->req->input('email'), 
            $this->req->input('password')
            );
        if (!$user) {
            return $this->res->json([
                'error' => true,
                'code' => null,
                'message' => 'email and Password are invalid.'
                ], Response::HTTP_OK);
        }

        $user['tokenId'] = $this->jwtAuth->fromUser($user);
        $user['error'] = false;
        $user['message'] = $user['first_name']." ".$user['last_name'] ." "." is logged in successfully!!!";

        return $this->res->json($user, Response::HTTP_OK);
    }
    /**
     * Get a user by the token from the header.
     *
     * @return Response
     */
    public function getByToken()
    {
        try {
            return $this->jwtAuth->parseToken()->authenticate();
        } catch(TokenExpiredException $e) {
            return [
            'error' => true,
            'code'  => 11,
            'message'   => 'Token Expired'
            ];
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return [
            'error' => true,
            'code'  => 12,
            'message'   => 'Invalid Token'
            ];
        } catch (JWTException $e) {
            return [
            'error' => true,
            'code'  => 13,
            'message'   => ''
                //Token absent
            ];
        }
    }
    /**
     * Get a CSRF-TOKEN.
     *
     * @return Response
     */
    public function getFormToken() {
       
        echo $formToken['_token'] = csrf_token();
       
        return $this->resultapi(1,"ccc",$formToken);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $input = Input::get();
        $userRules = User::$CareTakerRules;
        $validation = Validator::make($input, $userRules);
        if($validation->fails()){
          return $this->res->json([
            'errors' => [$validation->messages()],
            'code' => 400 // bad request
            ]);
      }else{
         $userObj = New User();
         $userObj->firstname = $input['first_name'];
         $userObj->lastname = $this->req->input('last_name');
         $userObj->email = $this->req->input('email');
         $userObj->mobile = $this->req->input('mobile');
         $userObj->password = Hash::make($this->req->input('password'));
         $userObj->save();
         return $this->res->json([
            'error' => false,
            'message' => 'User registered successfully.'
            ], Response::HTTP_OK);
         
         
     }
 }
 
    // Forgot Password
 public function postForgotPassword(Request $request) {
    $mobile = $this->req->input('mobile');
    
    if($mobile != "") {
        $data = User::SELECT('*')->WHERE('mobile','=',$mobile)->WHERE('user_type','!=',0)->FIRST();
        if(!$data) {
            return $this->res->json([
                'error' => true,
                'code' => null,
                'message' => 'User does not exist.'
                ], Response::HTTP_OK);
        } else {
            $generatedToken = "xyz";
            $data->reset_token = $generatedToken;
            $data->save();
                // Send Email - Start
            $emailData['user'] = $data->first_name .' '.$data->last_name;
            $emailData['resetLink'] = $request->root() ."/reset?token=".$generatedToken;
            $subject = "Forgot Password";
            $to = $data->email;
            Miscellaneous::sendEmail('emails.forgot', $emailData, $subject, $emailData['user'], $to, '', '', '');
                // Send Email - End
            return $this->res->json([
                'error' => false,
                'message' => 'We have e-mailed your password reset link.'
                ], Response::HTTP_OK);
        }
    }
}

    // Check Reset Token
public function getCheckResetToken($resetToken){
    if(isset($resetToken) && !empty($resetToken)){
        $data = User::SELECT('*')->WHERE('reset_token','=',$resetToken)->WHERE('user_type','!=',0)->FIRST();
        if(empty($data)){
            return $this->res->json([
                'error' => true,
                'code' => null,
                'message' => 'Invalid user token.'
                ], Response::HTTP_OK);
        } else {
            return $this->res->json([
                'success' => true,
                'message' => 'true'
                ], Response::HTTP_OK);
        }
    }
}

    // Change Reset Password
public function postResetPassword(Request $request){
    $resetToken = $this->req->input('resettoken');
    $password = $this->req->input('password');
    $data = User::SELECT('*')->WHERE('reset_token','=',$resetToken)->WHERE('user_type','!=',0)->FIRST();
    if(!empty($data)){
        $data->password = Hash::make($password);
            // $data->reset_token = '';
        $data->save();
        return $this->res->json([
            'success' => true,
            'message' => 'Your password has been set successfully.'
            ], Response::HTTP_OK);
    } else {
        return $this->res->json([
            'error' => true,
            'message' => 'Your password has not been set successfully.'
            ], Response::HTTP_OK);
    }
}

public function resultapi($status,$message,$result = array()) {
    $finalArray['STATUS'] = $status;
    $finalArray['MESSAGE'] = $message;
    $finalArray['RESULT'] = $result;
    echo json_encode($finalArray);
    die;
}
}
