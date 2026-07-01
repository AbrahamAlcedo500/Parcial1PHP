<?php
require_once __DIR__ . '/../config/Conexion.php';

class ColaboradorController {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->conectar();
    }

    public function procesarFormulario($postData) {
        try {
            // 1. Recoger los datos del POST
            $identidad    = $postData['identidad'] ?? '';
            $edad         = $postData['edad'] ?? 0;
            $nombre       = $postData['nombre'] ?? '';
            $apellido     = $postData['apellido'] ?? '';
            $tipo_sangre  = $postData['tipo_sangre'] ?? '';
            $sexo         = $postData['sexo'] ?? null;
            $estado_civil = $postData['estado_civil'] ?? null;
            $nacionalidad = $postData['nacionalidad'] ?? '';
            $ruta         = $postData['ruta'] ?? null;
            $celular      = $postData['celular'] ?? '';
            $correo       = $postData['correo'] ?? '';
            $puesto       = $postData['puesto'] ?? null;
            $salario      = $postData['salario'] ?? 0.00;
            $planilla     = $postData['planilla'] ?? null;
            $fecha_inicio = $postData['fecha_inicio'] ?? '';
            $fecha_fin    = !empty($postData['fecha_fin']) ? $postData['fecha_fin'] : null;
            $motivo_baja  = !empty($postData['motivo_baja']) ? $postData['motivo_baja'] : null;

            // 2. LA RECETA: Cadena exacta solicitada para la firma digital
            $datosA_Firmar = $identidad . "," . $correo . "," . $salario . "," . $fecha_inicio;

            // 3. CONFIGURACIÓN DE OPENSSL PARA WINDOWS / WAMP64
            $configOpenSSL = array(
                "config" => "C:/wamp64/bin/php/php" . PHP_VERSION . "/extras/ssl/openssl.cnf",
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            );

            // Generar par de llaves en memoria
            $resKeys = openssl_pkey_new($configOpenSSL);

            // Intento alternativo por si la ruta de configuración varía en tu Wamp
            if (!$resKeys) {
                $configOpenSSL["config"] = "C:/wamp64/bin/apache/apache" . substr(apache_get_version(), 7, 6) . "/conf/openssl.cnf";
                $resKeys = openssl_pkey_new($configOpenSSL);
            }

            if (!$resKeys) {
                throw new Exception("No se pudo inicializar OpenSSL. Asegúrate de tener la extensión activada en Wamp.");
            }

            // Exportar la llave privada para efectuar la firma
            $privateKey = '';
            openssl_pkey_export($resKeys, $privateKey, null, $configOpenSSL);

            // 4. GENERAR LA FIRMA DIGITAL BINARIA Y PASAR A BASE64
            $firmaBinaria = '';
            openssl_sign($datosA_Firmar, $firmaBinaria, $privateKey, OPENSSL_ALGO_SHA256);
            $firmaBase64 = base64_encode($firmaBinaria);

            // 5. INSERTAR EN LA BASE DE DATOS (Sin la columna llave_publica)
            $sql = "INSERT INTO colaboradores (
                        identidad, edad, nombre, apellido, tipo_sangre, sexo_id, 
                        estado_civil_id, nacionalidad, ruta_id, celular, correo, 
                        puesto_id, salario, planilla_id, fecha_inicio, fecha_fin, 
                        motivo_baja_id, firma_digital
                    ) VALUES (
                        :identidad, :edad, :nombre, :apellido, :tipo_sangre, :sexo, 
                        :estado_civil, :nacionalidad, :ruta, :celular, :correo, 
                        :puesto, :salario, :planilla, :fecha_inicio, :fecha_fin, 
                        :motivo_baja, :firma
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':identidad'    => $identidad,
                ':edad'         => $edad,
                ':nombre'       => $nombre,
                ':apellido'     => $apellido,
                ':tipo_sangre'  => $tipo_sangre,
                ':sexo'         => $sexo,
                ':estado_civil' => $estado_civil,
                ':nacionalidad' => $nacionalidad,
                ':ruta'         => $ruta,
                ':celular'      => $celular,
                ':correo'       => $correo,
                ':puesto'       => $puesto,
                ':salario'      => $salario,
                ':planilla'     => $planilla,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_fin'    => $fecha_fin,
                ':motivo_baja'  => $motivo_baja,
                ':firma'        => $firmaBase64
            ]);

            return [
                'status' => 'success',
                'message' => '¡Registro guardado y firmado digitalmente con éxito!'
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error en el controlador: ' . $e->getMessage()
            ];
        }
    }
}