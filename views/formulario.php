<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contratación - iTECH</title>
    <style>
        /* ==========================================
           ESTILOS MODERNOS - CREADOS PARA EL PARCIAL
           ========================================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f7f6;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        /* Contenedor del Formulario */
        .container {
            background: #ffffff;
            max-width: 800px;
            width: 100%;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #0056b3;
            position: relative;
        }

        .header-acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        h2 {
            color: #0056b3;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        /* Botón de Excel Fino */
        .btn-excel {
            background-color: #1f7246;
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s ease;
            box-shadow: 0 4px 10px rgba(31, 114, 70, 0.2);
        }

        .btn-excel:hover {
            background-color: #13482c;
        }

        /* Grid del Formulario - Doble Columna */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            grid-column: span 2;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }

        /* Inputs y Selects */
        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 11px 14px;
            font-size: 0.95rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fafafa;
            transition: all 0.25s ease;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #0056b3;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.15);
        }

        /* Botón Guardar */
        .btn-guardar {
            width: 100%;
            padding: 14px;
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.2);
        }

        .btn-guardar:hover {
            background: #004085;
        }

        .btn-guardar:active {
            transform: scale(0.99);
        }

        /* Alertas de Feedback */
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            font-weight: 500;
            text-align: center;
        }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Tabla Escondida de apoyo para la exportación */
        #tabla-datos-exportar {
            display: none;
        }

        /* Celulares */
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .header-acciones { flex-direction: column; gap: 15px; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="header-acciones">
            <h2>Formulario de Contratación</h2>
            <button type="button" class="btn-excel" onclick="exportarExcel()">
                📊 Exportar a Excel
            </button>
        </div>

        <?php if (isset($resultado)): ?>
            <div class="alert alert-<?php echo $resultado['status']; ?>">
                <?php echo $resultado['message']; ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            
            <div class="form-grid">
                
                <div class="form-group">
                    <label for="identidad">Identidad (Cédula):</label>
                    <input type="text" id="identidad" name="identidad" required placeholder="Ej: 8-000-0000">
                </div>

                <div class="form-group">
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad" required min="18" placeholder="Ej: 25">
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ingresa nombres">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required placeholder="Ingresa apellidos">
                </div>

                <div class="form-group">
                    <label for="tipo_sangre">Tipo de Sangre:</label>
                    <input type="text" id="tipo_sangre" name="tipo_sangre" required placeholder="Ej: O+, A-">
                </div>

                <div class="form-group">
                    <label for="sexo">Sexo / Género:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="">-- Seleccione --</option>
                        <?php 
                        $renderSexos = isset($listaSexos) ? $listaSexos : (isset($listaSexo) ? $listaSexo : []);
                        foreach ($renderSexos as $s): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado_civil">Estado Civil:</label>
                    <select id="estado_civil" name="estado_civil" required>
                        <option value="">-- Seleccione --</option>
                        <?php 
                        $renderEstados = isset($listaEstadosCivil) ? $listaEstadosCivil : (isset($listaEstadoCivil) ? $listaEstadoCivil : []);
                        foreach ($renderEstados as $e): ?>
                            <option value="<?php echo $e['id']; ?>"><?php echo $e['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nacionalidad">Nacionalidad:</label>
                    <input type="text" id="nacionalidad" name="nacionalidad" required placeholder="Ej: Panameña">
                </div>

                <div class="form-group">
                    <label for="ruta">Ruta de Transporte:</label>
                    <select id="ruta" name="ruta" required>
                        <option value="">-- Seleccione --</option>
                        <?php 
                        $renderRutas = isset($listaRutas) ? $listaRutas : [];
                        foreach ($renderRutas as $r): ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo $r['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular" required placeholder="Ej: 6000-0000">
                </div>

                <div class="form-group full-width">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required placeholder="usuario@gmail.com">
                </div>

                <div class="form-group">
                    <label for="puesto">Puesto / Cargo (Ocupación):</label>
                    <select id="puesto" name="puesto" required>
                        <option value="">-- Seleccione --</option>
                        <?php 
                        $renderOcupaciones = isset($listaOcupaciones) ? $listaOcupaciones : (isset($listaOcupacion) ? $listaOcupacion : []);
                        foreach ($renderOcupaciones as $o): ?>
                            <option value="<?php echo $o['id']; ?>"><?php echo $o['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="salario">Salario Mensual:</label>
                    <input type="number" id="salario" name="salario" required step="0.01" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="planilla">Tipo de Planilla / Empleado:</label>
                    <select id="planilla" name="planilla" required>
                        <option value="">-- Seleccione --</option>
                        <?php 
                        $renderPlanilla = isset($listaTipoEmpleado) ? $listaTipoEmpleado : (isset($listaTiposEmpleado) ? $listaTiposEmpleado : []);
                        foreach ($renderPlanilla as $t): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo $t['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio Laboral:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin (Opcional):</label>
                    <input type="date" id="fecha_fin" name="fecha_fin">
                </div>

                <div class="form-group">
                    <label for="motivo_baja">Motivo de Baja (Opcional):</label>
                    <select id="motivo_baja" name="motivo_baja">
                        <option value="">-- Ninguno --</option>
                        <?php 
                        $renderMotivo = isset($listaMotivo) ? $listaMotivo : (isset($listaMotivos) ? $listaMotivos : []);
                        foreach ($renderMotivo as $m): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo $m['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn-guardar">Guardar y Distribuir Registro</button>
        </form>
    </div>

    <table id="tabla-datos-exportar">
        <thead>
            <tr>
                <th>Campo</th>
                <th>Valor Ingresado</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        function exportarExcel() {
            const tbody = document.querySelector("#tabla-datos-exportar tbody");
            tbody.innerHTML = ""; 

            const campos = [
                { label: 'Cédula / Identidad', id: 'identidad' },
                { label: 'Nombre', id: 'nombre' },
                { label: 'Apellido', id: 'apellido' },
                { label: 'Edad', id: 'edad' },
                { label: 'Tipo Sangre', id: 'tipo_sangre' },
                { label: 'Sexo (ID)', id: 'sexo' },
                { label: 'Estado Civil (ID)', id: 'estado_civil' },
                { label: 'Nacionalidad', id: 'nacionalidad' },
                { label: 'Ruta de Transporte (ID)', id: 'ruta' },
                { label: 'Celular', id: 'celular' },
                { label: 'Correo Electrónico', id: 'correo' },
                { label: 'Puesto (ID)', id: 'puesto' },
                { label: 'Salario', id: 'salario' },
                { label: 'Planilla / Tipo (ID)', id: 'planilla' },
                { label: 'Fecha Inicio', id: 'fecha_inicio' },
                { label: 'Fecha Fin', id: 'fecha_fin' },
                { label: 'Motivo Baja (ID)', id: 'motivo_baja' }
            ];

            campos.forEach(campo => {
                const elemento = document.getElementById(campo.id);
                let valor = '';
                
                if (elemento) {
                    if (elemento.tagName === 'SELECT') {
                        valor = elemento.options[elemento.selectedIndex].text;
                        if (valor.includes('--')) valor = 'No seleccionado';
                    } else {
                        valor = elemento.value;
                    }
                }
                
                const fila = document.createElement("tr");
                fila.innerHTML = `<td><b>${campo.label}</b></td><td>${valor}</td>`;
                tbody.appendChild(fila);
            });

            const tablaHTML = document.getElementById("tabla-datos-exportar").outerHTML;
            const nombreArchivo = "Contratacion_iTECH.xls";
            
            const metatags = '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
            const blob = new Blob([metatags + tablaHTML], { type: "application/vnd.ms-excel" });
            
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = nombreArchivo;
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>