<?php
session_start();
require 'conexao.php';

if (isset($_POST['login_usuario'])) {
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $senha = trim($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email = '$email' LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuarioDB = mysqli_fetch_assoc($resultado);
        if (password_verify($senha, $usuarioDB['senha'])) {
            $_SESSION['usuario'] = $usuarioDB['email'];
            $_SESSION['nome'] = $usuarioDB['nome'];
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['mensagem'] = 'Senha incorreta.';
        }
    } else {
        $_SESSION['mensagem'] = 'Usuário não encontrado.';
    }
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistema de Entregas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1a1a2e; min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.4); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-5">
                    <h3 class="text-center mb-1">🚚</h3>
                    <h4 class="text-center mb-4 fw-bold">Sistema de Entregas</h4>
                    <p class="text-center text-muted small mb-4">Turma 3B - Grupo 4</p>

                    <?php if (isset($_SESSION['mensagem'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="admin@transportadora.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Senha</label>
                            <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" name="login_usuario" class="btn btn-dark w-100 fw-bold">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
