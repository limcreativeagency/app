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
            return back()->with('success', __('general.emergency_contact_deleted'));
        } catch (\Exception $e) {
            return back()->with('error', __('general.emergency_contact_delete_error'));
        }
    }
} 