<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public static function getAllBrands()
	{
		$allBrands =  DB::table('brands')
		->where('status',1)
		->select('id','brand','image')
		->get();

		return $allBrands;
	} 
}
