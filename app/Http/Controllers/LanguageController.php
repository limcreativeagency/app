<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        // Desteklenen diller
        $supportedLocales = ['tr', 'en'];
        
        if (in_array($lang, $supportedLocales)) {
            // Session'a dili kaydet
            session()->put('locale', $lang);
            
            // Kullanıcı giriş yapmışsa, veritabanında da güncelle
            if (Auth::check()) {
                Auth::user()->update(['language' => $lang]);
            }
        }
        
        return redirect()->back();
    }
}
