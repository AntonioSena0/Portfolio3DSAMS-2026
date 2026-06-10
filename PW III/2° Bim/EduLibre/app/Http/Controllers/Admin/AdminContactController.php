<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $contacts = Contact::withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['status' => 'read']);

        return redirect()->back()
            ->with('success', 'Mensagem marcada como lida.');
    }

    public function markAsReplied(Contact $contact)
    {
        $contact->update(['status' => 'replied']);

        return redirect()->back()
            ->with('success', 'Mensagem marcada como respondida.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Mensagem excluída com sucesso.');
    }

    public function restore(Contact $contact)
    {
        $contact->restore();

        return redirect()->back()
            ->with('success', 'Mensagem restaurada com sucesso.');
    }

    public function forceDelete(Contact $contact)
    {
        $contact->forceDelete();

        return redirect()->back()
            ->with('success', 'Mensagem excluída permanentemente.');
    }
}