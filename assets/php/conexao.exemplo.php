<?php
define('HOST', 'seu-host-aqui');
define('USUARIO', 'seu-usuario-aqui');
define('SENHA', 'sua-senha-aqui');
define('DB', 'seu-banco-aqui');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar.');
mysqli_set_charset($conexao, 'utf8');
?>