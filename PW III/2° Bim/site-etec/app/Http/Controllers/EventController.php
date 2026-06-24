<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $events,
    ) {
    }

    public function index()
    {
        return view('events.index', [
            'events' => $this->events->listEvents(),
        ]);
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function markAsRead(Event $event): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->readEvents()->syncWithoutDetaching([
                $event->id => ['read_at' => now()],
            ]);
        }

        return back()->with('success', 'Evento marcado como lido.');
    }

    public function markAsUnread(Event $event): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->readEvents()->updateExistingPivot($event->id, [
                'read_at' => null,
            ]);
        }

        return back()->with('success', 'Evento marcado como não lido.');
    }
}
