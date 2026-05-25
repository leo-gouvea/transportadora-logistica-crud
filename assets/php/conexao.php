<?php
define('HOST', 'ec2-3-131-141-8.us-east-2.compute.amazonaws.com');
define('USUARIO', 'usr_3b_g4');
define('SENHA', 'g4B@123');
define('DB', 'ads_3b_grupo4');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar ao banco de dados.');
mysqli_set_charset($conexao, 'utf8');
?>
