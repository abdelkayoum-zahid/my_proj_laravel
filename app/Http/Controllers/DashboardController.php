<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Logement;
use App\Models\Paiement;
use App\Models\Depense;
use App\Models\Reservation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
    

    return view('dashboard', [

    ]);
    }
}
