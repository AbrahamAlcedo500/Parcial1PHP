<?php
require_once __DIR__ . '/../config/Conexion.php';

// 1. Conexión a la base de datos y obtención de los registros
$conexion = new Conexion();
$pdo = $conexion->conectar();

$sql = "SELECT id, identidad, nombre, apellido, correo, salario, fecha_inicio, firma_digital FROM colaboradores";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$colaboradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Colaboradores - Validación Asimétrica</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f7f6; padding: 40px; }
        .container { background: white; max-width: 1100px; margin: 0 auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.05); border-top: 6px solid #28a745; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; }
        .btn-volver { background: #0056b3; color: white; padding: 10px 16px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        .badge { padding: 6px 12px; border-radius: 50px; font-weight: bold; font-size: 0.85rem; display: inline-block; }
        .badge-integro { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-vulnerado { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>📋 Reporte de Personal y Verificación Dinámica OpenSSL</h2>
        <a href="../index.php" class="btn-volver">➕ Nuevo Registro</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Identidad</th>
                <th>Nombre Completo</th>
                <th>Correo Electrónico</th>
                <th>Salario</th>
                <th>Fecha Inicio</th>
                <th>Estado del Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($colaboradores)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No hay registros de colaboradores actualmente.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($colaboradores as $c): ?>
                    <?php
                    // Receta por defecto para evitar errores técnicos
                    $estadoFirma = 0; 

                    // 2. RECONSTRUIR LA RECETA: Cadena idéntica a la generada en el Controlador
                    $datosA_Verificar = $c['identidad'] . "," . $c['correo'] . "," . $c['salario'] . "," . $c['fecha_inicio'];

                    // 3. DESEMPAQUETAR LA DATA (Firma | Llave Pública)
                    if (!empty($c['firma_digital']) && strpos($c['firma_digital'], '|') !== false) {
                        
                        // Dividimos el contenido del campo por el pipe '|'
                        list($firmaBase64, $publicKeyBase64) = explode('|', $c['firma_digital']);
                        
                        // Decodificamos el Base64 para obtener los elementos originales
                        $firmaBinaria = base64_decode($firmaBase64);
                        $llavePublicaTexto = base64_decode($publicKeyBase64);

                        // Cargamos la llave pública recuperada en el recurso de OpenSSL
                        $publicKeyResource = openssl_pkey_get_public($llavePublicaTexto);

                        if ($publicKeyResource !== false) {
                            // Validación matemática: 1 = VÁLIDO (Íntegro), 0 = INVÁLIDO (Vulnerado)
                            $estadoFirma = openssl_verify($datosA_Verificar, $firmaBinaria, $publicKeyResource, OPENSSL_ALGO_SHA256);
                        }
                    }
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($c['identidad']); ?></strong></td>
                        <td><?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($c['correo']); ?></td>
                        <td>$<?php echo number_format($c['salario'], 2); ?></td>
                        <td><?php echo htmlspecialchars($c['fecha_inicio']); ?></td>
                        <td>
                            <?php if ($estadoFirma === 1): ?>
                                <span class="badge badge-integro">INTEGRO ✔️</span>
                            <?php else: ?>
                                <span class="badge badge-vulnerado">VULNERADO ❌</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>