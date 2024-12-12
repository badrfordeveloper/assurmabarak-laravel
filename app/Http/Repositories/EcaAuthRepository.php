<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EcaAuthRepository {

  protected $baseUrl;
  private $identifiant;
  private $motDePasse;

  public function __construct()
  {
      $this->baseUrl  =   'https://ws.eca-partenaires.com';
      $this->identifiant = 'lassurances';
      $this->motDePasse = 'b9O^e)In';
  }

  public function getAccessToken(){
    $access_token = Session::get("eca_api_token");
    if($access_token == null ){
        $this->auth();
        $access_token = Session::get("eca_api_token");
    }
    return $access_token;
  }

  public function auth()
  {
      try {
          $data = [
            'identifiant' => $this->identifiant,
            'motDePasse' => $this->motDePasse,
          ];
          $url = $this->baseUrl.'/api/login';
          $response = Http::post($url ,$data );
          if ($response->successful()) {
              // Store the Bearer Token in the session
              $access_token =$response['token'];
              Session::put("eca_api_token",$access_token);
              Session::save();
          } else {
           // $this->logAndNotifySupport('Problem auth ECA : '.$url.' data :: '. json_encode($data));
           \Log::info('ECA AUTH ERROR');
            throw new \Exception('Problem auth ECA');
          }

      }
      catch (\Exception $e) {
         // $this->logAndNotifySupport('Problem auth ECA :: '.$url.' data :: '. json_encode($data));
         \Log::info('ECA AUTH ERROR Exception :: '.$e->getMessage());

          throw new \Exception('Problem auth ECA');
      }
  }









}

