<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\TreatmentNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewTreatmentMessage;
use Illuminate\Support\Facades\DB;

class TreatmentNoteController extends Controller
{
    public function store(Request $request, Treatment $treatment)
    {
        $request->validate([
            'note' => 'required|string',
            'is_visible_to_patient' => 'nullable|boolean'
        ]);

        $user = Auth::user();

        $isVisible = $request->input('is_visible_to_patient', 0);

        $note = $treatment->notes()->create([
            'user_id' => $user->id,
            'note' => $request->note,
            'is_visible_to_patient' => $isVisible
        ]);

        // Bildirim gönder
        if ($user->hasRole('patient')) {
            // Klinik adminlere bildir
            $admins = \App\Models\User::whereHas('role', function($q){ $q->where('slug', 'clinic_admin'); })->get();
            foreach($admins as $admin) {
                $admin->notify(new NewTreatmentMessage($note));
            }
        } else if ($user->hasRole('clinic_admin')) {
            // Hastaya bildir
            $patient = $treatment->patient->user ?? null;
            if ($patient) {
                $patient->notify(new NewTreatmentMessage($note));
            }
        }

        return redirect()->back()->with('success', 'Not başarıyla eklendi.');
    }

    public function reply(Request $request, TreatmentNote $note)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $user = Auth::user();
        $userType = $user->role->slug;

        $note->replies()->create([
            'user_id' => $user->id,
            'reply' => $request->reply
        ]);

        return redirect()->back()->with('success', 'Cevap başarıyla eklendi.');
    }

    public function destroy(TreatmentNote $note)
    {
        // Sadece notu yazan kişi veya admin silebilir
        if (Auth::id() !== $note->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Bu notu silme yetkiniz yok.');
        }

        $note->delete();
        return redirect()->back()->with('success', 'Not başarıyla silindi.');
    }

    public function destroyReply(TreatmentNoteReply $reply)
    {
        // Sadece cevabı yazan kişi veya admin silebilir
        if (Auth::id() !== $reply->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Bu cevabı silme yetkiniz yok.');
        }

        $reply->delete();
        return redirect()->back()->with('success', 'Cevap başarıyla silindi.');
    }

    public function messages(Treatment $treatment)
    {
        $user = auth()->user();
        if ($user->hasRole('patient') || $user->hasRole('clinic_admin')) {
            $notes = $treatment->notes()->where('is_visible_to_patient', true)->with('user')->orderBy('created_at')->get();
        } else {
            $notes = $treatment->notes()->with('user')->orderBy('created_at')->get();
        }
        return view('treatments.messages', compact('treatment', 'notes'));
    }

    public function unreadMessages()
    {
        $user = auth()->user();
        $unreadGroups = \DB::table('treatment_notes')
            ->select(
                'treatment_id',
                \DB::raw('COUNT(*) as unread_count'),
                \DB::raw('MAX(created_at) as last_message_at')
            )
            ->whereNull('read_at')
            ->where('user_id', '!=', $user->id)
            ->groupBy('treatment_id')
            ->get();

        $groups = $unreadGroups->map(function($item) {
            $treatment = \App\Models\Treatment::find($item->treatment_id);
            $patient = $treatment && $treatment->patient ? $treatment->patient->user->name : '-';
            return [
                'patient_name' => $patient,
                'treatment_title' => $treatment ? $treatment->title : '-',
                'treatment_id' => $item->treatment_id,
                'unread_count' => $item->unread_count,
                'last_message_at' => $item->last_message_at,
            ];
        });

        return view('treatments.unread_messages', [
            'groups' => $groups
        ]);
    }
} 