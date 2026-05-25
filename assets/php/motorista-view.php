<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';

$cpf = isset($_GET['cpf']) ? mysqli_real_escape_string($conexao, $_GET['cpf']) : '';
$query = mysqli_query($conexao, "SELECT m.*, v.modelo, v.categoria FROM motoristas m LEFT JOIN veiculos v ON m.veiculo_id = v.id WHERE m.cpf='$cpf'");
$m = mysqli_fetch_assoc($query);
if (!$m) { header('Location: motoristas.php'); exit; }

$entregas = mysqli_query($conexao, "SELECT * FROM entregas WHERE motorista_cpf='$cpf'");
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Motorista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">👤 <?= htmlspecialchars($m['nome']) ?>
                    <a href="motoristas.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>CPF:</strong><br><code><?= $m['cpf'] ?></code></div>
                    <div class="col-md-3"><strong>Idade:</strong><br><?= $m['idade'] ?> anos</div>
                    <div class="col-md-3"><strong>Veículo:</strong><br><?= htmlspecialchars($m['modelo'] ?? 'Sem veículo') ?></div>
                    <div class="col-md-3"><strong>Status:</strong><br>
                        <span class="badge bg-<?= $m['status'] === 'Em rota' ? 'primary' : 'secondary' ?>"><?= $m['status'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">📦 Entregas deste Motorista</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Nome</th><th>Destino</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($entregas) > 0):
                        foreach ($entregas as $e):
                            $badge = ['Pendente'=>'warning','Em rota'=>'primary','Entregue'=>'success'][$e['status']] ?? 'secondary';
                    ?>
                    <tr>
                        <td><code><?= $e['id'] ?></code></td>
                        <td><?= htmlspecialchars($e['nome']) ?></td>
                        <td><small><?= htmlspecialchars($e['destino']) ?></small></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= $e['status'] ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-3">Nenhuma entrega atribuída.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
