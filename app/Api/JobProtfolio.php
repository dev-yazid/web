<?php

namespace App\api;

use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Support\Facades\DB;
class JobProtfolio extends Model
{
	public static function getUserProtfolio($usertype, $uid)
	{
		if($usertype == 'Freelancer')
        {
            $searchField = 'fid';
            $usertype    = "Client";
        }
        else
        {
            $searchField = 'cid';
            $usertype    = "Freelancer";
        }

        $jobProtfolio = DB::table('job_protfolios')
		->where($searchField,$uid)
        ->where('given_by',$usertype)
		->join('job_details', 'job_protfolios.pid', '=', 'job_details.id')
		->select('job_protfolios.rating','job_protfolios.rating_desc','job_details.job_title','job_details.job_subtitle','job_details.job_cost','job_details.job_stattime','job_details.job_endtime')
		->orderBy('job_details.id','desc')
		->get();
		
		return $jobProtfolio;		
	}

	public static function addPortfolio($usertype,$pid,$fid,$cid,$rating,$ratingDetails)
	{
        //$check = jobProtfolio::where("fid",$fid)->where('pid',$pid)->where('cid',$cid)->count();
       
        $jobProtfolio = new jobProtfolio; 
        $jobProtfolio->pid            = $pid;
        $jobProtfolio->fid            = $fid;
        $jobProtfolio->cid            = $cid;
        $jobProtfolio->rating         = $rating;
        $jobProtfolio->rating_desc    = $ratingDetails;
        $jobProtfolio->given_by       = $usertype;
        $jobProtfolio->save();
        
        return true;
    }

    public static function portfolioStatus($request,$usertype)
	{
	    if($usertype == 'freelancer')
		{
			$jobProtfolio = DB::table('job_protfolios')         
            ->where('fid',$request->uid)
            ->where('pid',$request->pid)
            ->first();
        }
        else if($usertype == 'client')
        {
        	$jobProtfolio = DB::table('job_protfolios')         
            ->where('cid',$request->cid)
            ->where('pid',$request->pid)
            ->first();
        }
        else
        {
            $jobProtfolio = "";
        }
		
		return $jobProtfolio;		
	}

    public static function portfolioByProjectId($projId)
    {
        if($projId)
        {
            $jobProtfolio = DB::table('job_protfolios')
            ->where('pid',$projId)
            ->select('rating','given_by','rating_desc')
            ->get();
        }        
        else
        {
            $jobProtfolio = "";
        }
        
        return $jobProtfolio;       
    }
}
