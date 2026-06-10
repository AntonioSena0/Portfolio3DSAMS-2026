<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ProfessorWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $professor;
    public string $type;

    /**
     * Create a new message instance.
     */
    public function __construct(User $professor, string $type = 'welcome')
    {
        $this->professor = $professor;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = match ($this->type) {
            'new_registration' => 'Novo professor cadastrado - Aguardando aprovação',
            'welcome' => 'Bem-vindo à EduLibre!',
            'approved' => 'Seu cadastro foi aprovado!',
            default => 'Notificação da EduLibre',
        };

        return $this->subject($subject)
                    ->view('emails.professor_welcome')
                    ->with([
                        'professor' => $this->professor,
                        'type' => $this->type,
                    ]);
    }
}