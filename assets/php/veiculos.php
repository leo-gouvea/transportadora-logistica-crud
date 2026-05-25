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
    <title>Veículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">🚛 Veículos
                    <a href="veiculo-create.php" class="btn btn-dark btn-sm float-end">+ Adicionar</a>
                </h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Modelo</th><th>Categoria</th><th>Capacidade</th><th>Última Checagem</th><th>Status</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $veiculos = mysqli_query($conexao, "SELECT * FROM veiculos ORDER BY categoria");
                    if (mysqli_num_rows($veiculos) > 0):
                        foreach ($veiculos as $v):
                            $badge = ['disponivel'=>'success','em_rota'=>'primary','manutencao'=>'danger'][$v['status']] ?? 'secondary';
                            $label = ['disponivel'=>'Disponível','em_rota'=>'Em rota','manutencao'=>'Manutenção'][$v['status']] ?? $v['status'];
                            $alert = $v['ultima_checagem'] && strtotime($v['ultima_checagem']) < strtotime('+7 days') ? '⚠️ ' : '';
                    ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= htmlspecialchars($v['modelo']) ?></td>
                        <td><small><?= htmlspecialchars($v['categoria']) ?></small></td>
                        <td><?= htmlspecialchars($v['capacidade_carga']) ?></td>
                        <td><?= $alert . ($v['ultima_checagem'] ? date('d/m/Y', strtotime($v['ultima_checagem'])) : '-') ?></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= $label ?></span></td>
                        <td>
                            <a href="veiculo-view.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye-fill"></i></a>
                            <a href="veiculo-edit.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi-pencil-fill"></i></a>
                            <form action="acoes.php" method="POST" class="d-inline">
                                <button onclick="return confirm('Excluir veículo?')" type="submit" name="delete_veiculo" value="<?= $v['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="bi-trash3-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" class="text-center py-4">Nenhum veículo encontrado.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
