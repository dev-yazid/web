<?php

namespace App\api;

use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Support\Facades\DB;
class JobQuestion extends Model
{
	public static function getAllQuesAns($pId)
	{
		$JobQuesAns = DB::table('job_questions')
		->where('pid',$pId)
		->where('status',1)
		->where('parent_ques_id',0)
		->orderBy("id",'Desc')
		->get();

		if(count($JobQuesAns) > 0)
		{
			foreach ($JobQuesAns as $key => $value)
			{
				$users = User::find($value->fid);
				$JobQuesAns[$key]->userName = $users->firstname." ".$users->lastname;

				$users = User::find($value->cid);
				$JobQuesAns[$key]->clientName = $users->firstname." ".$users->lastname;
			
				$subQuestions = DB::table('job_questions')				
				->where('parent_ques_id',$value->id)
				->where('status',1)
				->orderBy("id",'Desc')
				->get();

				$JobQuesAns[$key]->replyOnAnswer = $subQuestions;
			}
		}

		return $JobQuesAns;
	}

	public static function getQuestion($QuesId)
	{
		$JobQues = JobQuestion::find($QuesId)->first();
		
		return $JobQues;
	}

	public static function submitQuestion($request)
	{
		$saveStatus = 0;

		$JobDetails = DB::table('job_details')
		->where('id',$request->ProjId)
		->first();	

		if($JobDetails->job_stage !== "Finished")
		{
			$JobQuestion = new JobQuestion;
			$JobQuestion->cid   		= $request->cId;
			$JobQuestion->fid   		= $request->fId;
			$JobQuestion->pid   		= $request->ProjId;
			$JobQuestion->question  	= $request->ques;
			$JobQuestion->answers  		= "";
			$JobQuestion->read_status  	= 0;
			$JobQuestion->status  		= 1;
			if($JobQuestion->save())
			{
				$saveStatus = 1;
			}
		}
		else
		{
			$saveStatus = 2;
		}
		
		return $saveStatus;
	}

	public static function submitAnswer($quesId, $answer)
	{
		$JobAnswer = 0;

		$JobAnswer = JobQuestion::find($quesId);

		if(count($JobAnswer) > 0)
		{
			$JobAnswer->answers  		= $answer;
			$JobAnswer->read_status  	= 1;
			$JobAnswer->status  		= 1;
			if($JobAnswer->save())
			{
				$JobAnswer = 1;
			}
		}
		
		return $JobAnswer;
	}

	public static function replyOnAnswer($quesId, $answer)
	{
		$JobAnswer = 0;
		$JobAnswer = JobQuestion::find($quesId);

		$JobReply  = new JobQuestion;
		if(count($JobAnswer) > 0)
		{	
			$JobReply->parent_ques_id  	= $quesId;
			$JobReply->cid  			= $JobAnswer->cid;
			$JobReply->pid  			= $JobAnswer->pid;
			$JobReply->fid  			= $JobAnswer->fid;
			$JobReply->question  		= $answer;
			$JobReply->read_status  	= 0;
			$JobReply->status  			= 1;
			if($JobReply->save())
			{
				$JobAnswer = 1;
			}
		}
		
		return $JobAnswer;
	}

	public static function deleteQuestion($quesId)
	{
		$delStatus = 0;

		$deleteQuestion = JobQuestion::find($quesId);
		if(count($deleteQuestion) > 0)
		{
			if($deleteQuestion->delete())
			{
				$delStatus = 1;
			}
		}
		
		return $delStatus;
	}
}
