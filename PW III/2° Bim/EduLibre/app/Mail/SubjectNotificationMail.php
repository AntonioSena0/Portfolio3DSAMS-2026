<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subject;

class SubjectNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectModel;
    public string $type;

    /**
     * Create a new message instance.
     */
    public function __construct(Subject $subject, string $type = 'info')
    {
        $this->subjectModel = $subject;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = match ($this->type) {
            'submitted_for_review' => 'Sua matéria foi submetida para revisão',
            'approved' => 'Sua matéria foi aprovada!',
            'rejected' => 'Sua matéria foi rejeitada',
            default => 'Notificação sobre sua matéria',
        };

        return $this->subject($subject)
                    ->view('emails.subject_notification')
                    ->with([
                        'subject' => $this->subjectModel,
                        'type' => $this->type,
                    ]);
    }
}
