<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function destroy(EmergencyContact $emergencyContact)
    {
        try {
            $emergencyContact->delete();
            return back()->with('success', 'Acil durum kişisi başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Acil durum kişisi silinirken bir hata oluştu.');
        }
    }
} 