<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Enrollment;
use App\Models\Subject;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = Enrollment::where('student_id', $user->id)
            ->with(['subject:id,title,slug,category_id,professor_id', 'subject.professor:id,name', 'subject.category:id,name,color'])
            ->orderBy('status')
            ->latest()
            ->paginate(12);

        return view('student.my-subjects', compact('enrollments'));
    }
}
