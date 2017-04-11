<?php
namespace App\api;
use App\Api\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Payment extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'cc_no','cc_name', 'cc_cvv',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ ];

    public static function savePayment($request)
    {
        $payStatus = 0;

        $userDetails = User::find($request->user_id);
        if(count($userDetails) > 0)
        {
            if($request->userType === "Freelancer" || $request->userType === "Client" )
            {
                if($request->userType === "Freelancer")
                {
                    $userDetails->is_payment_updated = 1;
                }
                else
                {
                   $userDetails->is_client_payment_updated = 1; 
                }
            }

            $userDetails->save();
            
            $PaymentDetail = new Payment; 
            $PaymentDetail->user_id         = $request->user_id;
            $PaymentDetail->cc_no           = $request->cc_number;
            $PaymentDetail->cc_name         = $request->cc_name;
            $PaymentDetail->cc_cvv          = $request->cc_cvv;
            $PaymentDetail->cc_type         = $request->cc_type;
            $PaymentDetail->user_type       = $request->userType;
        }
                                    
        if($PaymentDetail->save())
        {
            $payStatus = 1;
        }

        return $payStatus;      
    }
}
