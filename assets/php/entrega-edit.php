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
$id = isset($_GET['id']) ? mysqli_real_escape_string($conexao, $_GET['id']) : '';
$query = mysqli_query($conexao, "SELECT * FROM entregas WHERE id='$id'");
$e = mysqli_fetch_assoc($query);
if (!$e) {
    header('Location: entregas.php');
    exit;
}
$motoristas = mysqli_query($conexao, "SELECT cpf, nome FROM motoristas ORDER BY nome");
$veiculos = mysqli_query($conexao, "SELECT id, modelo FROM veiculos ORDER BY modelo");
$rotas = mysqli_query($conexao, "SELECT id, origem, destino FROM rotas ORDER BY id DESC");
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Entrega</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">✏️ Editar Entrega: <code><?= $e['id'] ?></code>
                    <a href="entregas.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="acoes.php" method="POST">
                    <input type="hidden" name="id" value="<?= $e['id'] ?>">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Nome do Item</label>
                            <input type="text" name="nome" value="<?= htmlspecialchars($e['nome']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <?php foreach (['Pendente', 'Em rota', 'Entregue', 'Não entregue', 'Estabelecimento Fechado'] as $s): ?>
                                    <option <?= $e['status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Descrição</label>
                            <textarea name="descricao" class="form-control"
                                rows="2"><?= htmlspecialchars($e['descricao']) ?></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Peso (kg)</label>
                            <input type="number" step="0.01" name="peso_kg" value="<?= $e['peso_kg'] ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Quantidade</label>
                            <input type="number" name="quantidade" value="<?= $e['quantidade'] ?>" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Dimensões (cm)</label>
                            <input type="text" name="dimensoes_cm_xyz"
                                value="<?= htmlspecialchars($e['dimensoes_cm_xyz']) ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Solicitante</label>
                            <input type="text" name="solicitante" value="<?= htmlspecialchars($e['solicitante']) ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Destino</label>
                            <input type="text" name="destino" value="<?= htmlspecialchars($e['destino']) ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Motorista</label>
                            <select name="motorista_cpf" class="form-select">
                                <option value="">Sem motorista</option>
                                <?php foreach ($motoristas as $m): ?>
                                    <option value="<?= htmlspecialchars($m['cpf']) ?>"
                                        <?= $e['motorista_cpf'] == $m['cpf'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Veículo</label>
                            <select name="veiculo_id" class="form-select">
                                <option value="">Sem veículo</option>
                                <?php foreach ($veiculos as $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= $e['veiculo_id'] == $v['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($v['modelo']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Rota</label>
                            <select name="rota_id" class="form-select">
                                <option value="">Sem rota</option>
                                <?php foreach ($rotas as $rt): ?>
                                    <option value="<?= $rt['id'] ?>" <?= $e['rota_id'] == $rt['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($rt['origem']) ?> → <?= htmlspecialchars($rt['destino']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Carga Sigilosa?</label>
                            <select name="segredo" class="form-select">
                                <option value="0" <?= !$e['segredo'] ? 'selected' : '' ?>>Não</option>
                                <option value="1" <?= $e['segredo'] ? 'selected' : '' ?>>Sim 🔒</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="update_entrega" class="btn btn-dark">💾 Salvar</button>
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