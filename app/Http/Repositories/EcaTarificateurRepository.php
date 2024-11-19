<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\EcaAuthRepository;

class EcaTarificateurRepository extends EcaAuthRepository {

    public function collectDataForTarif($data){
        $result =  [
            "produitChoisi" => $data["produitChoisi"],
            "produitType" => $data["produitType"],
            "courtier" => "CO0075901991",
            "identifiantWs" => "lassurances",
            "codePostal" => $data["codePostal"],
            "ville" => $data["ville"],
            "dateEffet" => $data["dateEffet"],
            "typeResidence" => $data["typeResidence"],
            "typeHabitation" => $data["typeHabitation"],
            "qualiteAssure" => $data["qualiteAssure"],
            "nbrPiecePrincipale" => $data["nbrPiecePrincipale"],
            "nbrPiecePrincipalePlus30m" => $data["nbrPiecePrincipalePlus30m"],
            "nbrDependance" => $data["nbrDependance"],
            "nbrDependancePlus30m" => $data["nbrDependancePlus30m"],
            "resilieAutreAssureur" => $data["resilieAutreAssureur"],
            "sinistres2ansDerniers" => $data["sinistres2ansDerniers"],
            "insertOuCheminee" => $data["insertOuCheminee"],
            "nbEnfantMineur" => $data["nbEnfantMineur"],
            "tcv" => $data["tcv"],
            "nbrEtageImmb" => $data["nbrEtageImmb"],
            "etageBien" => $data["etageBien"],
            "nbPiecesPrincipalesSup50" => $data["nbPiecesPrincipalesSup50"],
            "presenceVeranda" => $data["presenceVeranda"],
            "presencePicineOuTennis" => $data["presencePicineOuTennis"],
            "capitalMobilier" => $data["capitalMobilier"],
            "niveauFranchise" => $data["niveauFranchise"],
            "indemnMobilier" => $data["indemnMobilier"],
            "niveauOJ" => $data["niveauOJ"]
        ];
        return $result;
    }

    public function getTarif($data,$firstTry = true){
    try {
        $token = $this->getAccessToken();
        if (!empty($token)) {
        $result = $this->collectDataForTarif($data);
        $url = $this->baseUrl . '/api/tarificateur';
        $response = Http::withToken($token)->post($url ,$result );
        \Log::info('ECA Tarif DATA :: '.$url.' data :: '. json_encode($result));
        if($response->successful()){
            return response()->json([
                'message' => 'JSON sent successfully!',
                'response' => $response->json()
            ], 200);
        }else{
            if($response->status() == 422 || $response->status() == 400 ){

            \Log::info('ECA Tarif ERROR VALIDATION :: '.json_encode($response->object()));


            return response()->json([
                'message' => 'Failed to send JSON.',
                'error' => $response->body()
            ], $response->status());

            }else if($response->status() == 401){

            if($firstTry){
                // retry with different token
                Session::forget("eca_api_token");
                \Log::info('ECA Tarif retry auth ');
                return $this->getTarif($data,false);
            }

            \Log::info('ECA Tarif ERROR erreur d\'authentification  :: status: '.$response->status().' body : '.json_encode($response->body()));
            return ["erreur" => "erreur d'authentification "];
            }else{
            \Log::info('ECA Tarif ERROR :: status: '.$response->status().' body : '.json_encode($response->body()));
            return response()->json([
                'message' => 'Failed to send JSON.',
                'error' => $response->body()
            ],500);
            }
        }
        }
    } catch (\Exception $e) {
        \Log::info('ECA Tarif ERROR Exception :: '.$e->getMessage());
        return response()->json([ 'message' => 'Failed to send JSON.', 'error' => $e->getMessage() ],500);
    }
    }
}
