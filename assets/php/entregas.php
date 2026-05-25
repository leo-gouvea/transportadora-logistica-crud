<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';

$filtro_status = isset($_GET['status']) ? mysqli_real_escape_string($conexao, $_GET['status']) : '';
$where = $filtro_status ? "WHERE e.status='$filtro_status'" : '';
$entregas = mysqli_query($conexao, "
    SELECT e.*, m.nome AS motorista_nome, v.modelo AS veiculo_modelo
    FROM entregas e
    LEFT JOIN motoristas m ON e.motorista_cpf = m.cpf
    LEFT JOIN veiculos v ON e.veiculo_id = v.id
    $where
    ORDER BY e.created_at DESC
");
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entregas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="btn-group">
                <a href="entregas.php" class="btn btn-sm <?= !$filtro_status ? 'btn-dark' : 'btn-outline-dark' ?>">Todas</a>
                <a href="entregas.php?status=Pendente" class="btn btn-sm <?= $filtro_status=='Pendente' ? 'btn-warning' : 'btn-outline-warning' ?>">Pendentes</a>
                <a href="entregas.php?status=Em rota" class="btn btn-sm <?= $filtro_status=='Em rota' ? 'btn-primary' : 'btn-outline-primary' ?>">Em Rota</a>
                <a href="entregas.php?status=Entregue" class="btn btn-sm <?= $filtro_status=='Entregue' ? 'btn-success' : 'btn-outline-success' ?>">Entregues</a>
                <a href="entregas.php?status=Não entregue" class="btn btn-sm <?= $filtro_status=='Não entregue' ? 'btn-danger' : 'btn-outline-danger' ?>">Não Entregues</a>
                <a href="entregas.php?status=Estabelecimento Fechado" class="btn btn-sm <?= $filtro_status=='Estabelecimento Fechado' ? 'btn-dark' : 'btn-outline-dark' ?>">Estabelecimento Fechado</a>
            </div>
            <a href="entrega-create.php" class="btn btn-dark btn-sm">+ Nova Entrega</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">📦 Lista de Entregas</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Nome</th><th>Solicitante</th><th>Destino</th><th>Motorista</th><th>Veículo</th><th>Status</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($entregas) > 0):
                        foreach ($entregas as $e):
                            $badge = ['Pendente'=>'warning','Em rota'=>'primary','Entregue'=>'success', 'Não entregue'=>'danger', 'Estabelecimento Fechado'=>'dark'][$e['status']] ?? 'secondary';
                            $segredo = $e['segredo'] ? '🔒 ' : '';
                    ?>
                    <tr>
                        <td><code><?= $e['id'] ?></code></td>
                        <td><?= $segredo . htmlspecialchars($e['nome']) ?></td>
                        <td><small><?= htmlspecialchars($e['solicitante']) ?></small></td>
                        <td><small><?= htmlspecialchars($e['destino']) ?></small></td>
                        <td><small><?= htmlspecialchars($e['motorista_nome'] ?? '-') ?></small></td>
                        <td><small><?= htmlspecialchars($e['veiculo_modelo'] ?? '-') ?></small></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= $e['status'] ?></span></td>
                        <td>
                            <a href="entrega-view.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye-fill"></i></a>
                            <a href="entrega-edit.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi-pencil-fill"></i></a>
                            <form action="acoes.php" method="POST" class="d-inline">
                                <button onclick="return confirm('Excluir entrega?')" type="submit" name="delete_entrega" value="<?= $e['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="bi-trash3-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="8" class="text-center py-4">Nenhuma entrega encontrada.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
