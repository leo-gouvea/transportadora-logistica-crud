<?php
session_start();
require 'conexao.php';

// =============================================
// MOTORISTAS
// =============================================
if (isset($_POST['create_motorista'])) {
    $cpf   = mysqli_real_escape_string($conexao, trim($_POST['cpf']));
    $nome  = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $idade = (int)$_POST['idade'];
    $veiculo_id = !empty($_POST['veiculo_id']) ? (int)$_POST['veiculo_id'] : 'NULL';
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $vid = $veiculo_id === 'NULL' ? 'NULL' : $veiculo_id;
    $sql = "INSERT INTO motoristas (cpf, nome, idade, veiculo_id, status) VALUES ('$cpf','$nome',$idade,$vid,'$status')";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Motorista cadastrado!' : 'Erro ao cadastrar motorista.';
    header('Location: motoristas.php'); exit;
}

if (isset($_POST['update_motorista'])) {
    $cpf_original = mysqli_real_escape_string($conexao, $_POST['cpf_original']);
    $cpf   = mysqli_real_escape_string($conexao, trim($_POST['cpf']));
    $nome  = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $idade = (int)$_POST['idade'];
    $veiculo_id = !empty($_POST['veiculo_id']) ? (int)$_POST['veiculo_id'] : null;
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $vid = $veiculo_id ? $veiculo_id : 'NULL';
    $sql = "UPDATE motoristas SET cpf='$cpf', nome='$nome', idade=$idade, veiculo_id=$vid, status='$status' WHERE cpf='$cpf_original'";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) >= 0 ? 'Motorista atualizado!' : 'Erro ao atualizar.';
    header('Location: motoristas.php'); exit;
}

if (isset($_POST['delete_motorista'])) {
    $cpf = mysqli_real_escape_string($conexao, $_POST['delete_motorista']);
    mysqli_query($conexao, "DELETE FROM motoristas WHERE cpf='$cpf'");
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Motorista excluído!' : 'Erro ao excluir.';
    header('Location: motoristas.php'); exit;
}

// =============================================
// VEÍCULOS
// =============================================
if (isset($_POST['create_veiculo'])) {
    $id = (int)$_POST['id'];
    $modelo = mysqli_real_escape_string($conexao, trim($_POST['modelo']));
    $categoria = mysqli_real_escape_string($conexao, $_POST['categoria']);
    $capacidade = mysqli_real_escape_string($conexao, trim($_POST['capacidade_carga']));
    $checagem = mysqli_real_escape_string($conexao, $_POST['ultima_checagem']);
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $local = mysqli_real_escape_string($conexao, trim($_POST['localizacao']));
    $sql = "INSERT INTO veiculos (id, modelo, categoria, capacidade_carga, ultima_checagem, status, localizacao)
            VALUES ($id,'$modelo','$categoria','$capacidade','$checagem','$status','$local')";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Veículo cadastrado!' : 'Erro ao cadastrar veículo.';
    header('Location: veiculos.php'); exit;
}

if (isset($_POST['update_veiculo'])) {
    $id = (int)$_POST['id'];
    $modelo = mysqli_real_escape_string($conexao, trim($_POST['modelo']));
    $categoria = mysqli_real_escape_string($conexao, $_POST['categoria']);
    $capacidade = mysqli_real_escape_string($conexao, trim($_POST['capacidade_carga']));
    $checagem = mysqli_real_escape_string($conexao, $_POST['ultima_checagem']);
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $local = mysqli_real_escape_string($conexao, trim($_POST['localizacao']));
    $sql = "UPDATE veiculos SET modelo='$modelo', categoria='$categoria', capacidade_carga='$capacidade',
            ultima_checagem='$checagem', status='$status', localizacao='$local' WHERE id=$id";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = 'Veículo atualizado!';
    header('Location: veiculos.php'); exit;
}

if (isset($_POST['delete_veiculo'])) {
    $id = (int)$_POST['delete_veiculo'];
    mysqli_query($conexao, "DELETE FROM veiculos WHERE id=$id");
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Veículo excluído!' : 'Erro ao excluir.';
    header('Location: veiculos.php'); exit;
}

// =============================================
// ENTREGAS
// =============================================
if (isset($_POST['create_entrega'])) {
    $id = mysqli_real_escape_string($conexao, trim($_POST['id']));
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $segredo = (int)$_POST['segredo'];
    $peso = (float)$_POST['peso_kg'];
    $qtd = (int)$_POST['quantidade'];
    $destino = mysqli_real_escape_string($conexao, trim($_POST['destino']));
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $solicitante = mysqli_real_escape_string($conexao, trim($_POST['solicitante']));
    $dim = mysqli_real_escape_string($conexao, trim($_POST['dimensoes_cm_xyz']));
    $veiculo_id    = !empty($_POST['veiculo_id']) ? (int)$_POST['veiculo_id'] : 'NULL';
    $motorista_cpf = !empty($_POST['motorista_cpf']) ? "'" . mysqli_real_escape_string($conexao, $_POST['motorista_cpf']) . "'" : 'NULL';
    $rota_id       = !empty($_POST['rota_id']) ? (int)$_POST['rota_id'] : 'NULL';
    $sql = "INSERT INTO entregas (id, nome, descricao, segredo, peso_kg, quantidade, destino, status, solicitante, dimensoes_cm_xyz, veiculo_id, motorista_cpf, rota_id)
            VALUES ('$id','$nome','$descricao',$segredo,$peso,$qtd,'$destino','$status','$solicitante','$dim',$veiculo_id,$motorista_cpf,$rota_id)";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Entrega criada!' : 'Erro ao criar entrega.';
    header('Location: entregas.php'); exit;
}

if (isset($_POST['update_entrega'])) {
    $id = mysqli_real_escape_string($conexao, $_POST['id']);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $segredo = (int)$_POST['segredo'];
    $peso = (float)$_POST['peso_kg'];
    $qtd = (int)$_POST['quantidade'];
    $destino = mysqli_real_escape_string($conexao, trim($_POST['destino']));
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $solicitante = mysqli_real_escape_string($conexao, trim($_POST['solicitante']));
    $dim = mysqli_real_escape_string($conexao, trim($_POST['dimensoes_cm_xyz']));
    $veiculo_id    = !empty($_POST['veiculo_id']) ? (int)$_POST['veiculo_id'] : 'NULL';
    $motorista_cpf = !empty($_POST['motorista_cpf']) ? "'" . mysqli_real_escape_string($conexao, $_POST['motorista_cpf']) . "'" : 'NULL';
    $rota_id       = !empty($_POST['rota_id']) ? (int)$_POST['rota_id'] : 'NULL';
    $sql = "UPDATE entregas SET nome='$nome', descricao='$descricao', segredo=$segredo, peso_kg=$peso,
            quantidade=$qtd, destino='$destino', status='$status', solicitante='$solicitante',
            dimensoes_cm_xyz='$dim', veiculo_id=$veiculo_id, motorista_cpf=$motorista_cpf, rota_id=$rota_id
            WHERE id='$id'";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = 'Entrega atualizada!';
    header('Location: entregas.php'); exit;
}

if (isset($_POST['delete_entrega'])) {
    $id = mysqli_real_escape_string($conexao, $_POST['delete_entrega']);
    mysqli_query($conexao, "DELETE FROM entregas WHERE id='$id'");
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Entrega excluída!' : 'Erro ao excluir.';
    header('Location: entregas.php'); exit;
}

// =============================================
// OCORRÊNCIAS
// =============================================
if (isset($_POST['create_ocorrencia'])) {
    $entrega_id = mysqli_real_escape_string($conexao, $_POST['entrega_id']);
    $descricao  = mysqli_real_escape_string($conexao, trim($_POST['descricao_ocorrencia']));
    $sql = "INSERT INTO ocorrencias (entrega_id, descricao) VALUES ('$entrega_id','$descricao')";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Ocorrência registrada!' : 'Erro ao registrar.';
    header("Location: entrega-view.php?id=$entrega_id"); exit;
}

if (isset($_POST['delete_ocorrencia'])) {
    $id = (int)$_POST['delete_ocorrencia'];
    mysqli_query($conexao, "DELETE FROM ocorrencias WHERE id=$id");
    $_SESSION['mensagem'] = 'Ocorrência excluída!';
    header('Location: ocorrencias.php'); exit;
}

// =============================================
// ROTAS
// =============================================
if (isset($_POST['create_rota'])) {
    $origem      = mysqli_real_escape_string($conexao, trim($_POST['origem']));
    $destino     = mysqli_real_escape_string($conexao, trim($_POST['destino']));
    $distancia   = !empty($_POST['distancia_km']) ? (float)$_POST['distancia_km'] : 'NULL';
    $descricao   = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $dist_val    = $distancia === 'NULL' ? 'NULL' : $distancia;
    $sql = "INSERT INTO rotas (origem, destino, distancia_km, descricao) VALUES ('$origem','$destino',$dist_val,'$descricao')";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Rota cadastrada!' : 'Erro ao cadastrar rota.';
    header('Location: rotas.php'); exit;
}

if (isset($_POST['update_rota'])) {
    $id        = (int)$_POST['id'];
    $origem    = mysqli_real_escape_string($conexao, trim($_POST['origem']));
    $destino   = mysqli_real_escape_string($conexao, trim($_POST['destino']));
    $distancia = !empty($_POST['distancia_km']) ? (float)$_POST['distancia_km'] : 'NULL';
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));
    $dist_val  = $distancia === 'NULL' ? 'NULL' : $distancia;
    $sql = "UPDATE rotas SET origem='$origem', destino='$destino', distancia_km=$dist_val, descricao='$descricao' WHERE id=$id";
    mysqli_query($conexao, $sql);
    $_SESSION['mensagem'] = 'Rota atualizada!';
    header('Location: rotas.php'); exit;
}

if (isset($_POST['delete_rota'])) {
    $id = (int)$_POST['delete_rota'];
    mysqli_query($conexao, "DELETE FROM rotas WHERE id=$id");
    $_SESSION['mensagem'] = mysqli_affected_rows($conexao) > 0 ? 'Rota excluída!' : 'Erro ao excluir.';
    header('Location: rotas.php'); exit;
}

// Fallback
header('Location: index.php');
?>
