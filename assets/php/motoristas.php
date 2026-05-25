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
    <title>Motoristas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">👤 Motoristas
                    <a href="motorista-create.php" class="btn btn-dark btn-sm float-end">+ Adicionar</a>
                </h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>CPF</th><th>Nome</th><th>Idade</th><th>Veículo</th><th>Status</th><th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT m.*, v.modelo as veiculo_modelo FROM motoristas m LEFT JOIN veiculos v ON m.veiculo_id = v.id";
                    $motoristas = mysqli_query($conexao, $sql);
                    if (mysqli_num_rows($motoristas) > 0):
                        foreach ($motoristas as $m):
                            $badge = $m['status'] === 'Em rota' ? 'primary' : 'secondary';
                    ?>
                    <tr>
                        <td><code><?= $m['cpf'] ?></code></td>
                        <td><?= htmlspecialchars($m['nome']) ?></td>
                        <td><?= $m['idade'] ?> anos</td>
                        <td><small><?= htmlspecialchars($m['veiculo_modelo'] ?? 'Sem veículo') ?></small></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= $m['status'] ?></span></td>
                        <td>
                            <a href="motorista-view.php?cpf=<?= urlencode($m['cpf']) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi-eye-fill"></i></a>
                            <a href="motorista-edit.php?cpf=<?= urlencode($m['cpf']) ?>" class="btn btn-sm btn-outline-success"><i class="bi-pencil-fill"></i></a>
                            <form action="acoes.php" method="POST" class="d-inline">
                                <button onclick="return confirm('Excluir motorista?')" type="submit" name="delete_motorista" value="<?= htmlspecialchars($m['cpf']) ?>" class="btn btn-sm btn-outline-danger"><i class="bi-trash3-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-4">Nenhum motorista encontrado.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
