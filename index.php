<?php
// index.php

require_once __DIR__ . '/config/Conexion.php';
require_once __DIR__ . '/models/Colaborador.php';
require_once __DIR__ . '/controllers/ColaboradorController.php';

// 1. Obtener la conexión Singleton
$pdo = Conexion::conectar();

// 2. Inicializar componentes
$modelo = new Colaborador($pdo);
$controller = new ColaboradorController($pdo);
$resultado = null;

// 3. Capturar envíos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->procesarFormulario($_POST);
}

// 4. Cargar dinámicamente los 6 catálogos
$listaEstadosCivil = $modelo->obtenerEstadosCiviles();
$listaMotivos      = $modelo->obtenerMotivosTerminacion();
$listaOcupaciones  = $modelo->obtenerOcupaciones();
$listaRutas        = $modelo->obtenerRutas();
$listaSexos        = $modelo->obtenerSexos();
$listaTiposEmpleado= $modelo->obtenerTiposEmpleado();

// 5. Renderizar vista
require_once __DIR__ . '/views/formulario.php';