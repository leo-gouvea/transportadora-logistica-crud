<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
require 'conexao.php';
$id = isset($_GET['id']) ? mysqli_real_escape_string($conexao, $_GET['id']) : '';
$query = mysqli_query($conexao, "
    SELECT e.*, m.nome AS motorista_nome, v.modelo AS veiculo_modelo, v.localizacao AS veiculo_local
    FROM entregas e
    LEFT JOIN motoristas m ON e.motorista_cpf = m.cpf
    LEFT JOIN veiculos v ON e.veiculo_id = v.id
    WHERE e.id='$id'
");
$e = mysqli_fetch_assoc($query);
if (!$e) { header('Location: entregas.php'); exit; }
$ocorrencias = mysqli_query($conexao, "SELECT * FROM ocorrencias WHERE entrega_id='$id' ORDER BY data_ocorrencia DESC");
$badge = ['Pendente'=>'warning','Em rota'=>'primary','Entregue'=>'success'][$e['status']] ?? 'secondary';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrega <?= $e['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">📦 <?= ($e['segredo'] ? '🔒 ' : '') . htmlspecialchars($e['nome']) ?>
                    <span class="badge bg-<?= $badge ?> ms-2"><?= $e['status'] ?></span>
                </h4>
                <div>
                    <a href="entrega-edit.php?id=<?= $e['id'] ?>" class="btn btn-outline-success btn-sm">✏️ Editar</a>
                    <a href="entregas.php" class="btn btn-outline-secondary btn-sm">← Voltar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3"><strong>ID:</strong><br><code><?= $e['id'] ?></code></div>
                    <div class="col-md-3"><strong>Solicitante:</strong><br><?= htmlspecialchars($e['solicitante']) ?></div>
                    <div class="col-md-3"><strong>Peso:</strong><br><?= $e['peso_kg'] ?> kg</div>
                    <div class="col-md-3"><strong>Quantidade:</strong><br><?= $e['quantidade'] ?> un.</div>
                    <div class="col-md-6"><strong>Destino:</strong><br><?= htmlspecialchars($e['destino']) ?></div>
                    <div class="col-md-6"><strong>Dimensões (cm):</strong><br><?= htmlspecialchars($e['dimensoes_cm_xyz']) ?></div>
                    <div class="col-md-12"><strong>Descrição:</strong><br><?= htmlspecialchars($e['descricao']) ?></div>
                    <div class="col-md-6"><strong>Motorista:</strong><br>
                        <?php if ($e['motorista_nome']): ?>
                        <a href="motorista-view.php?cpf=<?= urlencode($e['motorista_cpf']) ?>"><?= htmlspecialchars($e['motorista_nome']) ?></a>
                        <?php else: ?> — <?php endif; ?>
                    </div>
                    <div class="col-md-6"><strong>Veículo:</strong><br>
                        <?php if ($e['veiculo_modelo']): ?>
                        <a href="veiculo-view.php?id=<?= $e['veiculo_id'] ?>"><?= htmlspecialchars($e['veiculo_modelo']) ?></a>
                        <br><small class="text-muted"><?= htmlspecialchars($e['veiculo_local']) ?></small>
                        <?php else: ?> — <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ocorrências -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">⚠️ Ocorrências desta Entrega</div>
            <div class="card-body">
                <form action="acoes.php" method="POST" class="mb-3">
                    <input type="hidden" name="entrega_id" value="<?= $e['id'] ?>">
                    <div class="input-group">
                        <textarea name="descricao_ocorrencia" class="form-control" rows="1" placeholder="Registrar nova ocorrência..." required></textarea>
                        <button type="submit" name="create_ocorrencia" class="btn btn-warning">Registrar</button>
                    </div>
                </form>
                <?php if (mysqli_num_rows($ocorrencias) > 0):
                    foreach ($ocorrencias as $o): ?>
                <div class="alert alert-warning py-2 mb-2">
                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($o['data_ocorrencia'])) ?></small><br>
                    <?= htmlspecialchars($o['descricao']) ?>
                </div>
                <?php endforeach; else: ?>
                <p class="text-muted mb-0">Nenhuma ocorrência registrada.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
