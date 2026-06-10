<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_return_successful_responses(): void
    {
        foreach (['/', '/catalog', '/teachers', '/about', '/contact', '/login', '/register', '/forgot-password'] as $path) {
            $this->get($path)->assertStatus(200);
        }
    }

    public function test_contact_form_stores_message(): void
    {
        $this->post('/contact', [
            'name' => 'Visitante EduLibre',
            'email' => 'visitante@example.com',
            'subject' => 'Dúvida sobre a plataforma',
            'message' => 'Gostaria de saber mais sobre as matérias disponíveis.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('contacts', [
            'email' => 'visitante@example.com',
            'subject' => 'Dúvida sobre a plataforma',
        ]);
    }

    public function test_student_can_register_access_dashboard_and_logout(): void
    {
        $this->post('/register', [
            'name' => 'Aluno Funcional',
            'email' => 'aluno@example.com',
            'role' => 'student',
            'specialty' => ['campo ignorado para aluno'],
            'bio' => ['campo ignorado para aluno'],
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'aluno@example.com',
            'role' => 'student',
            'status' => 'active',
            'specialty' => null,
        ]);

        $this->get('/dashboard')->assertStatus(200);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_existing_student_can_login(): void
    {
        User::create([
            'name' => 'Aluno Login',
            'email' => 'login@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->get('/dashboard')->assertStatus(200);
    }

    public function test_professor_registration_stays_pending_and_goes_to_login(): void
    {
        $this->post('/register', [
            'name' => 'Professor Funcional',
            'email' => 'professor@example.com',
            'role' => 'professor',
            'specialty' => 'Matemática',
            'bio' => 'Professor voluntário com experiência em educação básica.',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/login');

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'professor@example.com',
            'role' => 'professor',
            'status' => 'pending',
            'specialty' => 'Matemática',
        ]);
    }

    public function test_admin_can_approve_pending_professor_and_professor_can_login(): void
    {
        $admin = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $professor = User::create([
            'name' => 'Professor Pendente',
            'email' => 'pendente@example.com',
            'password' => Hash::make('password123'),
            'role' => 'professor',
            'status' => 'pending',
            'specialty' => 'História',
        ]);

        $this->actingAs($admin)
            ->get('/admin/users?role=professor&status=pending')
            ->assertStatus(200)
            ->assertSee('Professor Pendente');

        $this->actingAs($admin)
            ->post(route('admin.users.approve', $professor))
            ->assertRedirect(route('admin.users.show', $professor));

        $this->assertDatabaseHas('users', [
            'email' => 'pendente@example.com',
            'status' => 'active',
        ]);

        $this->post('/logout');

        $this->post('/login', [
            'email' => 'pendente@example.com',
            'password' => 'password123',
        ])->assertRedirect('/professor/dashboard');

        $this->get('/professor/dashboard')->assertStatus(200);
        $this->get('/professor/subjects/create')->assertStatus(200);
    }

    public function test_approved_professor_can_create_subject_draft(): void
    {
        $professor = User::create([
            'name' => 'Professor Ativo',
            'email' => 'ativo@example.com',
            'password' => Hash::make('password123'),
            'role' => 'professor',
            'status' => 'active',
            'specialty' => 'Programação',
        ]);

        $category = Category::create([
            'name' => 'Programação',
            'slug' => 'programacao',
            'color' => '#4F46E5',
        ]);

        $this->actingAs($professor)
            ->post('/professor/subjects', [
                'title' => 'Introdução ao PHP Moderno',
                'description' => 'Uma matéria introdutória com fundamentos essenciais para começar a programar com PHP.',
                'category_id' => $category->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('subjects', [
            'professor_id' => $professor->id,
            'category_id' => $category->id,
            'title' => 'Introdução ao PHP Moderno',
            'status' => 'draft',
        ]);

        $subject = Subject::where('professor_id', $professor->id)->firstOrFail();

        $this->actingAs($professor)
            ->get(route('professor.videos.index', $subject))
            ->assertStatus(200)
            ->assertSee('Criar primeira aula');

        $this->actingAs($professor)
            ->post(route('professor.videos.store', $subject), [
                'title' => 'Ambiente PHP com Laravel',
                'description' => 'Primeira aula pratica da materia.',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration' => 600,
                'order' => 1,
                'status' => 'active',
            ])
            ->assertRedirect();

        $video = Video::where('subject_id', $subject->id)->firstOrFail();

        $this->actingAs($professor)
            ->get(route('professor.videos.edit', [$subject, $video]))
            ->assertStatus(200)
            ->assertSee('Ambiente PHP com Laravel');

        $this->assertDatabaseHas('videos', [
            'subject_id' => $subject->id,
            'professor_id' => $professor->id,
            'title' => 'Ambiente PHP com Laravel',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::create([
            'name' => 'Admin Categorias',
            'email' => 'admin-categorias@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Criar categoria');

        $this->actingAs($admin)
            ->get('/admin/categories/create')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->post('/admin/categories', [
                'name' => 'Design',
                'description' => 'Conteúdos sobre design, UI e experiência do usuário.',
                'color' => '#0EA5E9',
            ])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', [
            'name' => 'Design',
            'slug' => 'design',
            'color' => '#0EA5E9',
        ]);
    }

    public function test_student_can_view_published_subject_and_start_enrollment(): void
    {
        $student = User::create([
            'name' => 'Aluno Catalogo',
            'email' => 'aluno-catalogo@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $professor = User::create([
            'name' => 'Professor Publicado',
            'email' => 'professor-publicado@example.com',
            'password' => Hash::make('password123'),
            'role' => 'professor',
            'status' => 'active',
            'specialty' => 'Programacao',
        ]);

        $category = Category::create([
            'name' => 'Programacao',
            'slug' => 'programacao',
            'color' => '#4F46E5',
        ]);

        $subject = Subject::create([
            'professor_id' => $professor->id,
            'category_id' => $category->id,
            'title' => 'Laravel para Iniciantes',
            'description' => 'Materia publicada para alunos iniciarem seus estudos pelo catalogo.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $video = Video::create([
            'subject_id' => $subject->id,
            'professor_id' => $professor->id,
            'title' => 'Primeiros passos',
            'description' => 'Aula inicial da materia publicada.',
            'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration' => 600,
            'order' => 1,
            'status' => 'active',
        ]);

        $this->actingAs($student)
            ->get('/catalog')
            ->assertStatus(200)
            ->assertSee('Laravel para Iniciantes');

        $this->actingAs($student)
            ->get(route('subjects.show', $subject->slug))
            ->assertStatus(200)
            ->assertSee('Come');

        $this->actingAs($student)
            ->get(route('videos.show', ['subjectSlug' => $subject->slug, 'videoSlug' => $video->slug]))
            ->assertStatus(200);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'in_progress',
        ]);

        $this->actingAs($student)
            ->get(route('student.subjects'))
            ->assertStatus(200)
            ->assertSee('Laravel para Iniciantes');
    }

    public function test_admin_can_approve_review_subject_and_student_can_enroll_after_publication(): void
    {
        $admin = User::create([
            'name' => 'Admin Revisao',
            'email' => 'admin-revisao@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $student = User::create([
            'name' => 'Aluno Revisao',
            'email' => 'aluno-revisao@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $professor = User::create([
            'name' => 'Professor Revisao',
            'email' => 'professor-revisao@example.com',
            'password' => Hash::make('password123'),
            'role' => 'professor',
            'status' => 'active',
            'specialty' => 'Laravel',
        ]);

        $category = Category::create([
            'name' => 'Backend',
            'slug' => 'backend',
            'color' => '#0EA5E9',
        ]);

        $subject = Subject::create([
            'professor_id' => $professor->id,
            'category_id' => $category->id,
            'title' => 'APIs com Laravel',
            'description' => 'Materia completa para aprender APIs modernas com Laravel.',
            'status' => 'draft',
        ]);

        $video = Video::create([
            'subject_id' => $subject->id,
            'professor_id' => $professor->id,
            'title' => 'Rotas e controllers',
            'description' => 'Primeira aula da materia de APIs.',
            'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration' => 720,
            'order' => 1,
            'status' => 'active',
        ]);

        $this->actingAs($professor)
            ->post(route('professor.subjects.submit', $subject))
            ->assertRedirect(route('professor.subjects.edit', $subject));

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'status' => 'under_review',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.subjects.index', ['status' => 'under_review']))
            ->assertStatus(200)
            ->assertSee('APIs com Laravel');

        $this->actingAs($admin)
            ->get(route('admin.subjects.show', $subject))
            ->assertStatus(200)
            ->assertSee('Aprovar e publicar');

        $this->actingAs($admin)
            ->post(route('admin.subjects.approve', $subject))
            ->assertRedirect(route('admin.subjects.show', $subject));

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'status' => 'published',
        ]);

        $subject->refresh();
        $video->refresh();

        $this->actingAs($student)
            ->get(route('subjects.show', $subject->slug))
            ->assertStatus(200)
            ->assertSee('Come');

        $this->actingAs($student)
            ->get(route('videos.show', ['subjectSlug' => $subject->slug, 'videoSlug' => $video->slug]))
            ->assertStatus(200);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_validation_messages_are_translated(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => ['não deveria ser array'],
            'email' => 'email-invalido',
            'role' => 'student',
            'password' => 'curta',
            'password_confirmation' => 'diferente',
        ]);

        $response->assertRedirect('/register');
        $this->assertSame('O campo nome deve ser um texto.', session('errors')->get('name')[0]);
    }
}
