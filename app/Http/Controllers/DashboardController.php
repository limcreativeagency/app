<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hospital = null;
        $showMenus = false;

        // Klinik yöneticisi, doktor ve temsilci için hastane bilgilerini al
        if (in_array($user->role_id, [2, 3, 4])) {
            $hospital = Hospital::find($user->hospital_id);
            $showMenus = true; // Bu roller için menüleri göster
        }

        switch ($user->role_id) {
            case 1: // Süper Admin
                return redirect('/admin/dashboard');
            case 2: // Klinik Yöneticisi
            case 3: // Doktor
            case 4: // Temsilci
                return view('clinic.dashboard', compact('hospital', 'showMenus', 'user'));
            case 5: // Hasta
                return redirect('/patient/dashboard');
            default:
                abort(403);
        }
    }
} 