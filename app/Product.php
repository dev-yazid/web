<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    public static function getProductsByBrandId($bid)
	{
		//echo $bid;
		$productsByBrandId =  DB::table('products')
		->where('brand',$bid)
		//->where('status',1)
		->select('id','pname','year','brand')
		->get();
		
		return $productsByBrandId;
	}
}
