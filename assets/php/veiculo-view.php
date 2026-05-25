<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conexao, "SELECT * FROM veiculos WHERE id=$id");
$v = mysqli_fetch_assoc($query);
if (!$v) { header('Location: veiculos.php'); exit; }
$motorista = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT * FROM motoristas WHERE veiculo_id=$id"));
$entregas = mysqli_query($conexao, "SELECT * FROM entregas WHERE veiculo_id=$id");
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Veículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">🚛 <?= htmlspecialchars($v['modelo']) ?>
                    <a href="veiculos.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>Categoria:</strong><br><?= $v['categoria'] ?></div>
                    <div class="col-md-3"><strong>Capacidade:</strong><br><?= $v['capacidade_carga'] ?></div>
                    <div class="col-md-3"><strong>Última Checagem:</strong><br><?= $v['ultima_checagem'] ? date('d/m/Y', strtotime($v['ultima_checagem'])) : '-' ?></div>
                    <div class="col-md-3"><strong>Status:</strong><br>
                        <?php $badge=['disponivel'=>'success','em_rota'=>'primary','manutencao'=>'danger'][$v['status']]??'secondary'; ?>
                        <span class="badge bg-<?= $badge ?>"><?= $v['status'] ?></span>
                    </div>
                    <div class="col-md-12 mt-3"><strong>Localização:</strong><br><?= htmlspecialchars($v['localizacao'] ?? '-') ?></div>
                    <?php if ($motorista): ?>
                    <div class="col-md-12 mt-3"><strong>Motorista:</strong><br>
                        <a href="motorista-view.php?cpf=<?= urlencode($motorista['cpf']) ?>"><?= htmlspecialchars($motorista['nome']) ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">📦 Entregas neste Veículo</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark"><tr><th>ID</th><th>Nome</th><th>Destino</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if (mysqli_num_rows($entregas) > 0):
                        foreach ($entregas as $e):
                            $b=['Pendente'=>'warning','Em rota'=>'primary','Entregue'=>'success'][$e['status']]??'secondary'; ?>
                    <tr>
                        <td><code><?= $e['id'] ?></code></td>
                        <td><?= htmlspecialchars($e['nome']) ?></td>
                        <td><small><?= htmlspecialchars($e['destino']) ?></small></td>
                        <td><span class="badge bg-<?= $b ?>"><?= $e['status'] ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-3">Nenhuma entrega neste veículo.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
