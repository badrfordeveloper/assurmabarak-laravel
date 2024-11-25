<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\EcaAuthRepository;

class EcaNotEligibleRepository extends EcaAuthRepository {

    public function collectDataForMAil($data){

        $result =  [
            "nom" => $data["souscripteurNom"],
            "prenom" => $data["souscripteurPrenom"],
            "email" => $data["souscripteurEmail"],
            "tel" => $data["souscripteurTel"],
            "dateNaissance" => $data["dateNaissance"],
            "codePostal" => $data["souscripteurCodePostal"],
            "ville" => $data["souscripteurVille"],
            "adressePostale" => $data["souscripteurAdressePostale"],
        ];
        return $result;
    }

    public function notEligible($data,$firstTry = true){
        try {
            $data = $this->collectDataForMAil($data);

            $emails = array("b.jeddab@eca-assurances.com");
            \Log::info('ECA not Eligible ERROR Exception :: ');

            \Mail::send('mails.notEligible', compact('data'), function ($message) use ($emails) {
                $message->from('notEligible@assurmabarak.com')
                        ->to($emails)
                        ->subject('AssureMaBarak - Assuré non éligible ECA');
            });
            return response()->json([
                'message' => 'Mail sent successfully!',
            ], 200);

        } catch (\Throwable $e) {
            \Log::info('ECA not Eligible ERROR Exception :: '.$e->getMessage());
            return response()->json([ 'message' => 'Failed to send MAIL.', 'error' => $e->getMessage() ],500);
        }
    }
}
