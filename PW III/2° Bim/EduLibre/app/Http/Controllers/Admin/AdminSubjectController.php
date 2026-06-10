<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Subject;
use App\Models\Category;
use App\Events\Subject\SubjectPublished;
use App\Events\Subject\SubjectRejected;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Jobs\SendSubjectStatusNotification;

class AdminSubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by professor
        if ($request->filled('professor_id')) {
            $query->where('professor_id', $request->input('professor_id'));
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $subjects = $query->with([
            'professor:id,name,avatar',
            'category:id,name,color'
        ])
            ->withCount([
                'videos as videos_count' => fn($q) => $q->where('status', 'active'),
                'enrollments as enrollments_count'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function show(Subject $subject)
    {
        $subject->load([
            'videos:id,subject_id,title,slug,status,views_count,order,duration',
            'professor:id,name,bio,specialty',
            'category:id,name,color',
            'enrollments' => fn($q) => $q->with('student:id,name'),
        ]);

        return view('admin.subjects.show', compact('subject'));
    }

    public function approve(Subject $subject)
    {
        // Ensure the subject is under review
        if ($subject->status !== 'under_review') {
            throw ValidationException::withMessages([
                'approve' => 'Apenas matérias em revisão podem ser aprovadas.',
            ]);
        }

        $subject->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        event(new SubjectPublished($subject));

        // Send notification to professor (queued)
        SendSubjectStatusNotification::dispatch($subject, 'approved');

        return redirect()->route('admin.subjects.show', $subject)
            ->with('status', 'Matéria publicada com sucesso!');
    }

    public function reject(Subject $subject)
    {
        // Ensure the subject is under review
        if ($subject->status !== 'under_review') {
            throw ValidationException::withMessages([
                'reject' => 'Apenas matérias em revisão podem ser rejeitadas.',
            ]);
        }

        $request = request(); // Get the current request instance

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        $subject->update([
            'status' => 'draft',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        event(new SubjectRejected($subject));

        // Send notification to professor (queued)
        SendSubjectStatusNotification::dispatch($subject, 'rejected');

        return redirect()->route('admin.subjects.show', $subject)
            ->with('status', 'Matéria rejeitada com sucesso!');
    }

    public function archive(Subject $subject)
    {
        // Ensure the subject is published
        if ($subject->status !== 'published') {
            throw ValidationException::withMessages([
                'archive' => 'Apenas matérias publicadas podem ser arquivadas.',
            ]);
        }

        $subject->update(['status' => 'archived']);

        return redirect()->route('admin.subjects.show', $subject)
            ->with('status', 'Matéria arquivada com sucesso!');
    }
}
