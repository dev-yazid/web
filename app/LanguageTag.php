<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageTag extends Model
{
	public static function DisplayMessages()
    {  
        $type = 1;

        if($type == 1)
        {
	        $messages = array(
	        	"Brod1" => "Product Found.",
	        	"Brod2" => "No Product Found.",
	        	"Brod3" => "Brand Id Not Found.",
	        	"Brod4" => "Product Request Added Sucessfully.",
	        	"Brod5" => "Product Request Updated Sucessfully.",
	        	"Brod6" => "You Have already Confirmed This Product Request.",
	        	"Msg1"  => "Message Send Sucessfully.",
	        	"Msg3"  => "Some Problem with Send Email.",
	        	"Msg3"  => "Message Send Sucessfully.",
		  	);
	    }

	    if($type == 2)
        {
	        $messages = array(
	        	"Brod1" => "Product Found.",
	        	"Brod2" => "No Product Found.",
	        	"Brod3" => "Brand Id Not Found.",
	        	"Brod4" => "Product Request Added Sucessfully.",
	        	"Brod5" => "Product Request Updated Sucessfully.",
	        	"Brod6" => "You Have already Confirmed This Product Request.",
	        	"Msg1"  => "Message Send Sucessfully.",
	        	"Msg3"  => "Some Problem with Send Email.",
	        	"Msg3"  => "Message Send Sucessfully.",
	        );
	    }

        return $messages;
    }      
}
