<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conexao, "SELECT * FROM rotas WHERE id=$id");
$r = mysqli_fetch_assoc($query);
if (!$r) { header('Location: rotas.php'); exit; }

$entregas = mysqli_query($conexao, "
    SELECT e.*, m.nome AS motorista_nome
    FROM entregas e
    LEFT JOIN motoristas m ON e.motorista_cpf = m.cpf
    WHERE e.rota_id = $id
");
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Rota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">🗺️ Rota #<?= $r['id'] ?>
                    <a href="rotas.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="text-muted small">ORIGEM</label>
                        <p class="fw-bold mb-0"><?= htmlspecialchars($r['origem']) ?></p>
                    </div>
                    <div class="col-md-2 text-center">
                        <label class="text-muted small">DISTÂNCIA</label>
                        <p class="fw-bold mb-0"><?= $r['distancia_km'] ? number_format($r['distancia_km'], 1) . ' km' : '—' ?></p>
                    </div>
                    <div class="col-md-5">
                        <label class="text-muted small">DESTINO</label>
                        <p class="fw-bold mb-0"><?= htmlspecialchars($r['destino']) ?></p>
                    </div>
                    <?php if ($r['descricao']): ?>
                    <div class="col-md-12">
                        <label class="text-muted small">OBSERVAÇÕES</label>
                        <p class="mb-0"><?= htmlspecialchars($r['descricao']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">📦 Entregas nesta Rota</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Nome</th><th>Motorista</th><th>Destino</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($entregas) > 0):
                        foreach ($entregas as $e):
                            $badge = ['Pendente'=>'warning','Em rota'=>'primary','Entregue'=>'success'][$e['status']] ?? 'secondary';
                    ?>
                    <tr>
                        <td><a href="entrega-view.php?id=<?= $e['id'] ?>"><code><?= $e['id'] ?></code></a></td>
                        <td><?= htmlspecialchars($e['nome']) ?></td>
                        <td><?= htmlspecialchars($e['motorista_nome'] ?? '—') ?></td>
                        <td><small><?= htmlspecialchars($e['destino']) ?></small></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= $e['status'] ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center py-3">Nenhuma entrega nesta rota.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
