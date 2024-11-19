<?php

namespace App\Http\Controllers;

use App\Http\Repositories\EcaSaveRepository;
use App\Http\Repositories\EcaTarificateurRepository;
use Illuminate\Http\Request;

class EcaController extends Controller
{
    public function __construct( protected EcaTarificateurRepository $ecaTarificateurRepo, protected EcaSaveRepository $ecaSaveRepo) {
    }
    /**
     * Handle the incoming request.
     */
    public function tarificateur(Request $request)
    {
      return  $this->ecaTarificateurRepo->getTarif($request->all());
    }

    public function saveContrat(Request $request)
    {
      return  $this->ecaSaveRepo->sendSouscription($request->all());
    }
}
