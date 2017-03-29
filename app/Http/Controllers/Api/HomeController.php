<?php
namespace App\Http\Controllers\Api;
use App\Libraries\Miscellaneous;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\UserProfiles;
use App\Newsletter; 
use App\Api\JobDetail;
use App\Skills;
use App\States;
use App\Cities;
use App\EmailTemplates; 
use DB;
use Input;
use Validator;
use Hash;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;


class HomeController extends Controller
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
   
    public function getTopRatedJobs(){ 
        $topRatedJobsQuery = DB::table('job_details')            
        ->select('id','job_title','job_images','job_cost','job_category')     
        ->where('job_stage','!=','Finished')
        ->where('status','Active')        
        ->orderBy('job_cost','desc')
        ->get();

        $jobCatIds = array();
        $rowCount = count($topRatedJobsQuery);
        if($rowCount > 7)
        {
            $jobCategories = DB::table('categories')->select('id')->where('status','Active')->get();
            foreach ($jobCategories as $jobCategory) {
                
                $jobCatId = $jobCategory->id;
                foreach ($topRatedJobsQuery as $topRatedCats) {
                    
                    $jobId = $topRatedCats->id;
                    $jobCategory = $topRatedCats->job_category;
                    $jobCategoryArray = explode(",",$jobCategory);

                    if (in_array($jobCatId, $jobCategoryArray))
                    {
                        $jobCatIds[] = $jobId;
                    }
                }                
            }

            $uniqueProdIdsCount  = count($jobCatIds);
            $randomProdsIds = array_flip(array_unique($jobCatIds));           
            $randomProds = DB::table('job_details')
            ->whereIn('id',$randomProdsIds)
            ->select('id','job_title','job_images','job_cost','job_category')     
            ->Where('job_stage','!=','Finished')
            ->orWhere('status','Active')
            ->get();

            $highPrice = array();
            $division = floor($rowCount/2);
            $midPrice = array();
            $lowPrice = array();
            
            foreach ($topRatedJobsQuery as $key => $value)
            {                 
                if($key  == 0 || $key  == 1 || $key  == 2)
                {
                    $highPrice[] = $value;
                }

                if($key == $division-1 || $key == $division || $key == $division+1)
                {
                    $midPrice[] = $value;
                }  

                if($key == $rowCount-1 || $key == $rowCount-2 || $key == $rowCount-3)
                {
                    $lowPrice[] = $value;
                }                    
            }

            $topRatedJobs['HighPrice'] =  $highPrice;
            $topRatedJobs['LowPrice']  =  $lowPrice;           
            $topRatedJobs['MidPrice']  =  $midPrice;
            $topRatedJobs['CatWise1']  =  array_slice($randomProds, 0, 3);
            $topRatedJobs['CatWise2']  =  array_slice($randomProds, 3, 3);
            $topRatedJobs['CatWise3']  =  array_slice($randomProds, 6, 3);
            $topRatedJobs['CatWise4']  =  array_slice($randomProds, 9, 3);

        }
        else
        {
            $topRatedJobs = array();
        }

        if(count($topRatedJobs) > 0)
        {
            $this->resultapi('1','Projects Found.', $topRatedJobs);
        }
        else
        {                
            $this->resultapi('0','No Projects Found.', $topRatedJobs);
        }
    }

    public function getNewsletterSubscribe(Request $request){
       
        if($request->email && count($request->all()) > 0)
        {  
            $validator = Validator::make($request->all(), [

                'email'  =>'required|email|unique:newsletters',
            ]);
             
            if ($validator->fails()) 
            {
                $this->resultapi('0',$validator->errors()->all(), null);
            }
            else
            {
                $newsletter = new Newsletter;
                $newsletter->email   = trim($request->email);
                $newsletter->status  = 1;     
                
                if($newsletter->save())
                {
                    
                    $mailData = array(
                        'email'         => $request->email,
                        'template'          => 'newsletter-subscribe-done',
                        'search'            => "a",
                        'replace'           => "a"        
                    );

                    $sendStatus = EmailTemplates::SubsNewsletter($mailData);
                }

                $this->resultapi('1','Newsletter Subscription Done.', 'true');
            }
        }
        else
        {
            $this->resultapi('0','Problem in Newsletter Subscription.', 'false');
        }
    }

    
    public function getSearchResult(Request $request){ 
        
      
        if($request->all() && !empty($request->all())) 
        {                
            if($request->searchtype == 'project')
            {
                $this->getProjectSearchResult($request);
            }
            else if($request->searchtype == 'freelancer')
            {
                $this->getFreelancerSearchResult($request);
            }
            else
            {
                $results = array();
                $this->resultapi('0','No Record Found..', $results);
            }          
        }
        else
        {
            $results = array();
            $this->resultapi('0','No Record Found..', $results);
        }
    }

    /* Search job from freelancer*/
    public function getProjectSearchResult($request){

        if($request->all() && !empty($request->all())) 
        {       

            $searchtext   = "";
            $skill        = "";
            $location     = "";
                          
       
            $results = jobdetail::where(function($q) use ($request) {
                
                $searchtext   = $request->searchtext;
                $skill        = $request->skill;                
                $location     = $request->location;  

                //$userdata     = $this->jwtAuth->parseToken()->authenticate();            
                 
                //echo $userdata->id;

                $q->Where('status', 'Active');
                $q->Where('job_stage', '!=', 'Finished');
                $q->Where('hired_user_id',0);

                /*if($userdata->id){
                     $q->Where('user_id', '!=', $userdata->id);                    
                }*/
                if($searchtext!=""){
                    $q->Where('job_title', 'LIKE', '%'.$searchtext.'%');
                    $q->orWhere('job_subtitle', 'LIKE', '%'.$searchtext.'%');
                }              
                if($skill!=""){
                    $q->whereRaw('FIND_IN_SET('.$skill.',job_skills)');
                }
                if($location!=""){
                    $q->Where('job_location', '=', $location);
                }        
            })
            ->select('id','job_location','job_title','job_subtitle','job_cost','job_images','job_skills','job_description','job_availble_for')
            ->orderBy("created_at",'desc')          
            ->get();
       
            foreach ($results as $key => $result) {
                
                $skillsDecodeJson = explode(",", $result->job_skills);
                //$results[$key]->job_images = json_decode($result->job_images, TRUE);
      
                if($skillsDecodeJson && count($skillsDecodeJson) > 0)
                {
                    $results[$key]->skill_list = Skills::whereIn('id',$skillsDecodeJson)
                    ->select('skill')
                    ->where('status', 'Active')
                    ->get();
                }

                $location = DB::table('cities')->where('id',$result->job_location)->select('name')->first();
                $results[$key]->job_location = $location->name;               
            }                    

           $msg = "Project Search Result";
           $this->resultapi('project',$msg, $results);        
        }
        else
        {   
            $results = array();
            $this->resultapi('0','No Record Found.', $results);
        }
             
    }

    /* Search Freelancer from client side */
    public function getFreelancerSearchResult($request){        
     
        if($request->all() && !empty($request->all())) 
        {       
            $searchtext   = "";
            $skill        = "";
            $location     = "";
          
            $results =  DB::table('users')
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->select('user_profiles.*','users.*','user_profiles.id as up_id')
                ->where(function($q) use ($request) {        

                $searchtext   = $request->searchtext;
                $skill        = $request->skill;                
                $location     = $request->location;

                $q->Where('users.status', 'Active');
                $q->Where('users.is_profile_updated',1);
                $q->Where('users.is_payment_updated',1);
                $q->Where('users.email_verified','Yes');

                if($searchtext!=""){
                    $q->Where('job_title', 'LIKE', '%'.$searchtext.'%');                                 
                }              
                if($skill && $skill !=""){
                    $q->whereRaw('FIND_IN_SET('.$skill.',skills)');
                }
                if($location!=""){
                    $q->Where('locationId', '=', $location);
                }
                $q->Where('users.usertype','=','Freelancer');
                //$q->orWhere('users.usertype','=','Company');                
               
                
            })
            ->select('user_id','locationId','job_title','usertype','is_company','profile_image','profile_description','skills','hourly_rate','firstname','lastname')           
            ->get();
            
            foreach ($results as $key => $result) {                
               
                $skillsDecodeJson = explode(",", $result->skills);              
      
                if($skillsDecodeJson && count($skillsDecodeJson) > 0)
                {
                     $results[$key]->skill_list = Skills::whereIn('id',$skillsDecodeJson)
                    ->select('skill')
                    ->where('status', 'Active')
                    ->get();
                }

                $location = DB::table('cities')->where('id',$result->locationId)->select('name')->first();
                $results[$key]->user_location = $location->name;
            }

           $msg ="Freelancer Search Result";
           $this->resultapi('freelancer',$msg, $results);        
        }
        else
        {   
            $results = array();
            $this->resultapi('0','No Record Found.', $results);
        }
             
    }

    public function getAllCaseStudy(){ 
    
        $caseStudy = DB::table('case_study')
        ->select('id','title','image','content','url')  
        ->where('status','1')
        ->orderBy('id', 'desc')
        ->take(1)
        ->get();

        if(count($caseStudy) > 0)
        {
            $this->resultapi('1','Case Study Found.', $caseStudy);
        }
        else
        {                
            $this->resultapi('0','No Case Study Found.', $caseStudy);
        }
    }

    public function getAllCommunity(){ 
    
        $allCommunity = DB::table('community')            
        ->select('id','title','image','content','url')     
        ->orderBy('id', 'desc')
        ->where('status','1')
        ->take(4)
        ->get();

        if(count($allCommunity) > 0)
        {
            $this->resultapi('1','Community Found.', $allCommunity);
        }
        else
        {                
            $this->resultapi('0','No Community Found.', $allCommunity);
        }
    }
    
    public function getAllCityData() {

        $allcities = DB::table('cities')
                ->select('id', 'name')
                ->get();
      
        if (count($allcities) > 0) {
            $this->resultapi('1', 'Cities Found.', $allcities);
        } else {
            $this->resultapi('0', 'No City Found.', $allcities);
        }
    }


    public function resultapi($status,$message,$result = array()) {
        $finalArray['STATUS'] = $status;
        $finalArray['MESSAGE'] = $message;
        $finalArray['DATA'] = $result;
        echo json_encode($finalArray);

        exit;
    }
}