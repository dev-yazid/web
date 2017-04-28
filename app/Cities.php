<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cities extends Model
{
	public static function getAllCities()
	{
		$allCities =  DB::table('cities')
		->where('status',1)
		->select('id','name')
		->get();

		return $allCities;
	}
}
 