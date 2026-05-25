<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">🗺️ Rotas
                    <a href="rota-create.php" class="btn btn-dark btn-sm float-end">+ Adicionar</a>
                </h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Distância</th>
                            <th>Descrição</th>
                            <th>Entregas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $rotas = mysqli_query($conexao, "
                        SELECT r.*, COUNT(e.id) as total_entregas
                        FROM rotas r
                        LEFT JOIN entregas e ON e.rota_id = r.id
                        GROUP BY r.id
                        ORDER BY r.id DESC
                    ");
                    if (mysqli_num_rows($rotas) > 0):
                        foreach ($rotas as $r):
                    ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['origem']) ?></td>
                        <td><?= htmlspecialchars($r['destino']) ?></td>
                        <td><?= $r['distancia_km'] ? number_format($r['distancia_km'], 1) . ' km' : '—' ?></td>
                        <td><small><?= htmlspecialchars($r['descricao'] ?? '—') ?></small></td>
                        <td>
                            <span class="badge bg-secondary"><?= $r['total_entregas'] ?></span>
                        </td>
                        <td>
                            <a href="rota-view.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye-fill"></i></a>
                            <a href="rota-edit.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi-pencil-fill"></i></a>
                            <form action="acoes.php" method="POST" class="d-inline">
                                <button onclick="return confirm('Excluir rota?')" type="submit" name="delete_rota" value="<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="bi-trash3-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-4">Nenhuma rota cadastrada.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
