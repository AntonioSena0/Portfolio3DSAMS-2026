@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-primary-700 hover:text-primary-900">Voltar para usuários</a>

        @if(session('status'))
            <div class="mt-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        <div class="mt-6 rounded-xl border border-secondary-200 bg-secondary-50 p-6 shadow-card">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Usuário</p>
                    <h1 class="mt-2 text-3xl font-extrabold text-secondary-900">{{ $user->name }}</h1>
                    <p class="mt-2 text-secondary-600">{{ $user->email }}</p>
                </div>
                <span class="w-fit rounded-lg px-3 py-2 text-sm font-bold {{ $user->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : ($user->status === 'blocked' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700') }}">{{ $user->role }} · {{ $user->status }}</span>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-white p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Especialidade</p>
                    <p class="mt-2 font-semibold text-secondary-900">{{ $user->specialty ?: 'Não informada' }}</p>
                </div>
                <div class="rounded-lg bg-white p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Cadastro</p>
                    <p class="mt-2 font-semibold text-secondary-900">{{ $user->created_at?->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if($user->bio)
                <div class="mt-4 rounded-lg bg-white p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-secondary-500">Bio</p>
                    <p class="mt-2 leading-6 text-secondary-700">{{ $user->bio }}</p>
                </div>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                @if($user->role === 'professor' && $user->status === 'pending')
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                        @csrf
                        <button class="rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Aprovar professor</button>
                    </form>
                @endif

                @if($user->status !== 'blocked' && auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                        @csrf
                        <button class="rounded-lg border border-red-200 px-5 py-3 text-sm font-bold text-red-700 hover:bg-red-50">Bloquear</button>
                    </form>
                @elseif($user->status === 'blocked')
                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                        @csrf
                        <button class="rounded-lg border border-green-200 px-5 py-3 text-sm font-bold text-green-700 hover:bg-green-50">Desbloquear</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
