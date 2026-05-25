<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Data no passado

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';

$cpf = isset($_GET['cpf']) ? mysqli_real_escape_string($conexao, $_GET['cpf']) : '';
$query = mysqli_query($conexao, "SELECT * FROM motoristas WHERE cpf='$cpf'");
$m = mysqli_fetch_assoc($query);
if (!$m) {
    header('Location: motoristas.php');
    exit;
}

$veiculos = mysqli_query($conexao, "SELECT id, modelo, categoria FROM veiculos ORDER BY categoria");
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Motorista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">✏️ Editar Motorista
                    <a href="motoristas.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="acoes.php" method="POST">
                    <input type="hidden" name="cpf_original" value="<?= htmlspecialchars($m['cpf']) ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">CPF</label>
                            <input type="text" name="cpf" value="<?= htmlspecialchars($m['cpf']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" name="nome" value="<?= htmlspecialchars($m['nome']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Idade</label>
                            <input type="number" name="idade" value="<?= $m['idade'] ?>" class="form-control" min="18"
                                max="99">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Veículo</label>
                            <select name="veiculo_id" class="form-select">
                                <option value="">Sem veículo</option>
                                <?php foreach ($veiculos as $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= $m['veiculo_id'] == $v['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($v['modelo']) ?> (<?= $v['categoria'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <?php foreach (['Fora de serviço', 'Em rota', 'Disponível'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $m['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="update_motorista" class="btn btn-dark">💾 Salvar</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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