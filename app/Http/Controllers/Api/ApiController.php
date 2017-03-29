<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use DB;
use App\Api\User;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator; 
use Hash;
use Illuminate\Support\Facades\URL;
use Mail; 
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller {
    private $req;
    private $user;
    private $jwtAuth;
    public function __construct(Request $request, User $user, ResponseFactory $responseFactory, JWTAuth $jwtAuth)
    {
        header('Content-Type: application/json');
        $this->user = $user;
        $this->jwtAuth = $jwtAuth;
        $this->req = $request;
        $this->res = $responseFactory;
    }

    /**
     * Get a CSRF-TOKEN.
     *
     * @return Response
     */
    public function getFormToken() { 
  
        $formToken['_token'] = csrf_token(); 
        echo json_encode($formToken);
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

    public function logout(Request $request) {
        $user_id = $request->user_id;
        Auth::logout();
        $this->resultapi('1','Success');
    }   

    public function forgotPassword(Request $request) {

            $user = User::where('email', '=', $request->email)
                        ->where('password','!=','')->first();
            if($user == null){
                $this->resultapi('0','We can\'t find a user with that e-mail address.'); 
            }

            $this->validate($request, ['email' => 'required|email']);

            $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
            });

            switch ($response) {
                case Password::RESET_LINK_SENT:
                    $this->resultapi('1','Success',trans($response));

                case Password::INVALID_USER:
                    $this->resultapi('0','Fail',trans($response));
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