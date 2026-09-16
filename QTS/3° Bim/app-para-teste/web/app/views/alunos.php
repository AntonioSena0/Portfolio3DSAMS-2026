<?php
$tituloPagina = 'Alunos';
include __DIR__ . '/header.php';
?>
<h2>Cadastro de Alunos</h2>

<form method="post" action="index.php?acao=alunos" class="form-cadastro">
    <div class="campos">
        <label>Nome
            <input type="text" name="nome" required>
        </label>
        <label>E-mail
            <!-- BUG DE UI/JAVASCRIPT: a validação de email acontece aqui no html
                 mas não depende das regras do modelo (pode haver divergência) -->
            <input type="email" name="email" required>
        </label>
        <label>Turma
            <input type="text" name="turma">
        </label>
        <label>Telefone
            <input type="text" name="telefone">
        </label>
    </div>
    <button type="submit">Cadastrar</button>
</form>

<?php if (isset($resultado) && isset($resultado['erro'])): ?>
    <div class="erro"><?php echo $resultado['erro']; ?></div>
<?php endif; ?>

<?php if (isset($resultado) && empty($resultado['erro'])): ?>
    <div class="sucesso">Aluno cadastrado com sucesso! (mensagem sempre aparece?)</div>
<?php endif; ?>

<h3>Lista de Alunos</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Turma</th>
            <th>Telefone</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($alunos as $a): ?>
        <tr>
            <td><?php echo $a['id']; ?></td>
            <!-- BUG DE SEGURANÇA: XSS - nome não é escapado na exibição -->
            <td><?php echo $a['nome']; ?></td>
            <td><?php echo $a['email']; ?></td>
            <td><?php echo $a['turma']; ?></td>
            <td><?php echo $a['telefone']; ?></td>
            <td>
                <a href="#" class="btn-excluir-aluno" data-id="<?php echo $a['id']; ?>">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div id="modal-exclusao" class="modal" style="display:none;">
    <div class="modal-conteudo">
        <p>Deseja excluir este aluno? Esta ação não pode ser desfeita.</p>
        <button id="confirmar-exclusao">Confirmar</button>
        <button id="cancelar-exclusao">Cancelar</button>
    </div>
</div>

<p class="dica">
    Dica para o aluno-tester: o botão "Excluir" usa JavaScript. Verifique se o
    modal aparece e se a exclusão funciona de verdade (caixa preta e cinza).
</p>

<?php include __DIR__ . '/footer.php'; ?>
