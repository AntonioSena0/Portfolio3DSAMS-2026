<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            // Store the contact message in database
            Contact::create($validated);

            // Log the contact message
            Log::info('Contact form submitted and stored', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

            // Optional: Send email notification (would need mail configuration)
            // Mail::to(config('mail.from.address'))->send(new \App\Mail\ContactMessage($validated));

            return Redirect::route('contact')
                ->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error processing contact form: ' . $e->getMessage());

            return Redirect::route('contact')
                ->with('error', 'Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.');
        }
    }
}