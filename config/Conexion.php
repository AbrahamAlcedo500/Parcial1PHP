<?php
// config/Database.php

class Conexion {
    private static $host = 'localhost';
    private static $db   = 'itech_contrataciones';
    private static $user = 'root';
    private static $pass = ''; 
    private static $charset = 'utf8mb4';
    private static $pdo  = null;

    public static function conectar() {
        // Aplicamos el patrón Singleton para no duplicar conexiones innecesariamente
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                
                self::$pdo = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                die("Error crítico de conexión en la clase Database: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}