<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\TreatmentNote;

class NewTreatmentMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $note;

    public function __construct(TreatmentNote $note)
    {
        $this->note = $note;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->note->note,
            'treatment_note_id' => $this->note->id,
            'treatment_id' => $this->note->treatment_id,
            'user_id' => $this->note->user_id,
        ];
    }
} 