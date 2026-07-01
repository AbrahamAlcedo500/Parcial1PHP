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

    // --- MÉTODOS DE INSERCIÓN DISTRIBUIDA (MANDAN LA DATA A LAS VISTAS/TABLAS DE LA PROFE) ---
    
    public function insertarEnSexo($id, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO ` cat_sexo` (id, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)");
        return $stmt->execute([$id, $nombre]);
    }

    public function insertarEnEstadoCivil($id, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO cat_estadocivil (id, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)");
        return $stmt->execute([$id, $nombre]);
    }

    public function insertarEnRuta($id, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO cat_rutas (id, Nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE Nombre = VALUES(Nombre)");
        return $stmt->execute([$id, $nombre]);
    }

    public function insertarEnTipoEmpleado($id, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO cat_tipoempleado (id, Nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE Nombre = VALUES(Nombre)");
        return $stmt->execute([$id, $nombre]);
    }

    public function insertarEnOcupacion($id, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO cat_ocupaciones (C_OCUP, OCUPACION) VALUES (?, ?) ON DUPLICATE KEY UPDATE OCUPACION = VALUES(OCUPACION)");
        return $stmt->execute([$id, $nombre]);
    }

    public function insertarEnMotivoTerminacion($id, $nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO cat_motivos_terminacion (C_TERMINACION, MOTIVO) VALUES (?, ?) ON DUPLICATE KEY UPDATE MOTIVO = VALUES(MOTIVO)");
        return $stmt->execute([$id, $nombre]);
    }
}