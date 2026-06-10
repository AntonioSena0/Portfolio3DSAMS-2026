<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>EduLibre</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h1>EduLibre</h1>
    @if($type === 'new_registration')
        <p>Um novo professor se cadastrou e aguarda aprovação.</p>
        <p><strong>Nome:</strong> {{ $professor->name }}</p>
        <p><strong>E-mail:</strong> {{ $professor->email }}</p>
        <p><strong>Especialidade:</strong> {{ $professor->specialty }}</p>
    @elseif($type === 'approved')
        <p>Olá, {{ $professor->name }}. Seu cadastro de professor foi aprovado.</p>
        <p>Você já pode acessar a área do professor e publicar conteúdos.</p>
    @else
        <p>Olá, {{ $professor->name }}. Seu cadastro foi recebido pela EduLibre.</p>
        <p>Assim que houver atualização, você receberá uma nova notificação.</p>
    @endif
</body>
</html>
