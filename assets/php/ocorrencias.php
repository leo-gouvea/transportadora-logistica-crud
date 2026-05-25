<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';
$ocorrencias = mysqli_query($conexao, "
    SELECT o.*, e.nome AS entrega_nome
    FROM ocorrencias o
    LEFT JOIN entregas e ON o.entrega_id = e.id
    ORDER BY o.data_ocorrencia DESC
");
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">⚠️ Todas as Ocorrências</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr><th>Data</th><th>Entrega</th><th>Descrição</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($ocorrencias) > 0):
                        foreach ($ocorrencias as $o): ?>
                    <tr>
                        <td><small><?= date('d/m/Y H:i', strtotime($o['data_ocorrencia'])) ?></small></td>
                        <td>
                            <a href="entrega-view.php?id=<?= $o['entrega_id'] ?>">
                                <code><?= $o['entrega_id'] ?></code><br>
                                <small><?= htmlspecialchars($o['entrega_nome']) ?></small>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($o['descricao']) ?></td>
                        <td>
                            <form action="acoes.php" method="POST" class="d-inline">
                                <button onclick="return confirm('Excluir ocorrência?')" type="submit" name="delete_ocorrencia" value="<?= $o['id'] ?>" class="btn btn-sm btn-outline-danger">🗑</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-4">Nenhuma ocorrência registrada.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
