<?php

namespace App\api;

use Illuminate\Database\Eloquent\Model;
use File;

class Transaction extends Model
{	
	public static function UpdateTransactionDetails($cid, $fid, $pid, $transStatus)
	{
	   $trsStatus = 0;
		
		$TransDetails = new Transaction;
        $TransDetails->cid           = $cid;
        $TransDetails->fid           = $fid;
        $TransDetails->pid           = $pid;
        $TransDetails->pay_status    = $transStatus;
        $TransDetails->ip            = $_SERVER['REMOTE_ADDR'];                             
      
        if($TransDetails->save())
        {
            $trsStatus = 0;
        }

   		return $trsStatus;		
	}
}
