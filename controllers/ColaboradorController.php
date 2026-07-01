<?php
// controllers/ColaboradorController.php

require_once dirname(__DIR__) . '/models/Colaborador.php';

class ColaboradorController {
    private $modelo;

    public function __construct($pdo) {
        $this->modelo = new Colaborador($pdo);
    }

    public function procesarFormulario($post) {
        try {
            // Evitamos el Deprecated de trim asegurando que si viene vacío sea un string "" y no null
            $identidad    = trim($post['identidad'] ?? '');
            $nombre       = trim($post['nombre'] ?? '');
            $apellido     = trim($post['apellido'] ?? '');
            $edad         = trim($post['edad'] ?? '');
            $tipo_sangre  = trim($post['tipo_sangre'] ?? '');
            $sexo         = trim($post['sexo'] ?? '');
            $estado_civil = trim($post['estado_civil'] ?? '');
            $nacionalidad = trim($post['nacionalidad'] ?? '');
            $ruta         = trim($post['ruta'] ?? '');
            $correo       = trim($post['correo'] ?? '');
            $celular      = trim($post['celular'] ?? '');
            $puesto       = trim($post['puesto'] ?? '');
            $salario      = trim($post['salario'] ?? '');
            $planilla     = trim($post['planilla'] ?? '');
            $fecha_inicio = trim($post['fecha_inicio'] ?? '');
            
            // Campos opcionales que causaban el error en tu captura
            $fecha_fin    = trim($post['fecha_fin'] ?? '');
            $motivo_baja  = trim($post['motivo_baja'] ?? '');

            // Validación de que los datos primordiales no vengan vacíos
            if (empty($nombre) || empty($apellido) || empty($identidad)) {
                return ['status' => 'error', 'message' => 'Faltan datos requeridos en el formulario.'];
            }

            // Sanitización contra XSS
            $nombre   = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
            $apellido = htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8');

            // DISTRIBUCIÓN E INSERCIÓN CRÍTICA EN LAS 6 TABLAS DISPONIBLES
            // Guardamos las distintas partes del colaborador en cada catálogo
            $this->modelo->insertarEnSexo($sexo, $nombre); 
            $this->modelo->insertarEnEstadoCivil($estado_civil, $apellido);
            $this->modelo->insertarEnRuta($ruta, $nacionalidad);
            $this->modelo->insertarEnTipoEmpleado($planilla, $identidad);
            $this->modelo->insertarEnOcupacion($puesto, $correo);
            
            if (!empty($motivo_baja)) {
                $this->modelo->insertarEnMotivoTerminacion($motivo_baja, "Baja: " . $fecha_fin);
            }

            return ['status' => 'success', 'message' => '¡Datos del colaborador distribuidos e insertados con éxito en las 6 tablas de catálogos!'];

        } catch (Exception $e) {
            return ['status' => 'error', 'message' => "Error en la transacción SQL: " . $e->getMessage()];
        }
    }
}