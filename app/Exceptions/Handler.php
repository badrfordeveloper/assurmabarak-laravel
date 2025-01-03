<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];



    public function report(Throwable $exception)
    {
        if ( env('APP_ENV') == "local" && $this->shouldReport($exception)) {
            $error = $exception->getMessage().' on line '.$exception->getLine();
            $url = url()->full();
            $file = $exception->getTrace()[0]["file"]??"";
            $myRequest = request()->all();
            $requestMethod = request()->method();
           //$browserName = Browser::browserFamily().' - '.Browser::deviceType().' - '.Browser::platformFamily() ;

            \Log::error('HANDLER_EXCEPTIONS : '.json_encode(["error" =>$error,"url" =>$url,"file" =>$file,"myRequest" =>$myRequest,"requestMethod" =>$requestMethod]));

            $emails =  [
                "mrbadrjeddab@gmail.com", 
                "m.tajmout@eca-assurances.com"
            ];

            \Mail::send('mails.error_mail', compact('error','url','file','myRequest','requestMethod'), function ($message) use ($emails) {
                $message->from('exception@assurmabarak.com')->to($emails)->subject('Exception détecté : ASSURMABARAK');
            });
        }
        parent::report($exception);
    }


    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
