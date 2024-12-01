<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{

    
  

    public function test(Request $request)
    {
       

dd($result);




        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiMWQzOWVlNmQyNGI2ZDQ3MTc1NmIxZjA1NjMxMWZkMTQwODc1MDMwZDI3ZDkyOTFjZmQwYmUyZjMwOWVkMTM4MDg5OTY1NzgzZTViMDk0OTEiLCJpYXQiOjE3MzMwNTc5NzMuMTMzNDM0LCJuYmYiOjE3MzMwNTc5NzMuMTMzNDM3LCJleHAiOjE3MzMxNDQzNzMuMTA4NDQ1LCJzdWIiOiI2NCIsInNjb3BlcyI6WyIqIl19.eeMQe-WMd1MQlO_J0pdalWAwsOm7ZzLoAaNxeyaGO2tXJHL177lZvMcT_6z_zMujrK5-GmZv78nh2FCBiBU0UtUOyuQsSTBBr4JRT0HyqWLgAfxhxHRy8Wu0UeRIblpCCvF3JRoYESuDKzpPRf70I9vS_ZAWwCbGzfnODfqlaQSYPOECC7oZfP5qHFszZ8U_3F4N_1YZdj9RLEGjqQYNhsGEYGBb97VLGS12BXXBcG8-yLG2S9jthcGFT4QTJkbM7Uq-Dv_tC1B4uN-tdu8d6qpZtebvrA9ovBtIYYorQvVwVaZzdu2tlSgmulbBAh5eZKhsyaIuGGjuSfsawLZ_TGVhFRGnZNZvarOIBP6k_NAJcC2lcBOANOzi57zE5acNB5N9nk20fNZfHXhZIXcjt3n12KY3EVJpzNC0eHZSGnfJP0iODm7jTCLDGR9D7w3frVzliQkO3xk8kraM_j3uGdpBCI26GPCwHUsEU5JzBHide4OmQCPk1pcnNrDJauJ_xdiU5kvd5K6TUrub16p4L4RlP98uTwzt3nlNoScipMfKNsUQBny9F1JtaHVkmsB29x5ogoGTQToTPvAj4v1FFmhyXAG7K8hBhggZ0rSsVHRkSdvjNl5QPQz7GysurHnN3chcZZz35YT8fN1BRSb2Lj1AK1oAMp4InnZBH2tz7Y4";
        $result =  [
            "produitChoisi" => "MRH",
            "produitType" => "MRH_GENERALI",
            "courtier" => "CO0075901991",
            "identifiantWs" => "lassurances",
            "tcv" => 20,
            "codePostal" => "75001",
            "ville" =>"paris",
            "dateEffet" => "02/12/2024",
            "typeResidence" =>"RESIDENCE_PRINCIPALE",
            "typeHabitation" => "APPARTEMENT",
            "qualiteAssure" => "LOCATAIRE_OCCUPANT",
            "nbrPiecePrincipale" => 2,
            "nbrPiecePrincipalePlus30m" =>  0,
            "nbrDependance" => 0,
            "nbrDependancePlus30m" => 0,
            "resilieAutreAssureur" => "NON",
            "sinistres2ansDerniers" =>"NON",
            "insertOuCheminee" => "NON",
            "nbEnfantMineur" => 0,
            "nbrEtageImmb" => "10",
            "etageBien" => "1",
            "nbPiecesPrincipalesSup50" =>"0",
            //"formuleGenerali" => $data["formuleGenerali"],
            "presenceVeranda" => "NON",
            "presencePicineOuTennis" => "NON",
            "capitalMobilier" =>  "8000",
            "niveauFranchise" =>  "TROISCENTS",
            "indemnMobilier" => "VALEUR_USAGE",
            "niveauOJ" => "ZERO"
        ];
        $url = "https://ws.eca-partenaires.com/api/tarificateur";
        $response = Http::withToken($token)->post($url ,$result );
        $tarifs =[];
        foreach ($response->object() as $tarif) {
             $tarifs[] = $tarif;
        }
        dd($tarifs);

        if($response->successful()){
            return $response;
            return response()->json([
                'message' => 'JSON sent successfully!',
                'response' => $response->json()
            ], 200);
    }}

  
    
}
