<?php
session_start();

// Força o navegador a não salvar esta página no cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Data no passado

if (!isset($_SESSION['usuario'])) { 
    header('Location: login.php'); 
    exit; 
}
require 'conexao.php';

$total_motoristas = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM motoristas"))[0];
$total_veiculos   = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM veiculos"))[0];
$total_pendentes  = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM entregas WHERE status='Pendente'"))[0];
$total_em_rota    = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM entregas WHERE status='Em rota'"))[0];
$total_entregues  = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM entregas WHERE status='Entregue'"))[0];
$total_nao_entregues  = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM entregas WHERE status='Não entregue'"))[0];
$total_estabelecimento_fechado  = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(*) FROM entregas WHERE status='Estabelecimento Fechado'"))[0];

$entregas_recentes = mysqli_query($conexao, "
    SELECT e.*, m.nome AS motorista_nome, v.modelo AS veiculo_modelo
    FROM entregas e
    LEFT JOIN motoristas m ON e.motorista_cpf = m.cpf
    LEFT JOIN veiculos v ON e.veiculo_id = v.id
    ORDER BY e.created_at DESC LIMIT 5
");

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Sistema de Entregas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>

        <h4 class="mb-4 fw-bold">📊 Dashboard</h4>

        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-1">👤</div>
                        <div class="fs-3 fw-bold"><?= $total_motoristas ?></div>
                        <div class="text-muted small">Motoristas</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-1">🚛</div>
                        <div class="fs-3 fw-bold"><?= $total_veiculos ?></div>
                        <div class="text-muted small">Veículos</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-0 shadow-sm bg-warning bg-opacity-10">
                    <div class="card-body">
                        <div class="fs-1">⏳</div>
                        <div class="fs-3 fw-bold text-warning"><?= $total_pendentes ?></div>
                        <div class="text-muted small">Pendentes</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body">
                        <div class="fs-1">🚚</div>
                        <div class="fs-3 fw-bold text-primary"><?= $total_em_rota ?></div>
                        <div class="text-muted small">Em Rota</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body">
                        <div class="fs-1">✅</div>
                        <div class="fs-3 fw-bold text-success"><?= $total_entregues ?></div>
                        <div class="text-muted small">Entregues</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-0 shadow-sm bg-danger bg-opacity-10">
                    <div class="card-body">
                        <div class="fs-1">❌</div>
                        <div class="fs-3 fw-bold text-danger"><?= $total_nao_entregues ?></div>
                        <div class="text-muted small">Não Entregues</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">📦 Entregas Recentes</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Nome</th><th>Destino</th><th>Motorista</th><th>Veículo</th><th>Status</th><th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($e = mysqli_fetch_assoc($entregas_recentes)): ?>
                        <tr>
                            <td><code><?= $e['id'] ?></code></td>
                            <td><?= htmlspecialchars($e['nome']) ?></td>
                            <td><small><?= htmlspecialchars($e['destino']) ?></small></td>
                            <td><?= htmlspecialchars($e['motorista_nome'] ?? '-') ?></td>
                            <td><small><?= htmlspecialchars($e['veiculo_modelo'] ?? '-') ?></small></td>
                            <td>
                                <?php
                                $badge = ['Pendente'=>'warning','Em rota'=>'primary','Entregue'=>'success','Não entregue'=>'danger', 'Estabelecimento Fechado'=>'dark'];
                                $b = $badge[$e['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $b ?>"><?= $e['status'] ?></span>
                            </td>
                            <td>
                                <a href="entrega-view.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye-fill"></i></a>
                                <a href="entrega-edit.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi-pencil-fill"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                <a href="entregas.php" class="btn btn-dark btn-sm">Ver todas as entregas</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para bloquear o botão "Voltar" pós-logout -->
    <script>
        window.addEventListener('pageshow', function (event) {
            // Se o evento persistido for true, a página veio do cache do navegador
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Força o recarregamento da página para bater no PHP e ser redirecionado
                window.location.reload();
            }
        });
    </script>
</body>
</html>
