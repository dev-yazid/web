<?php
namespace App\Http\Controllers\Api;
use App\Libraries\Miscellaneous;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Api\User;
use App\Api\JobProtfolio;
use DB;
use App\Skills;
use Input;
use Validator;
use Hash;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Session;

class GlobalController extends Controller
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

    public function getLanguageData(){ 
       
        $labals = DB::table('language_tag')
            ->orderBy('created_at')
            ->where('lang_code', 'de')
            ->pluck('labal_de', 'labal');
       
        $labalsInSession = Session()->put('labals_data', $labals);
        $labalsFromSession = Session()->get('labals_data',$labals);
        
        return $labalsFromSession;
    }

   
    public function getAllSkills(){

        $skills = DB::table('skills')
            ->select('id','skill')
            ->orderBy('skill')         
            ->where('status', 'Active')
            ->get();

        return $this->resultapi('1','Fetched successfully.',$skills);

    }

    public function getAutocompleteSkills(Request $request){

        $skills = DB::table('skills')
            ->select('id','skill')
            ->Where('skill', 'LIKE', '%'.$request->q.'%')
            ->orderBy('skill')            
            ->where('status', 'Active')
            ->get();

        return $this->resultapi('1','Fetched successfully.',$skills);

    }

    

    public function getQualifications(){
    
       $qualifications = DB::table('qualifications')
            ->select('id','name')            
            ->where('status', '1')
            ->orderBy('orderno', 'asc')
            ->get();

        return $this->resultapi('1','Fetched successfully.',$qualifications);

    }

    public function getAllCategories(){
    
       $categories = DB::table('categories')
            ->select('id','name')            
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return $this->resultapi('1','Fetched successfully.',$categories);

    }

    public function getPageDetails(Request $request){
        $pageId = $request->id;
        $pageDetails = DB::table('cms')
            ->select('title','description')
             ->where('id', $pageId)           
            ->first();

        return $this->resultapi('1','Fetched successfully.',$pageDetails);

    }

    public function getAllBlogs(){        
        $allBlogs = DB::table('blogs')
            ->where('status', '1')
            ->orderBy('id')        
            ->get();

        return $this->resultapi('1','Fetched successfully.',$allBlogs);

    }


    public function getUserDetailsById(Request $request){   
        //DB::enableQueryLog();   pr(DB::getQueryLog());exit;
        if($request->uid && $request->usertype)
        {
            $userDetails = DB::table('users')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->leftJoin('qualifications', 'user_profiles.qualifications', '=', 'qualifications.id')
            ->leftJoin('states', 'states.id', '=', 'user_profiles.stateId')             
            ->join('cities', 'user_profiles.locationId', '=', 'cities.id')
            ->select('users.id','users.firstname','users.lastname','users.id','users.profile_image','users.usertype','users.is_company','users.status','users.created_at','user_profiles.job_title','user_profiles.portfolio_images','user_profiles.user_id','user_profiles.zipcode','user_profiles.birth_date', 'user_profiles.gender','user_profiles.profile_description','user_profiles.videos','user_profiles.hourly_rate','user_profiles.skills','qualifications.name','qualifications.name as qualificationsType','user_profiles.vat_number','user_profiles.commercial_register_number','invoice_address','user_profiles.invoice_address','user_profiles.delivery_address','cities.name as cityName','states.name as stateName','user_profiles.company_address','user_profiles.company_type', 'user_profiles.vat_number', 'user_profiles.commercial_register_number','user_profiles.company_no_of_employer','user_profiles.website','user_profiles.delivery_zipcode','user_profiles.invoice_zipcode') 

            ->where('users.id',$request->uid)
            ->first();
          
            if(count($userDetails) > 0)
            {
                $userDetails->portfolio = JobProtfolio::getUserProtfolio($request->usertype, $request->uid);
                                  
                $skillsDecodeJson = explode(",",  $userDetails->skills);

                if($skillsDecodeJson && count($skillsDecodeJson) > 0)
                {
                    $userDetails->skill_list = Skills::whereIn('id',$skillsDecodeJson)
                    ->select('skill')
                    ->where('status', 'Active')
                    ->get();
                }

                $this->resultapi('1','Details Found.', $userDetails);
            }
            else
            {
                $this->resultapi('0','Please Update Your Profile Details.', $userDetails);
            }            
        }
    }

    public function checkUserType(Request $request){
      
        if($request->uid && $request->uid !="" && $request->uid !="undefiend")
        {
            $usertype = DB::table('users')
            ->select('usertype')
            ->where('users.id',$request->uid)
            ->first();

            $this->resultapi('1','User Type.', $usertype);
        }
        else
        {
            $usertype = "";
            $this->resultapi('1','No User Found.', $usertype);
        }      
    
    }

    public function changeProfileView(Request $request){
        
        if($request->uid)
        {
            $userId = $request->uid;
            $profileType = $request->changeProfileType;
            if($profileType)
            {
                $user = User::find($userId);                
                $user->usertype  = $profileType;
                $user->update();

                $checkProfileUpdated = array();
                $checkProfileUpdated['freelancer'] = $user->is_profile_updated;        
                $checkProfileUpdated['client']     = $user->is_client_profile_updated;
            }           
            else  /* used in post*/
            {
                $profileType = "";
                $user = User::find($userId);            
                $checkProfileUpdated = array();
                $checkProfileUpdated['freelancer']  = $user->is_profile_updated;
                $checkProfileUpdated['fpayment']    = $user->is_payment_updated;        
                $checkProfileUpdated['client']      = $user->is_client_profile_updated;
                $checkProfileUpdated['cpayment']    = $user->is_client_payment_updated;
            }
            

            $this->resultapi('1',$checkProfileUpdated, $profileType);
        }
        else
        {            
            $profileType = "";
            $this->resultapi('1','Some Problem in change profile.', $profileType);
        }    
    
    }    

    public function resultapi($status, $message, $result = array()) {
        
        $finalArray['STATUS'] = $status;
        $finalArray['MESSAGE'] = $message;
        $finalArray['DATA'] = $result;
        
        echo json_encode($finalArray);
        die;
    }
       

}

