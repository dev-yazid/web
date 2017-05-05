<?php
namespace App\Http\Controllers\Api;
use App\Libraries\Miscellaneous;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Hash;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\UserProfiles;
use App\User;
use App\Cities;
use App\BrodRequest;
use App\BrodResponse;
use App\EmailTemplates;
use App\Message;
use Auth;
use Mail;
use Form;
use File; 
use Image;
use Session;

class MessageController extends Controller
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

        $this->middleware('jwt.auth', ['except' => ['vcodeExpires','notificationAdmin']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendMessage(Request $request) {
        $messages = LanguageTag::DisplayMessages();
        $validator = Validator::make($request->all(), [                
            'sender_id'       => 'required|numeric',
            'reeciver_id'     => 'required|numeric',
            'req_id'          => 'required|numeric',
            /*'res_id'          => 'required|numeric',   */            
            'description'     => 'required|max:255',
            'usertype'        => 'required',
            'file'            => 'mimes:jpeg,jpg,png,pdf|max:1024',           
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0', $validator->errors()->all(), 0);
        }
        else
        {
            /* for Shop Licence Image Upload */
            $filename = "";
            if($request->hasFile('file'))
            {           
                $file = $request->file('file');
                $path = public_path().'/asset/Messages/';
                $thumbPath = public_path('/asset/Messages/thumb/');

                $timestamp = time().  uniqid(); 
                $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                $file->move($path,$filename);

                $img = Image::make($path.$filename);
                $img->resize(100, 100, function ($constraint) { 
                    $constraint->aspectRatio();
                })->save($thumbPath.'/'.$filename);
            }

            $regNewMobile = new Message;                
            $regNewMobile->sender_id          = trim($request->sender_id);
            $regNewMobile->reeciver_id        = trim($request->reeciver_id);                
            /*$regNewMobile->req_id             = trim($request->req_id);  */              
            $regNewMobile->res_id             = trim($request->res_id);
            $regNewMobile->description        = trim($request->description);                
            $regNewMobile->usertype           = trim($request->usertype);
            $regNewMobile->read_status        = 0;
            $regNewMobile->attachment         = $filename;

            $currentMessage = array(
                    'message'    => $request->description,
                    'attachment' => $filename,
                    'imgpath'    => asset('/public/asset/Message/thumb'),
                );

            if($regNewMobile->save())
            {
                $this->resultapi('1',$messages['Msg1'], $currentMessage);
            }
            else
            {   
                $this->resultapi('0',$messages['Msg2'], $currentMessage);
            }                   
        }        
    }

    public function getAllMessages(Request $request) {
        $messages = LanguageTag::DisplayMessages();
        $validator = Validator::make($request->all(), [
            'uid'             => 'required|numeric',
            'req_id'          => 'required|numeric',
            'usertype'        => 'required',                         
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0', $validator->errors()->all(), 0);
        }
        else
        {
            $allMessages = DB::table('messages')
                ->where('usertype',$request->usertype)
                ->where('req_id',$request->req_id)
                ->Where(function ($query) use ($request){              
                    $query->where('reeciver_id',$request->uid)
                    ->orWhere('sender_id',$request->uid);
                    })
                ->select('id','read_status','attachment','attachment','usertype','created_at')
                ->get();

            foreach ($allMessages as $key => $value)
            {
                //$allMessages[$key]->imgpath = asset('/public/asset/Message/thumb');
                if($value->read_status == 0 )
                {
                    $msgStatus = Message::find($value->id);
                    $msgStatus->read_status = 1;
                    $msgStatus->save();
                }
            }
            $this->resultapi('1',$messages['Msg3'], $allMessages);   
        }
            
    }

    public function notificationAdmin(Request $request) {

        $allBrodReqNotNotified = DB::table('brod_requests')->where('is_notify_to_admin',0)->get();

        if(count($allBrodReqNotNotified) > 0)
        {
            foreach ($allBrodReqNotNotified as $key => $value)
            {   
                $date1Timestamp = strtotime($value->created_at);
                $date2Timestamp = strtotime(date('Y-m-d H:i:s'));

                $difference = $date2Timestamp - $date1Timestamp;
                $days = floor($difference / (60*60*24) );

                if($days > 1)
                {
                    $isNotifyToAdmin = BrodRequest::find($value->id);
                    $checkResponse   = BrodResponse::where('request_id',$value->id)->count();
                    if($checkResponse > 0)
                    {
                        $isNotifyToAdmin->is_notify_to_admin = 2;
                        //$isNotifyToAdmin->save();
                    }
                    else
                    {
                        $reqIdArray[] = $value->id;
                        $isNotifyToAdmin->is_notify_to_admin = 1;
                        //$isNotifyToAdmin->save();
                    }
                }
                else
                {
                    $reqIdArray[] = "";
                }              
            }

            if(count($reqIdArray) > 0)
            {
                $brod_ids = implode(",",$reqIdArray);
                           
                /* List Of ids thos have to get any response by any seller to admin */
                $adminDetails = User::where('usertype','Super Admin')->where('role','Super Admin')->first();
                if(count($brod_ids) > 0)
                { 
                    $subject     =  'Brodcast Request :: No Response Notification';
                    $content     =  "Hello, <br/><br/>Brodcast Ids Having Not Responsed By Any Seller ".$brod_ids;

                    $mail_data = array(
                        'content'   => $content,
                        'toEmail'   => $adminDetails->email,
                        'subject'   => $subject,
                        'fromEmail' => 'admin@feeh.com'
                    );

                    $send = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                        $message->to($mail_data['toEmail']);
                        $message->from($mail_data['fromEmail']);
                        $message->subject($mail_data['subject']);
                    });

                    $this->resultapi('1','Main Send Sucefffully.', $brod_ids);
                }
                else
                {
                    $this->resultapi('1','No Request Found.', $brod_ids);
                }
            }
            else
            {
                $this->resultapi('0','No List Found To Send An Email.', false); 
            }
        }
        else
        {
            $this->resultapi('0','No Need to Notify Admin', false);    
        }
    }

    public function vcodeExpires(Request $request) {

        $users = User::all();
        if(count($users) > 0)
        {
            foreach ($users as $key => $value)
            {   
                $date1Timestamp = strtotime($value->updated_at);
                $date2Timestamp = strtotime(date('Y-m-d'));

                $difference = $date2Timestamp - $date1Timestamp;
                $days = floor($difference / (60*60*24) );

                if($days > 1)
                {
                    $userDetails = User::find($value->id);
                    $userDetails->mobile_verify_code = 0;
                    $userDetails->save();
              
                    $this->resultapi('1','Code Expired Sucessfully.', true);
                }
                else
                {
                    $this->resultapi('0','Not Expired.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','No Users Found.', false);
        }

    }   
    
    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }   
}
