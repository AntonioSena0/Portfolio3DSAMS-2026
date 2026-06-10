<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>EduLibre</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h1>{{ $subject->title }}</h1>
    @if($type === 'submitted_for_review')
        <p>A matéria foi submetida para revisão administrativa.</p>
    @elseif($type === 'approved')
        <p>Sua matéria foi aprovada e publicada no catálogo.</p>
    @elseif($type === 'rejected')
        <p>Sua matéria foi rejeitada e voltou para rascunho.</p>
        @if($subject->rejection_reason)
            <p><strong>Motivo:</strong> {{ $subject->rejection_reason }}</p>
        @endif
    @else
        <p>Há uma atualização sobre esta matéria.</p>
    @endif
</body>
</html>
