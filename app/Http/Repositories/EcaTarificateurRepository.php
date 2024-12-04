<?php
namespace App\Http\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\EcaAuthRepository;

class EcaTarificateurRepository extends EcaAuthRepository {
    function etageBienMrh($etage, $nbr_etage_immeuble)
    {
        switch ($etage) {
            case 'RDC':
            return 0;
            break;
            case 'DERNIER':
            return  $nbr_etage_immeuble;
            break;
            case 'INTERMEDIAIRE':
            return  $nbr_etage_immeuble - 1;
            break;
            default:
            return 0;
            break;
        }
    }

    public function collectDataForTarif($data){
        $etageBien = $data['typeHabitation'] == 'APPARTEMENT' ? $this->etageBienMrh($data['etageBien'] ?? '', $data['nbrEtageImmb']) : '';

        $result =  [
            "produitChoisi" => "MRH",
            "produitType" => $data["produitType"],
            "courtier" => "CO0075901991",
            "identifiantWs" => "lassurances",
            "tcv" => 20,
            "codePostal" => $data["codePostal"],
            "ville" => $data["ville"],
            "dateEffet" => Carbon::parse($data['dateEffet'])->format("d/m/Y"),
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
            "nbrEtageImmb" => $data["nbrEtageImmb"],
            "etageBien" => $etageBien,
            "nbPiecesPrincipalesSup50" => $data["nbPiecesPrincipalesSup50"],
            "presenceVeranda" => $data["presenceVeranda"],
            "presencePicineOuTennis" => $data["presencePicineOuTennis"],
            "capitalMobilier" => $data["capitalMobilier"],
            "niveauFranchise" => $data["niveauFranchise"],
            "indemnMobilier" => $data["indemnMobilier"],
            "niveauOJ" => $data["niveauOJ"]
        ];

        if(isset($data["formuleGenerali"])){
            $result["formuleGenerali"] = $data["formuleGenerali"];
        }
        return $result;
    }

    public function getTarif($data,$firstTry = true){
    /* try { */


        $token = $this->getAccessToken();
        if (!empty($token)) {
        $result = $this->collectDataForTarif($data);
        $url = $this->baseUrl . '/api/tarificateur';

        \Log::info('ECA Tarif DATA :: '.$url.' data :: '. json_encode($result));

        $response = Http::withToken($token)->post($url ,$result );
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
   /*  } catch (\Exception $e) {
        \Log::info('ECA Tarif ERROR Exception :: '.$e->getMessage());
        return response()->json([ 'message' => 'Failed to send JSON.', 'error' => $e->getMessage() ],500);
    } */
    }

    public function getDependecies($nbrPiece){
        $result = [];
        foreach ($this->getFormules($nbrPiece) as $formule) {
            $result[] = [
                "formule" => $formule,
                "franchise" => $this->getFranchise($formule),
                "objetValeur" => $this->getObjetValeur($formule),
                "indemnisationMobilier" => $this->getIndemnisationMobilier($formule),
                "capitals" => $this->getCapital($nbrPiece, $formule),
            ];
        }
        return $result;

    }


    public function getFormules($nbrPiece)
  {
    if ($nbrPiece > 1) {
        return [
            'ESSENTIELLE' ,
            'CONFORT',
            'COMPLETE',
            'OPTIMUM'
          ];
    } else {
        return ["ECO", "CONFORT", "OPTIMALE", "PREMIUM"];
    }
  }

  public function getFranchise($formule)
  {
    $data = [ "AUCUNE" => "Aucune", "CENTCINQUANTE" => "150 €", "TROISCENTS" => "300 €"];
    if($formule == 'ESSENTIELLE')
      $data['CINQCENTS'] = '500 €';

    return $data;
  }

  public function getObjetValeur($formule)
  {
    if ($formule == 'ESSENTIELLE') {
      return ["ZERO" => "0%"];
    }
    if ($formule == 'CONFORT') {
      return [ "ZERO" => "0%", "DIX" => "10%"];
    } elseif ($formule == 'COMPLETE') {
      return ["QUINZE" => "15%"];
    } elseif ($formule == 'OPTIMUM') {
      return [ "VINGT" => "20%", "TRENTE" => "30%"];
    }
    return ["ZERO" => "0%"];
  }

  public function getIndemnisationMobilier($formule)
  {
    if ($formule == 'ESSENTIELLE') {
      return ["VALEUR_USAGE" => "Valeur d'usage"];
    }
    return [ "VALEUR_USAGE" => "Valeur d'usage", "VALEUR_NEUF_25_POURCENT" => "Valeur à neuf 25%", "VALEUR_NEUF_2_ANS" => "Valeur à neuf intégrale 2 ans", "VALEUR_NEUF_5_ANS" => "Valeur à neuf intégrale 5 ans"];
  }

  public function getCapital($nbrPiece, $formule)
  {

    if ($nbrPiece <= 1 ) {
      return ["5000" => "5000 €", "10000" => "10000 €", "15000" => "15000 €", "20000" => "20000 €", "25000" => "25000 €", "30000" => "30000 €", "35000" => "35000 €", "40000" => "40000 €"];
    } else{
        if ($formule == 'ESSENTIELLE') {
            return [2000 * $nbrPiece => $this->numberFormatWithEuro(2000 * $nbrPiece),3000 * $nbrPiece => $this->numberFormatWithEuro(3000 * $nbrPiece),4000 * $nbrPiece => $this->numberFormatWithEuro(4000 * $nbrPiece)];
        } elseif ($formule == 'CONFORT') {
            return [4000 * $nbrPiece => $this->numberFormatWithEuro(4000 * $nbrPiece), 8000 * $nbrPiece => $this->numberFormatWithEuro(8000 * $nbrPiece), 12000 * $nbrPiece => $this->numberFormatWithEuro(12000 * $nbrPiece)];
        } elseif ($formule == 'COMPLETE' || $formule == 'OPTIMUM') {
        $max = ($nbrPiece == 4)  ? 60000 : (in_array($nbrPiece,[5,6]) ? 100000 : 16000 * $nbrPiece);
            return [8000 * $nbrPiece => $this->numberFormatWithEuro(8000 * $nbrPiece), 12000 * $nbrPiece => $this->numberFormatWithEuro(12000 * $nbrPiece),    $max => $this->numberFormatWithEuro($max)];
        }
    }
    return [];

  }
  public function  numberFormatWithEuro($valeur,$decimal = 0)
  {
      $valeur= str_replace(',','.', $valeur);
      $valeur= str_replace(' €','', $valeur);
    return number_format($valeur, $decimal, ',', ' ') . " €";
  }
}
