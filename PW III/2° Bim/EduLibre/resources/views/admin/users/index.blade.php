@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Admin</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Usuários</h1>
                <p class="mt-3 text-secondary-600">Filtre professores pendentes e aprove cadastros.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar ao painel</a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="mt-6 grid gap-3 rounded-xl border border-secondary-200 bg-secondary-50 p-4 md:grid-cols-[1fr_180px_180px_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Buscar por nome ou e-mail" class="h-11 rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
            <select name="role" class="h-11 rounded-lg border border-secondary-200 bg-white px-3 text-sm">
                <option value="">Todas as roles</option>
                <option value="student" @selected(request('role') === 'student')>Aluno</option>
                <option value="professor" @selected(request('role') === 'professor')>Professor</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            </select>
            <select name="status" class="h-11 rounded-lg border border-secondary-200 bg-white px-3 text-sm">
                <option value="">Todos os status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                <option value="active" @selected(request('status') === 'active')>Ativo</option>
                <option value="blocked" @selected(request('status') === 'blocked')>Bloqueado</option>
            </select>
            <button class="rounded-lg bg-primary-600 px-5 text-sm font-bold text-white hover:bg-primary-700">Filtrar</button>
        </form>
    </div>
</section>

<section class="bg-secondary-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        <div class="overflow-hidden rounded-xl border border-secondary-200 bg-white shadow-card">
            <div class="grid grid-cols-[1.4fr_.8fr_.8fr_1fr] gap-4 border-b border-secondary-200 bg-secondary-50 px-4 py-3 text-xs font-bold uppercase tracking-wide text-secondary-500">
                <span>Usuário</span>
                <span>Role</span>
                <span>Status</span>
                <span>Ações</span>
            </div>
            @forelse($users as $user)
                <div class="grid grid-cols-[1.4fr_.8fr_.8fr_1fr] items-center gap-4 border-b border-secondary-100 px-4 py-4 text-sm last:border-b-0">
                    <div>
                        <p class="font-bold text-secondary-900">{{ $user->name }}</p>
                        <p class="text-secondary-600">{{ $user->email }}</p>
                    </div>
                    <span class="font-semibold text-secondary-700">{{ $user->role }}</span>
                    <span class="w-fit rounded-lg px-2 py-1 text-xs font-bold {{ $user->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : ($user->status === 'blocked' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700') }}">{{ $user->status }}</span>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border border-secondary-200 px-3 py-2 text-xs font-bold text-secondary-700 hover:border-primary-200">Detalhes</a>
                        @if($user->role === 'professor' && $user->status === 'pending')
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                @csrf
                                <button class="rounded-lg bg-primary-600 px-3 py-2 text-xs font-bold text-white hover:bg-primary-700">Aprovar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-secondary-600">Nenhum usuário encontrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</section>
@endsection
