<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Exibe o dashboard administrativo com estatísticas e dados recentes.
     */
    public function dashboard(): View
    {
        $studentsCount = User::query()->where('role', 'student')->count();
        $eventsCount = Event::query()->count();
        $coursesCount = Course::query()->count();
        $recentStudents = User::query()
            ->where('role', 'student')
            ->with('course')
            ->latest()
            ->limit(5)
            ->get();
        $recentEvents = Event::query()
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'studentsCount',
            'eventsCount',
            'coursesCount',
            'recentStudents',
            'recentEvents'
        ));
    }

    /**
     * Lista todos os alunos cadastrados.
     */
    public function students(): View
    {
        $students = User::query()
            ->where('role', 'student')
            ->with('course')
            ->latest()
            ->paginate(15);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Exibe o formulário de cadastro de aluno.
     */
    public function createStudent(): View
    {
        $courses = Course::query()->orderBy('name')->get();

        return view('admin.students.create', compact('courses'));
    }

    /**
     * Cadastra um novo aluno no sistema.
     */
    public function storeStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'course_id' => ['required', 'exists:courses,id'],
            'grade' => ['required', 'in:1,2,3'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'course_id' => $validated['course_id'],
            'grade' => $validated['grade'],
        ]);

        return redirect()
            ->route('admin.students')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um aluno.
     */
    public function editStudent(User $user): View
    {
        if (! $user->isStudent()) {
            abort(404);
        }

        $courses = Course::query()->orderBy('name')->get();

        return view('admin.students.edit', compact('user', 'courses'));
    }

    /**
     * Atualiza os dados de um aluno.
     */
    public function updateStudent(Request $request, User $user): RedirectResponse
    {
        if (! $user->isStudent()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'course_id' => ['required', 'exists:courses,id'],
            'grade' => ['required', 'in:1,2,3'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'course_id' => $validated['course_id'],
            'grade' => $validated['grade'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.students')
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    /**
     * Remove um aluno do sistema.
     */
    public function destroyStudent(User $user): RedirectResponse
    {
        if (! $user->isStudent()) {
            abort(404);
        }

        $user->delete();

        return redirect()
            ->route('admin.students')
            ->with('success', 'Aluno removido com sucesso!');
    }

    /**
     * Lista todos os eventos cadastrados.
     */
    public function events(): View
    {
        $events = Event::query()
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Exibe o formulário de criação de evento.
     */
    public function createEvent(): View
    {
        return view('admin.events.create');
    }

    /**
     * Cadastra um novo evento.
     */
    public function storeEvent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:' . Event::class],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'event_date' => ['required', 'date'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'in:destaque,noticia,evento,comunicado'],
            'target_grade' => ['nullable', 'in:1,2,3'],
        ]);

        Event::create($validated);

        return redirect()
            ->route('admin.events')
            ->with('success', 'Evento criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um evento.
     */
    public function editEvent(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Atualiza os dados de um evento.
     */
    public function updateEvent(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:' . Event::class . ',slug,' . $event->id],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'event_date' => ['required', 'date'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'in:destaque,noticia,evento,comunicado'],
            'target_grade' => ['nullable', 'in:1,2,3'],
        ]);

        $event->update($validated);

        return redirect()
            ->route('admin.events')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Remove um evento do sistema.
     */
    public function destroyEvent(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('admin.events')
            ->with('success', 'Evento removido com sucesso!');
    }
}
