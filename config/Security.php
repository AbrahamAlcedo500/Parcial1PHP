<?php
// config/Security.php

class Security {
    // IV Parte - Punto 29: Sanitización de datos de texto plano
    public static function sanitizarTexto($texto) {
        $limpio = trim(strip_tags($texto));
        // IV Parte - Punto 30: Aplicar formato Tipo Título al guardar Nombres y Apellidos
        return mb_convert_case($limpio, MB_CASE_TITLE, "UTF-8");
    }

    public static function sanitizarEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    // IV Parte - Punto 28: Validación orientada a objetos (Edad >= 18)
    public static function validarEdad($edad) {
        $opciones = ["options" => ["min_range" => 18, "max_range" => 100]];
        return filter_var($edad, FILTER_VALIDATE_INT, $opciones) !== false;
    }
}