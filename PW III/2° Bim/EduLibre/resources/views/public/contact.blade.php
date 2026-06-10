@extends('layouts.app')

@section('content')
<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:px-8">
        <div class="contact-copy">
            <p class="text-sm font-bold uppercase tracking-wide text-primary-700">Contato</p>
            <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-secondary-900">Fale com a EduLibre</h1>
            <p class="mt-4 text-lg leading-8 text-secondary-600">Envie dúvidas, sugestões ou pedidos de suporte. A mensagem fica registrada no painel para acompanhamento.</p>
            <div class="mt-8 space-y-3 rounded-xl border border-secondary-200 bg-secondary-50 p-5">
                <p class="text-sm font-bold text-secondary-900">O que você pode enviar</p>
                <p class="text-sm leading-6 text-secondary-600">Problemas de acesso, sugestões para matérias, dúvidas sobre cadastro de professor ou qualquer ponto da plataforma.</p>
            </div>
        </div>

        <div class="contact-form rounded-xl border border-secondary-200 bg-secondary-50 p-5 shadow-card">
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ session('error') }}</div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Nome</label>
                        <input id="name" name="name" value="{{ old('name') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        @error('name') <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                        @error('email') <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="subject" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Assunto</label>
                    <input id="subject" name="subject" value="{{ old('subject') }}" required class="h-11 w-full rounded-lg border border-secondary-200 bg-white px-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">
                    @error('subject') <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="message" class="mb-1 block text-xs font-bold uppercase tracking-wide text-secondary-500">Mensagem</label>
                    <textarea id="message" name="message" rows="6" required class="w-full rounded-lg border border-secondary-200 bg-white px-3 py-3 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>
                <button class="w-full rounded-lg bg-primary-600 px-5 py-3 text-sm font-bold text-white hover:bg-primary-700">Enviar mensagem</button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.from('.contact-copy > *', { y: 18, opacity: 0, duration: 0.55, stagger: 0.06, ease: 'power2.out' });
    gsap.from('.contact-form', { y: 22, opacity: 0, duration: 0.55, ease: 'power2.out', delay: 0.12 });
});
</script>
@endpush
