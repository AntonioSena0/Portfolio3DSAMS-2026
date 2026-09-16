<?php
$tituloPagina = 'Livros';
include __DIR__ . '/header.php';
?>
<h2>Acervo de Livros</h2>

<form method="get" action="index.php" class="form-busca">
    <input type="hidden" name="acao" value="livros">
    <input type="text" name="q" value="<?php echo isset($termo) ? $termo : ''; ?>"
           placeholder="Buscar por título ou autor">
    <button type="submit">Buscar</button>
</form>

<form method="post" action="index.php?acao=livros" class="form-cadastro">
    <div class="campos">
        <label>Título
            <input type="text" name="titulo" required>
        </label>
        <label>Autor
            <input type="text" name="autor" required>
        </label>
        <label>Ano
            <input type="number" name="ano">
        </label>
        <label>Categoria
            <select name="categoria">
                <option value="Romance">Romance</option>
                <option value="Regional">Regional</option>
                <option value="Memórias">Memórias</option>
                <option value="Outro">Outro</option>
            </select>
        </label>
        <label>Quantidade
            <input type="number" name="quantidade" value="1" min="0">
        </label>
        <label>ISBN
            <input type="text" name="isbn">
        </label>
    </div>
    <button type="submit">Adicionar Livro</button>
</form>

<h3>Lista de Livros</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Ano</th>
            <th>Categoria</th>
            <th>Qtd</th>
            <th>Disponíveis</th>
            <th>ISBN</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($livros as $l): ?>
        <tr>
            <td><?php echo $l['id']; ?></td>
            <td><?php echo $l['titulo']; ?></td>
            <td><?php echo $l['autor']; ?></td>
            <td><?php echo $l['ano']; ?></td>
            <td><?php echo $l['categoria']; ?></td>
            <td><?php echo $l['quantidade']; ?></td>
            <td>
                <?php
                $livroObj = new Livro();
                echo $livroObj->quantidadeDisponivel($l['id']);
                ?>
            </td>
            <td><?php echo $l['isbn']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p class="dica">
    Dica para o aluno-tester: cadastre um livro com quantidade negativa e veja o
    que acontece. Tente também inserir caracteres especiais no título e observe a busca.
</p>

<?php include __DIR__ . '/footer.php'; ?>
