<?php
$tituloPagina = 'Empréstimos';
include __DIR__ . '/header.php';
?>
<h2>Gestão de Empréstimos</h2>

<form method="post" action="index.php?acao=emprestimos" class="form-cadastro">
    <div class="campos">
        <label>Aluno
            <select name="aluno_id" required>
                <option value="">Selecione...</option>
                <?php foreach ($alunos as $a): ?>
                    <option value="<?php echo $a['id']; ?>"><?php echo $a['nome']; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Livro
            <select name="livro_id" required>
                <option value="">Selecione...</option>
                <?php foreach ($livros as $l): ?>
                    <option value="<?php echo $l['id']; ?>">
                        <?php echo $l['titulo']; ?> (disp: <?php echo (new Livro())->quantidadeDisponivel($l['id']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <button type="submit">Emprestar</button>
</form>

<?php if (isset($resultado) && isset($resultado['erro'])): ?>
    <div class="erro"><?php echo $resultado['erro']; ?></div>
<?php endif; ?>

<h3>Empréstimos</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Aluno</th>
            <th>Livro</th>
            <th>Data Empréstimo</th>
            <th>Data Devolução</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($emprestimos as $e): ?>
        <tr>
            <td><?php echo $e['id']; ?></td>
            <td><?php echo $e['aluno_nome']; ?></td>
            <td><?php echo $e['livro_titulo']; ?></td>
            <td><?php echo $e['data_emprestimo']; ?></td>
            <td><?php echo $e['data_devolucao'] ?: '—'; ?></td>
            <td>
                <?php
                if ($e['status'] === 'emprestado') {
                    echo '<span class="badge badge-ativo">Emprestado</span>';
                } else {
                    echo '<span class="badge badge-devolvido">Devolvido</span>';
                }
                ?>
            </td>
            <td>
                <?php if ($e['status'] === 'emprestado'): ?>
                    <form method="post" action="index.php?acao=emprestimos" class="form-inline">
                        <input type="hidden" name="id" value="<?php echo $e['id']; ?>">
                        <button type="submit" name="devolver" value="1">Devolver</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p class="dica">
    Dica para o aluno-tester: tente emprestar um livro com quantidade disponível
    zero. O sistema deveria bloquear, mas talvez não faça. Avalie também o limite
    de livros por aluno.
</p>

<?php include __DIR__ . '/footer.php'; ?>
