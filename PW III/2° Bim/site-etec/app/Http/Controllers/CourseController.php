<?php

namespace App\Http\Controllers;

use App\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courses,
    ) {
    }

    public function index()
    {
        return view('courses.index', [
            'courses' => $this->courses->listCourses(),
        ]);
    }
}
