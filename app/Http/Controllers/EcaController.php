<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\EcaSaveRepository;
use App\Http\Repositories\EcaNotEligibleRepository;
use App\Http\Repositories\EcaTarificateurRepository;

class EcaController extends Controller
{
    public function __construct(
        protected EcaTarificateurRepository $ecaTarificateurRepo,
         protected EcaSaveRepository $ecaSaveRepo,
         protected EcaNotEligibleRepository $ecaNotEligibleRepo,
         ) {
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

    public function notEligible(Request $request)
    {
      return  $this->ecaNotEligibleRepo->notEligible($request->all());
    }
}
