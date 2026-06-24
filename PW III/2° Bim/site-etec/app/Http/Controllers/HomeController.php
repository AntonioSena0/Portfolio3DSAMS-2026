<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Services\EventService;

class HomeController extends Controller
{
    public function __construct(
        private readonly CourseService $courses,
        private readonly EventService $events,
    ) {
    }

    /**
     * Exibe a página inicial com cursos e eventos em destaque.
     */
    public function index()
    {
        return view('home', [
            'courses' => $this->courses->latestCourses(3),
            'highlights' => $this->events->latestEvents(3),
        ]);
    }
}

