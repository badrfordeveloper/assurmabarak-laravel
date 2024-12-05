<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\EcaAuthRepository;

class EcaNotEligibleRepository extends EcaAuthRepository {

    public function collectDataForMAil($data){
        $result =  [
            "civilite" => $data["civilite"],
            "nom" => $data["nom"],
            "prenom" => $data["prenom"],
            "email" => $data["email"],
            "tel" => $data["telephone"],
            "dateNaissance" => $data["dateNaissance"],
            "codePostal" => $data["codePostal"],
            "ville" => $data["ville"],
            "dateEffet" => $data["dateEffet"],
            "resilieAutreAssureur" => $data["resilieAutreAssureur"],
        ];
        return $result;
    }

    public function sendNotificationSubscription($data,$firstTry = true){
        try {
            $data = $this->collectDataForMAil($data);

            $emails = array("b.jeddab@eca-assurances.com");
            \Log::info('ECA notification data :: '.json_encode($data));

            $subject = ($data['resilieAutreAssureur'] == "OUI" ) ? 'AssureMaBarak - Assuré non éligible ECA' : 'AssureMaBarak - Notification' ;

            \Mail::send('mails.notification', compact('data'), function ($message) use ($emails,$subject) {
                $message->from('notification@assurmabarak.com')
                        ->to($emails)
                        ->subject($subject);
            });
            return response()->json([
                'message' => 'Mail sent successfully!',
            ], 200);

        } catch (\Throwable $e) {
            \Log::info('ECA notification ERROR Exception :: '.$e->getMessage());
            return response()->json([ 'message' => 'Failed to send MAIL.', 'error' => $e->getMessage() ],500);
        }
    }
}
