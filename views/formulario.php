<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contratación - iTECH</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f7f6; color: #333; display: flex; justify-content: center; padding: 40px 20px; }
        .container { background: #ffffff; max-width: 800px; width: 100%; padding: 35px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); border-top: 6px solid #0056b3; }
        .header-acciones { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; }
        h2 { color: #0056b3; }
        .btn-excel { background-color: #1f7246; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; margin-bottom: 25px; }
        .form-group { display: flex; flex-direction: column; }
        .full-width { grid-column: span 2; }
        input, select { width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; }
        .btn-guardar { width: 100%; padding: 14px; background: #0056b3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-acciones">
        <h2>Formulario de Contratación</h2>
        <div style="display: flex; gap: 10px;">
            <a href="./views/reporte.php" style="text-decoration: none; background-color: #0056b3; color: white; padding: 10px 16px; font-size: 0.9rem; font-weight: 600; border-radius: 6px;">📋 Ver Reporte</a>
            <button type="button" class="btn-excel" onclick="exportarExcel()">📊 Exportar a Excel</button>
        </div>
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