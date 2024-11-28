<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function test(Request $request)
    {
        try {
            $test = [];
            $dd =$test['fdd'];
        } catch (\Exception $exception) {
            if ( env('APP_ENV') == "local" ) {
                $error = $exception->getMessage().' on line '.$exception->getLine();
                $url = url()->full();
                $file = $exception->getTrace()[0]["file"]??"";
                $myRequest = request()->all();
                $requestMethod = request()->method();
               //$browserName = Browser::browserFamily().' - '.Browser::deviceType().' - '.Browser::platformFamily() ;
    
                \Log::error('HANDLER_EXCEPTIONS : '.json_encode(["error" =>$error,"url" =>$url,"file" =>$file,"myRequest" =>$myRequest,"requestMethod" =>$requestMethod]));
    
                $emails = array("mrbadrjeddab@gmail.com");
    
                \Mail::send('mails.error_mail', compact('error','url','file','myRequest','requestMethod'), function ($message) use ($emails) {
                    $message->from('exception@assurmabarak.com')->to($emails)->subject('Exception détecté : ASSURMABARAK');
                });
            }
        }
    
    }
}
