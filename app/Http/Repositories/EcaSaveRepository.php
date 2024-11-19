<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\EcaAuthRepository;

class EcaSaveRepository extends EcaAuthRepository {

    public function collectDataForSaveContrat($data){
        $result =  [
            "flag" => "DEVIS_COMPLET",
            "flagType" => "DOCUMENT", // Obtenir le PDF avant signature en format base64 et refaire un appel d'api pour la signature et paiement par contre cela crée 2 fois le contrat dans l'espace courtage
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
                     "jardinBiensExterieur" => "NON",
                     "piscine" => $data['piscine'] ? $data['piscine'] : "NON",
                     "rcPiscineTennis" => $data['piscine'] ? $data['piscine'] : "NON",
                     "surfaceDepSuperieur100" => "NON",
                     "dateEffet" => $data['dateEffet'],
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
                     "bienComportePiscineTennis" => "NON",
                     "batimentCouvertMatDurs" => "OUI",
                     "moyenProtectionVols" => $data['moyenProtectionVols'],
                     "capitalMobilier" => $data['capitalMobilier'],
                     "franchise" => "TROISCENTS",
                     "indemnisationMobilier" => "VALEUR_NEUF_5_ANS",
                     "complementAdresse" => $data['souscripteurAdressePostale'],
                     "periodicite" => "MENSUELLE",
                     "assureObjetValeur" => "OUI",
                     "niveauGarantieObjVal" => "10",
                     "valeurEstime" => "5000",
                     "declarAssistantMatern" => "OUI",
                     "panneauPhotoVolt" => "OUI",
                     "bienElectrMoin5ans" => "OUI",
                     "possedeJardins" => "NON",
                     "repriseHamon" => "NON",
                     "modePaiement" => "CHEQUE",
                     "modePaiementCotisationSuivante" => "PRELEVEMENT",
                     "formuleRecommandee" => $data['formuleChoisi'],
                     "formuleChoisi" => $data['formuleChoisi'],
                     "fraisDossier" => 0,
                     "assureur" => [
                        "identifiantAssureur" =>  "",
                        "nomAssureur" => "test test",
                        "referenceContrat" => "aaaaaaa",
                        "dateEcheanceContrat" => "12/12/2018",
                        "typeVoie" => "QUAI",
                        "libelle" => "aaaa",
                        "complement" => "Complément",
                        "codePostal" => "09100",
                        "ville" => "St-Michel"
                     ]
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
               "dateNaissance" => $data['dateNaissance']
            ],
           //  "payeur" => [
           //     "ibanPrelevemnt" => str_replace(' ','', $data['souscripteuribanPrelevemnt']),
           //     "ibanRembDifferent" => "NON",
           //     "ibanRemboursement" => str_replace(' ','', $data['souscripteuribanPrelevemnt']),
           //     "mandatSepa" => "GENERER_MANDAT",
           //     "payeurDifferent" => "NON",
           //     "nomPayeur" => $data['souscripteurNom'],
           //     "prenomPayeur" => $data['souscripteurPrenom'],
           //     "numeroPayeur" => str_replace(' ','', $data['souscripteurTel']),
           //     "voiePayeur" => $data['souscripteurAdressePostale'],
           //     "batimentPayeur" => null,
           //     "libellePayeur" => "Complément",
           //     "codePostalPayeur" => $data['souscripteurCodePostal'],
           //     "villePayeur" => $data['souscripteurVille'],
           //  ],
        ];

        // if(isset($data['nbrPiecePrincipale']) && $data['nbrPiecePrincipale'] === 1) {
        //    $data['produits']['produit']['type'] = 'MRH';

        // } else {
        //    $data['produits']['produit']['type'] = 'MRH_GENERALI';
        // }

        if(isset($data['flagType']) && $data['flagType'] == 'subscription') {
           unset($result['flagType']);
        }

        if(isset($data['etageAppart'])) {
           $result['produits']['MRH'][0]['etageAppart'] = $data['etageAppart'];
        }

        if($data['typeHabitation'] == 'APPARTEMENT') {
           $result['produits']['MRH'][0]['nbrEtage'] = 5;
        }

        // if($data['formuleChoisi'] == 'ECO'){
        //    $data['formuleChoisi'] = 'ESSENTIELLE';
        // }
        // if($data['formuleChoisi'] == 'ECO'){
        //    $data['formuleChoisi'] = 'ESSENTIELLE';
        // }

        if($data['formuleChoisi'] == 'ESSENTIELLE'){
           $result['produits']['MRH'][0]['dontObjetsValeur'] = 'ZERO';
           $result['produits']['MRH'][0]['indemnisationMobilier'] = 'VALEUR_USAGE';
        }
        if($data['formuleChoisi'] == 'CONFORT'){
           $result['produits']['MRH'][0]['dontObjetsValeur'] = 'DIX';
        }
        if($data['formuleChoisi'] == 'COMPLETE'){
           $result['produits']['MRH'][0]['dontObjetsValeur'] = 'QUINZE';
        }
        if($data['formuleChoisi'] == 'OPTIMUM'){
           $result['produits']['MRH'][0]['dontObjetsValeur'] = 'TRENTE';
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
