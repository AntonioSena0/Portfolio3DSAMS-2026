<?php
$tituloPagina = 'Painel';
include __DIR__ . '/header.php';
?>
<h2>Painel de Controle</h2>

<!-- BUG DE UI: cards de estatísticas -->
<section class="cards">
    <div class="card">
        <h3>Total de Livros</h3>
        <p class="valor"><?php echo $totalLivros; ?></p>
    </div>
    <div class="card">
        <h3>Total de Alunos</h3>
        <p class="valor"><?php echo $totalAlunos; ?></p>
    </div>
    <div class="card">
        <h3>Total de Empréstimos</h3>
        <p class="valor"><?php echo $totalEmprestimos; ?></p>
    </div>
</section>

<section class="atrasados">
    <h3>Empréstimos em Atraso</h3>
    <?php if (count($atrasados) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Livro</th>
                    <th>Data Empréstimo</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($atrasados as $item): ?>
                <tr>
                    <td><?php echo $item['aluno_nome']; ?></td>
                    <td><?php echo $item['livro_titulo']; ?></td>
                    <td><?php echo $item['data_emprestimo']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum empréstimo em atraso. <span class="ok">(pode ser bug no cálculo)</span></p>
    <?php endif; ?>
</section>

<p class="dica">
    Dica para o aluno-tester: verifique se a listagem de atrasados está
    <strong>correta</strong>. Confira as datas e o status.
</p>

<?php include __DIR__ . '/footer.php'; ?>
