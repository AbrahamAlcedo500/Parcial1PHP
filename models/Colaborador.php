<?php
// models/Colaborador.php

class Colaborador {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- MÉTODOS DE LECTURA (CORREGIDOS CON LLAMADO ESTRICTO A LA BASE DE DATOS) ---
    
    public function obtenerEstadosCiviles() {
        return $this->pdo->query("SELECT id, nombre AS Nombre FROM cat_estadocivil")->fetchAll();
    }

    public function obtenerMotivosTerminacion() {
        return $this->pdo->query("SELECT C_TERMINACION AS id, MOTIVO AS Nombre FROM cat_motivos_terminacion")->fetchAll();
    }

    public function obtenerOcupaciones() {
        return $this->pdo->query("SELECT C_OCUP AS id, OCUPACION AS Nombre FROM cat_ocupaciones")->fetchAll();
    }

    public function obtenerRutas() {
        return $this->pdo->query("SELECT id, Nombre FROM cat_rutas")->fetchAll();
    }

   // Corregido: Mapea la tabla con el nombre exacto del backup (con el espacio oculto de la profe)
    public function obtenerSexos() {
        // En su base de datos las columnas reales son 'id' y 'nombre' (en minúscula)
        return $this->pdo->query("SELECT id, nombre AS Nombre FROM ` cat_sexo`")->fetchAll();
    }

    public function obtenerTiposEmpleado() {
        return $this->pdo->query("SELECT id, Nombre FROM cat_tipoempleado")->fetchAll();
    }

   public function insertarColaborador($datos) {
        $sql = "INSERT INTO colaboradores (
                    identidad, nombre, apellido, edad, tipo_sangre, sexo_id, 
                    estado_civil_id, nacionalidad, ruta_id, celular, correo, 
                    puesto_id, salario, planilla_id, fecha_inicio, fecha_fin, motivo_baja_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($datos);
    }
}