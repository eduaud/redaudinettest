<?php
include_once 'autocompletador.class.php';
$usuario = new Autocompletador();
echo json_encode($usuario->buscarCliente($_GET['term']));
