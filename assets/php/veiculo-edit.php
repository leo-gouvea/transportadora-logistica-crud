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
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = mysqli_query($conexao, "SELECT * FROM veiculos WHERE id=$id");
$v = mysqli_fetch_assoc($query);
if (!$v) {
    header('Location: veiculos.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Veículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">✏️ Editar Veículo
                    <a href="veiculos.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="acoes.php" method="POST">
                    <input type="hidden" name="id" value="<?= $v['id'] ?>">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Modelo</label>
                            <input type="text" name="modelo" value="<?= htmlspecialchars($v['modelo']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Categoria</label>
                            <select name="categoria" class="form-select">
                                <?php foreach (['Moto Utilitária', 'Caminhão Leve / Carro', 'Caminhão Médio', 'Caminhão Pesado', 'Caminhão Extra Pesado'] as $cat): ?>
                                    <option <?= $v['categoria'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Capacidade de Carga</label>
                            <input type="text" name="capacidade_carga"
                                value="<?= htmlspecialchars($v['capacidade_carga']) ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Última Checagem</label>
                            <input type="date" name="ultima_checagem" value="<?= $v['ultima_checagem'] ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <?php foreach (['disponivel' => 'Disponível', 'em_rota' => 'Em rota', 'manutencao' => 'Manutenção'] as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= $v['status'] == $val ? 'selected' : '' ?>><?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Localização</label>
                            <input type="text" name="localizacao" value="<?= htmlspecialchars($v['localizacao']) ?>"
                                class="form-control">
                        </div>
                    </div>
                    <button type="submit" name="update_veiculo" class="btn btn-dark">💾 Salvar</button>
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