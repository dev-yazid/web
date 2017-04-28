<?php
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Mail;

Class EmailTemplates extends Model
{
    protected $table='email_templates'; 

    public static function SendEmail($mailData) {

      /* $user = User::where('id',$mailData['userId'])
        ->select('firstname','lastname','email')
        ->first();*/

        
            $sent = false;
            $params = array(               
                'from'        => "info@jobbookers.com",
                'to'          => $mailData['email'],  
                'template'    => $mailData['template'],
                'search'      => $mailData['search'],
                'replace'     => $mailData['replace']        
            );

            $email_template = EmailTemplates::where('slug', '=', $params["template"])->first();
            $params["subject"] = $email_template["subject"];
            $message = str_replace($params["search"], $params["replace"], $email_template["content"]);           

            $mail_data = array(
                'content' => $message,
                'toEmail'=>$params["to"],
                'subject'=>$email_template["subject"],
                'fromEmail'=>$params["from"]
            );

            $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                $message->to($mail_data['toEmail']);
                $message->from($mail_data['fromEmail']);
                $message->subject($mail_data['subject']);
            });
      


        if($sent == true)
        {
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
            return false;
        }
    }

    public static function SubsNewsletter($mailData) {

        $sent = false;
        $params = array(               
            'from'        => "info@jobbookers.com",
            'to'          => $mailData['email'],  
            'template'    => $mailData['template'],
            'search'      => $mailData['search'],
            'replace'     => $mailData['replace']        
        );

        $email_template = EmailTemplates::where('slug', '=', $params["template"])->first();
        $params["subject"] = $email_template["subject"];
        $message = str_replace($params["search"], $params["replace"], $email_template["content"]);           

        $mail_data = array(
            'content' => $message,
            'toEmail'=>$params["to"],
            'subject'=>$email_template["subject"],
            'fromEmail'=>$params["from"]
        );

        $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
            $message->to($mail_data['toEmail']);
            $message->from($mail_data['fromEmail']);
            $message->subject($mail_data['subject']);
        });

        if($sent == true)
        {
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
            return false;
        }
    }

    public static function RegisterUser($to,$template,$search,$replace) {
        
        $sendStatus = false;

        $params = array(               
            'from'        => "info@jobbookers.com",
            'to'          => $to,  
            'template'    => $template,
            'search'      => $search,
            'replace'     => $replace        
        );

        $email_template = EmailTemplates::where('slug', '=', $params["template"])->first();
        $params["subject"] = $email_template["subject"];
        $message = str_replace($params["search"], $params["replace"], $email_template["content"]);           

        $mail_data = array(
            'content' => $message,
            'toEmail'=>$params["to"],
            'subject'=>$email_template["subject"],
            'fromEmail'=>$params["from"]
        );

        $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
            $message->to($mail_data['toEmail']);
            $message->from($mail_data['fromEmail']);
            $message->subject($mail_data['subject']);
        });

        if($sent)
        {
            return  $sendStatus = true;
        }
        else
        {
            show_error($this->email->print_debugger());
            return  $sendStatus = true;
        }
    }   
}
?>
