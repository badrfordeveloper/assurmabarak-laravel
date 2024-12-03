<?php
namespace App\Http\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\EcaAuthRepository;

class EcaSaveRepository extends EcaAuthRepository {

    public function collectDataForSaveContrat($data){
      \Log::info('ECA SAVE DATA ccc : '. json_encode($data));
      $result =  [
            "flag" => "DEVIS_COMPLET",
            "flagType" =>  $data['flagType'],
            "courtier" => "CO0075901991",
            "identifiantWs" => "lassurances",
            "produits" => [
               "types" => "MRH",
               "MRH" => [
                  [
                    "bienComportePiscineTennis" => $data['piscine'],
                    "possedePiscines" => $data['piscine'],
                     "habitationUsageProfessionel" => $data['habitationUsageProfessionel'] ? $data['habitationUsageProfessionel'] : "NON",
                     "habitationAvecAccessoire" => "NON",
                     "jardinBiensExterieur" => $data['piscine'],
                     "piscine" => $data['piscine'] ? $data['piscine'] : "NON",
                     "rcPiscineTennis" => $data['piscine'] ? $data['piscine'] : "NON",
                     "surfaceDepSuperieur100" => "NON",
                     "dateEffet" => Carbon::parse($data['dateEffet'])->format("d/m/Y"),
                     "typeHabitation" => $data['typeHabitation'],
                     "typeResidence" => $data['typeResidence'],
                     "qualiteAssure" => $data['qualiteAssure'],
                     "codePostal" => $data['codePostal'],
                     "ville" => $data['ville'],
                     "nbrPiecePrincipale" => $data['nbrPiecePrincipale'],
                     "nbrPiecePrincipalePlus30m" => $data['nbrPiecePrincipalePlus30m'] ? $data['nbrPiecePrincipalePlus30m'] : 0,
                     "nbrDependance" => $data['nbrDependance'] ? $data['nbrDependance'] : 0,
                     "nbrDependancePlus30m" => $data['nbrDependancePlus30m'] ? $data['nbrDependancePlus30m'] : 0,
                     "resilieAutreAssureur" => $data['resilieAutreAssureur'],
                     "sinistres2ansDerniers" => $data['sinistres2ansDerniers'],
                     "habitationDejaAssure" => $data['habitationDejaAssure'],
                     "bienComporteViranda" => $data['presenceVeranda'],
                     "batimentCouvertMatDurs" => "OUI",
                     "moyenProtectionVols" => $data['moyenProtectionVols'],
                     "capitalMobilier" => $data['capitalMobilier'],
                     "valeurEstime" => $data['capitalMobilier'],
                     "franchise" => $data['franchise'],
                     "indemnisationMobilier" =>  $data['indemnisationMobilier'],
                     "dontObjetsValeur" =>  $data['dontObjetsValeur'],
                     "nbrEtage" =>  $data['nbrEtage'],
                     "complementAdresse" => $data['souscripteurAdressePostale'],
                     "periodicite" => "MENSUELLE",

                     "assureObjetValeur" => "NON",
                     "niveauGarantieObjVal" => "15",


                     "declarAssistantMatern" => $data['declarAssistantMatern'] ?? 'NON',
                     "panneauPhotoVolt" => $data['panneauPhotoVolt'] ?? 'NON',
                     "bienElectrMoin5ans" => $data['bienElectrMoin5ans'] ?? 'NON',
                     "possedeJardins" => $data['piscine'] ? $data['piscine'] : "NON",
                     "dommageElectrique" => $data['bienElectrMoin5ans'],
                     "rachatFranchise" => $data['rachatFranchise'],
                     "systemePhotoVoltaique" => $data['panneauPhotoVolt'],
                     "individuAccidtMineur" => 'NON',
                     "assistanceMaternelle" => $data['declarAssistantMatern'],
                     "briseGlaceVeranda" => $data['presenceVeranda'],
                     "locationDeSalle" => $data['locationSalle'],
                     "repriseHamon" => "NON",
                     "modePaiement" => $data['modePaiement'],
                     "modePaiementCotisationSuivante" => "PRELEVEMENT",
                     "formuleRecommandee" => $data['formuleChoisi'],
                     "formuleChoisi" => $data['formuleChoisi'],
                     "fraisDossier" => 0,
                     "assureur" => []
                  ]
               ]
            ],
            "souscripteur" => [
               "voie" => $data['souscripteurAdressePostale'],
               "ville" => $data['souscripteurVille'],
               "codePostal" => $data['souscripteurCodePostal'],
               "nom" =>$data['souscripteurNom'],
               "cv" => $data['souscripteurCV'],
               "revenuMensuel" => "DE_1501_A_3000",
               "typeSouscripteur" => "PERSONNE_PHYSIQUE",
               "tel" => str_replace(' ','', $data['souscripteurTel']),
               "prenom" => $data['souscripteurPrenom'],
               "email" => $data['souscripteurEmail'],
               "souscripteurIsAssure" => 'OUI',
               "payeurDifferent" => "NON",
               "situationFam" => $data['souscripteursituationFam'],
               "profession" => "AUTRE",
               "assurerConjoint" => "OUI",
               "dateNaissance" => Carbon::parse($data['dateNaissance'])->format("d/m/Y")
            ],
            "payeur" => [
               "ibanPrelevemnt" => $data['souscripteuribanPrelevemnt'],
               "ibanRemboursement" => $data['souscripteuribanPrelevemnt'],
               "ibanRembDifferent" => "NON",
               "mandatSepa" => "GENERER_MANDAT",
               "payeurDifferent" => "NON"
            ],
        ];
        if(isset($data['etageAppart'])) {
           $result['produits']['MRH'][0]['etageAppart'] = $data['etageAppart'];
        }

        return $result;
      }

      public function sendSouscription($data,$firstTry = true){
        try {
          $token = $this->getAccessToken();
          if (!empty($token)) {
            $result = $this->collectDataForSaveContrat($data);
            $url = $this->baseUrl . '/api/saveContrat';
            $response = Http::withToken($token)->post($url ,$result );
            \Log::info('ECA SAVE DATA :: '.$url.' data :: '. json_encode($result));
            if($response->successful()){
                return response()->json([
                    'message' => 'JSON sent successfully!',
                    'response' => $response->json()
                ], 200);
            }else{
              if($response->status() == 422 || $response->status() == 400 ){

                \Log::info('ECA SAVE ERROR VALIDATION :: '.json_encode($response->object()));


                return response()->json([
                    'message' => 'Failed to send JSON.',
                    'error' => $response->body()
                ], $response->status());

              }else if($response->status() == 401){

                if($firstTry){
                  // retry with different token
                  Session::forget("eca_api_token");
                  \Log::info('ECA SAVE retry auth ');
                  return $this->sendSouscription($data,false);
                }

                \Log::info('ECA SAVE ERROR erreur d\'authentification  :: status: '.$response->status().' body : '.json_encode($response->body()));
                return ["erreur" => "erreur d'authentification "];
              }else{
                \Log::info('ECA SAVE ERROR :: status: '.$response->status().' body : '.json_encode($response->body()));
                return response()->json([
                    'message' => 'Failed to send JSON.',
                    'error' => $response->body()
                ],500);
              }
            }
          }
        } catch (\Exception $e) {
            \Log::info('ECA SAVE ERROR Exception :: '.$e->getMessage());
            return response()->json([ 'message' => 'Failed to send JSON.', 'error' => $e->getMessage() ],500);
        }
      }
}
