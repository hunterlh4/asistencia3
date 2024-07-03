<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Importar extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['usuario_autenticado']) || ($_SESSION['usuario_autenticado'] != "true")) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {
        // ini_set('max_execution_time', '300'); // 300 segundos = 5 minutos
        // ini_set('memory_limit', '512M');
        // $max_execution_time = ini_get('max_execution_time');
        // $memory_limit = ini_get('memory_limit');
        $data['title'] = 'Importar';
        // $data1['tiempo']= $max_execution_time;
        // $data1['memoria'] =$memory_limit;
        $data1 = '';
        $this->views->getView('Administracion', "Importar", $data, $data1);
    }
    public function importar_nuevo($tipo)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['msg' => 'Método no permitido', 'icono' => 'error']);
            exit;
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['msg' => 'Datos inválidos', 'icono' => 'error']);
            exit;
        }
        foreach ($data as $key => $value) {
            // foreach ($data as $registro) {
            // $nombre = mb_convert_encoding($data['Nombre Usuario'], "ISO-8859-1");
            // $data['Nombre Usuario'] = str_replace('?', 'Ñ', $nombre);
            if (isset($value['Nombre']) && !empty($value['Nombre'])) {
                // Convertir la cadena del campo 'Nombre' a ISO-8859-1 y reemplazar caracteres incorrectos
                $nombre = mb_convert_encoding($value['Nombre'], 'ISO-8859-1', 'UTF-8');
                $nombre = str_replace('?', 'Ñ', $nombre); // Reemplazar caracteres incorrectos

                // Asignar el nuevo valor al campo 'Nombre'
                $data[$key]['Nombre'] = $nombre;
            }

        }
        echo json_encode($data);
        // echo json_encode(['msg' => 'Datos importados exitosamente', 'icono' => 'success']);

    }
    public function importar_trabajador_csv()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['msg' => 'Método no permitido', 'icono' => 'error']);
            exit;
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['msg' => 'Datos inválidos', 'icono' => 'error']);
            exit;
        }
        $aceptado = 0;
        $ignorado = 0;
        $total = count($data);
        $nombresAceptados = [];
        foreach ($data as $key => $registro) {

            $valido = true;

            $idUsuario = isset($registro['ID Usuario']) ? $registro['ID Usuario'] : '';
            $nombre = isset($registro['Nombre']) ? $registro['Nombre'] : '';
            $departamento = isset($registro['Departamento']) ? $registro['Departamento'] : '';
            $fechaInicio = isset($registro['Fecha Inicio']) ? $registro['Fecha Inicio'] : '';
            $fechaVencimiento = isset($registro['Fecha Vencimiento']) ? $registro['Fecha Vencimiento'] : '';

            if (!empty($departamento)) {
                if (
                    strpos($departamento, 'DIRESA/PASIVOS') !== false ||
                    strpos($departamento, 'DIRESA/PROYECTOS') !== false ||
                    strpos($departamento, 'RED SALUD') !== false
                ) {
                    $valido = false;
                    $ignorado++;
                }
            }
            // if (!$valido) {
            //     // $total++;
            //     $ignorado++;
            // }
            if ($valido && $idUsuario) {
                // $total++;
                $result = $this->model->getTrabajador($idUsuario);
                if (empty($result)) {
                    $modalidad_trabajo = 'Presencial';
                    $institucion = 'DIRESA';

                    if (!empty($nombre)) {
                        $nombre = mb_convert_encoding($nombre, 'ISO-8859-1', 'UTF-8');
                        $nombre = str_replace('?', 'Ñ', $nombre);
                        $nombre = preg_replace('/\s+/', ' ', $nombre); // Eliminar espacios adicionales

                        // $data[$key]['Nombre'] = $nombre;
                    }
                    // if (!empty($fechaInicio)) {
                    //     $timestamp = strtotime($fechaInicio);
                    //     $fechaInicio = date('Y-m-d', $timestamp);
                    //         // $data[$key]['Fecha Inicio'] = $fechaInicio;
                    // }
                    // if (!empty($fechaVencimiento)) {
                    //     $timestamp = strtotime($fechaVencimiento);
                    //     $fechaVencimiento = date('Y-m-d', $timestamp);
                    //         // $data[$key]['Fecha Vencimiento'] = $fechaVencimiento;
                    // }    
                    // registrarTrabajador
                    // $nombresAceptados[] =$idUsuario.'|'. $nombre .'|'.$departamento .'|'.$total;

                    // $aceptado++;
                    $result = $this->model->registrarTrabajador($nombre, $idUsuario, $institucion, $modalidad_trabajo);
                    if ($result > 0) {
                        $aceptado++;
                    } else {
                        $ignorado++;
                    }
                } else {
                    $ignorado++;
                }
            }
        }

        $respuesta = ['aceptado' => $aceptado, 'ignorado' => $ignorado, 'total' => $total];
        echo json_encode($respuesta);
    }

    public function importar_asistencia_csv()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['msg' => 'Método no permitido', 'icono' => 'error']);
            exit;
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['msg' => 'Datos inválidos', 'icono' => 'error']);
            exit;
        }
        $aceptado = 0;
        $ignorado = 0;
        $modificado = 0;
        $total = count($data);
        $registros = [];
        $posicion = 0;
        foreach ($data as $key => $registro) {
            $posicion++;
            $valido = true;
            $departamento = isset($registro['Departamento']) ? $registro['Departamento'] : '';
            //  $this->model->registrarAsistencia(
            if (!empty($departamento)) {
                if (
                    strpos($departamento, 'DIRESA/PASIVOS') !== false ||
                    strpos($departamento, 'DIRESA/PROYECTOS') !== false ||
                    strpos($departamento, 'RED SALUD') !== false
                ) {
                    $valido = false;
                    $ignorado++;
                }
            }

            if ($valido) {
                $fecha_csv = isset($registro['Fecha']) ? $registro['Fecha'] : '';
                $nombre = isset($registro['Nombre Usuario']) ? $registro['Nombre Usuario'] : '';
                $timestamp = strtotime(str_replace('/', '-', $fecha_csv));
                $fecha_csv = date('Y-m-d', $timestamp);
                $fecha_cumpleaños_csv = date('m-d', $timestamp);

                $idUsuario_csv = isset($registro['ID']) ? $registro['ID'] : '';

                $ES_1_csv = isset($registro['Entrada - Salida 1']) ? $registro['Entrada - Salida 1'] : '00:00';
                $ES_2_csv = isset($registro['Entrada - Salida 2']) ? $registro['Entrada - Salida 2'] : '00:00';
                $ES_3_csv = isset($registro['Entrada - Salida 3']) ? $registro['Entrada - Salida 3'] : '00:00';
                $ES_4_csv = isset($registro['Entrada - Salida 4']) ? $registro['Entrada - Salida 4'] : '00:00';
                $ES_5_csv = isset($registro['Entrada - Salida 5']) ? $registro['Entrada - Salida 5'] : '00:00';
                $ES_6_csv = isset($registro['Entrada - Salida 6']) ? $registro['Entrada - Salida 6'] : '00:00';
                $ES_7_csv = isset($registro['Entrada - Salida 7']) ? $registro['Entrada - Salida 7'] : '00:00';
                $ES_8_csv = isset($registro['Entrada - Salida 8']) ? $registro['Entrada - Salida 8'] : '00:00';
                // DATOS PARA INSERTAR
                $licencia = '';
                $tardanza = '00:00';
                $tardanza_cantidad = 0;
                $es_festividad = false;
                $es_honomastico = false;
                $entrada = '00:00';
                $salida = '00:00';
                $total_horario = '00:00';
                $total_entrada_salida_reloj = '00:00';

                $result = $this->model->getAllfestividad();
                $dia_csv = date('d', strtotime($fecha_csv));
                $mes_csv = date('m', strtotime($fecha_csv));
                for ($i = 0; $i < count($result); $i++) {

                    // $dia = $result[$i]['dia_inicio'];
                    $dia_festividad = str_pad($result[$i]['dia_inicio'], 2, '0', STR_PAD_LEFT);
                    $mes_festividad = str_pad($result[$i]['mes_inicio'], 2, '0', STR_PAD_LEFT);
                    if ($dia_csv == $dia_festividad && $mes_csv == $mes_festividad) {
                        $es_festividad = true;
                        break;
                    }
                }


                for ($i = 1; $i <= 8; $i++) {
                    $es_csv = isset($registro["Entrada - Salida $i"]) ? $registro["Entrada - Salida $i"] : '00:00';
                    if ($es_csv !== '00:00') {
                        if ($entrada === '00:00') {
                            $entrada = $es_csv;
                        }
                        $salida = $es_csv;
                    }
                }

                $result = $this->model->getTrabajador($idUsuario_csv);
                if (empty($result)) {
                    $ignorado++;
                }
                if ($result) {
                    $trabajador_id = $result['tid'];
                    $fecha_nacimiento_csv = isset($result['fecha_nacimiento']) ? $result['fecha_nacimiento'] : '3000-01-01';
                    // $timestamp = strtotime(str_replace('/', '-', $fecha_nacimiento));
                    $fecha_nacimiento = strtotime($fecha_nacimiento_csv);
                    // $mes_nacimiento = date('m', $fecha_nacimiento);
                    // $dia_nacimiento = date('d', $fecha_nacimiento);
                    $fecha_nacimiento = date('m-d', $fecha_nacimiento);

                    $hora_entrada_trabajador_formato = strtotime($result['hora_entrada']);
                    $hora_salida_trabajador_formato = strtotime($result['hora_salida']);

                    // $hora_entrada_trabajador = date('H:i', $hora_entrada_trabajador_formato);
                    // $hora_salida_trabajador = date('H:i', $hora_salida_trabajador_formato);

                    $entrada_trabajador_mas_6 = strtotime('+6 minutes', $hora_entrada_trabajador_formato);
                    $entrada_trabajador_mas_30 = strtotime('+31 minutes', $hora_entrada_trabajador_formato);

                    $entrada_trabajador_limite = strtotime('+5 minutes', $hora_entrada_trabajador_formato);

                    $entrada_timestamp = strtotime($entrada);
                    $salida_timestamp = strtotime($salida);
                    $diferencia_trabajador_segundos = $hora_salida_trabajador_formato - $hora_entrada_trabajador_formato;
                    $diferencia_entrada_salida_segundos = $salida_timestamp - $entrada_timestamp;

                    if ($ES_1_csv == '00:00') {
                        $licencia = 'SR';
                    }

                    if ($ES_1_csv !== '00:00') {

                        if ($salida == '00:00') {
                            $licencia = 'NMS';
                        }
                        if ($salida !== '00:00') {
                            if (strtotime($entrada) < $entrada_trabajador_mas_6) {
                                // Llegó menos de 6 minutos tarde  
                                $tardanza = '00:00';
                                $tardanza_cantidad = 0;
                            }
                            if (
                                strtotime($entrada) >= $entrada_trabajador_mas_6 &&
                                strtotime($entrada) < $entrada_trabajador_mas_30
                            ) {
                                // Llegó entre 6 y 30 minutos tarde
                                $tardanza_cantidad = 1;
                            }
                            if (strtotime($entrada) >= $entrada_trabajador_mas_30) {
                                // Llegó más de 30 minutos tarde
                                $tardanza_cantidad = 1;
                                $licencia = '+30';
                            }
                            if (strtotime($entrada) >= $hora_salida_trabajador_formato) {
                                $licencia = 'NME';
                            }

                            if (strtotime($entrada) < $entrada_trabajador_mas_30 && strtotime($salida) >= $hora_salida_trabajador_formato) {
                                $licencia = 'OK';
                                $total_horario = gmdate('H:i', $diferencia_trabajador_segundos);
                                $total_entrada_salida_reloj = gmdate('H:i', $diferencia_entrada_salida_segundos);
                            }
                            if (strtotime($entrada) < $entrada_trabajador_mas_30 && strtotime($salida) < $hora_salida_trabajador_formato) {
                                $licencia = 'NMS';
                            }
                        }
                    }
                    if ($ES_1_csv !== '00:00' && $licencia == 'OK' && ($entrada_timestamp >= $entrada_trabajador_mas_6 &&
                        $entrada_timestamp < $entrada_trabajador_mas_30)) {

                        $diferencia_tardanza = $entrada_timestamp - $entrada_trabajador_limite;
                        $tardanza = gmdate('H:i', $diferencia_tardanza);
                    }

                    if ($es_festividad == true) {
                        $licencia = 'FERIADO';
                    }
                    if (($fecha_nacimiento == $fecha_cumpleaños_csv) && ($fecha_nacimiento_csv !== '3000-01-01')) {
                        $licencia = 'HONOMASTICO';
                    }
                    $result = $this->model->getAsistencia($idUsuario_csv, $fecha_csv);
                    $justificacion = '';
                    // $registros[] = [$result];
                    if (empty($result)) {
                        // REGISTRO ASISTENCIA
                        $aceptado++;
                        $this->model->registrarAsistencia($trabajador_id, $licencia, $fecha_csv, $entrada, $salida, $tardanza, $tardanza_cantidad, $total_entrada_salida_reloj, $total_horario, $justificacion, $ES_1_csv, $ES_2_csv, $ES_3_csv, $ES_4_csv, $ES_5_csv, $ES_6_csv, $ES_7_csv, $ES_8_csv);
                    } else {
                        $asistencia_id = $result['aid'];
                        // ACTUALIZO ASISTENCIA
                        $modificado++;
                        $this->model->modificarAsistencia($trabajador_id, $licencia, $fecha_csv, $entrada, $salida, $tardanza, $tardanza_cantidad, $total_entrada_salida_reloj, $total_horario, $justificacion, $ES_1_csv, $ES_2_csv, $ES_3_csv, $ES_4_csv, $ES_5_csv, $ES_6_csv, $ES_7_csv, $ES_8_csv, $asistencia_id);
                    }

                    // $registros[] = [
                    //     // 'honomastico' => $fecha_nacimiento,
                    //     'honosmastico' => $fecha_nacimiento.'|'.$fecha_cumpleaños_csv,
                    //     'festividad' => $es_festividad,
                    //     'posicion' => $posicion,
                    //     'idUsuario_csv' => $idUsuario_csv,
                    //     'departamento' => $departamento,
                    //     'fecha_csv' => $fecha_csv,
                    //     'nombre' => $nombre,
                    //     'entrada' => $entrada,
                    //     'salida' => $salida,
                    //     'licencia' => $licencia,
                    //     'tardanza' => $tardanza,
                    //     'tardanza_cantidad' => $tardanza_cantidad,
                    //     'total' => $total_horario,
                    //     'total_reloj' => $total_entrada_salida_reloj,
                    //     'ES_1_csv' => $ES_1_csv,
                    //     'ES_2_csv' => $ES_2_csv,
                    //     'ES_3_csv' => $ES_3_csv,
                    //     'ES_4_csv' => $ES_4_csv,
                    //     'ES_5_csv' => $ES_5_csv,
                    //     'ES_6_csv' => $ES_6_csv,
                    //     'ES_7_csv' => $ES_7_csv,
                    //     'ES_8_csv' => $ES_8_csv,
                    // ];

                }
            }
        }

        $respuesta = ['aceptado' => $aceptado, 'modificado' => $modificado, 'ignorado' => $ignorado, 'total' => $total];
        echo json_encode($respuesta);
    }



    function compararEncabezados($encabezadoRecibido, $encabezadoEsperado)
    {
        // Convertir ambos encabezados a minúsculas y quitar espacios en blanco
        $encabezadoRecibido = array_map('strtolower', array_map('trim', $encabezadoRecibido));
        $encabezadoEsperado = array_map('strtolower', array_map('trim', $encabezadoEsperado));

        // Comparar los encabezados normalizados
        return array_diff($encabezadoRecibido, $encabezadoEsperado) === array_diff($encabezadoEsperado, $encabezadoRecibido);
    }

    public function validar_archivo()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['msg' => 'Método no permitido', 'validado' => false]);
            exit;
        }

        $archivo = $_FILES['archivo']; // Obtener el archivo enviado
        $nombreArchivo = $archivo['name'];
        $tipoArchivo = $archivo['type'];

        // Validar que sea un archivo CSV o Excel
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        if ($extension == 'xls') {
            echo json_encode(['msg' => 'Modifique el archivo a XLSX', 'validado' => false]);
            exit;
        }
        if (!in_array($extension, ['csv', 'xlsx'])) {
            echo json_encode(['msg' => 'Tipo de archivo no válido', 'validado' => false]);
            exit;
        }

        // Leer el encabezado del archivo enviado
        $encabezado = json_decode($_POST['encabezado'], true);
        // if($extension =='xls'){

        // }

        $tiposEncabezado = [
            'asistencia_csv' => ["Fecha", "ID", "Nombre Usuario", "Departamento", "Entrada - Salida 1", "Entrada - Salida 2", "Entrada - Salida 3", "Entrada - Salida 4", "Entrada - Salida 5", "Entrada - Salida 6", "Entrada - Salida 7", "Entrada - Salida 8", "Descanso", "Tiempo Trabajado"],
            'usuario_csv' => ["ID Usuario", "Nombre", "Departamento", "Correo", "Tel�fono", "Fecha Inicio", "Fecha Vencimiento", "Nivel Admin.", "Modo Autenticaci�n", "N�mero de Template", "Grupo de Acceso1", "Grupo de Acceso2", "Grupo de Acceso3", "Grupo de Acceso4", "N�mero Tarjeta", "Bypass", "Title", "Mobile", "Gender", "Date of Birth"],
            'frontera_samu_1' => ["Dpto.", "Nombre", "No.", "Fecha/Hora", "Locación ID", "ID Número", "VerificaCod", "No.tarjeta"],
            'frontera_samu_2' => ["AC No.", "Nombre", "Dpto.", "Fecha", "Hora"],
            // 'tipo5' => ["ColumnaA", "ColumnaB", "ColumnaC", "ColumnaD"],

        ];
        $tipoValidado = false;
        $tipoArchivoValidado = '';

        foreach ($tiposEncabezado as $tipo => $esperado) {
            // Comparar encabezados ignorando diferencias de codificación y mayúsculas/minúsculas
            if ($this->compararEncabezados($encabezado, $esperado)) {
                $tipoValidado = true;
                $tipoArchivoValidado = $tipo;
                break;
            }
        }

        $datosComparados = [
            'encabezado_recibido' => $encabezado,
            'encabezados_esperados' => $tiposEncabezado,
        ];

        if ($tipoArchivoValidado == 'frontera_samu_1' || $tipoArchivoValidado == 'frontera_samu_2') {
            $fila1 = json_decode($_POST['fila_1'], true);
            // $tipoValidado = false;
            $tipo_fila = '';
            if (in_array("FRONTERA", $fila1)) {
                $tipo_fila = 'FRONTERA';
            }
            if (in_array("SAMU2023", $fila1)) {
                $tipo_fila = 'SAMU2023';
            }
            if ($tipoArchivoValidado == 'frontera_samu_1' && $tipo_fila == 'FRONTERA') {

                $tipoArchivoValidado = 'frontera_1';
            }
            if ($tipoArchivoValidado == 'frontera_samu_2' && $tipo_fila == 'FRONTERA') {
                $tipoArchivoValidado = 'frontera_2';
                // Aquí puedes agregar la lógica específica para 'frontera_samu_1'
            }

            if ($tipoArchivoValidado == 'frontera_samu_1' && $tipo_fila == 'SAMU2023') {
                $tipoArchivoValidado = 'samu_1';
                // Aquí puedes agregar la lógica específica para 'frontera_samu_2'
            }
            if ($tipoArchivoValidado == 'frontera_samu_2' && $tipo_fila == 'SAMU2023') {
                $tipoArchivoValidado = 'samu_2';
                // Aquí puedes agregar la lógica específica para 'frontera_samu_2'
            }
        }


        if (!$tipoValidado) {
            // echo json_encode($tipoArchivoValidado);
            echo json_encode(['msg' => 'Encabezado no valido', 'validado' => false]);
            exit;
        }

        echo json_encode(['msg' => 'Archivo valido', 'validado' => true, 'tipo' => $tipoArchivoValidado,  'datos' => $encabezado]);
    }

    public function importar()
    {

        $mensaje = '';
        $icono = '';
        $tamaño = '';
        $cantidad_ignorados = 0;
        $cantidad_registrado = 0;
        $cantidad_actualizado = 0;
        $cantidad_bug = 0;
        $cantidad_registros = 0;
        $fechaFormateada = null;



        $aviso = '';
        $id = NULL;
        $hora_marcada = null;
        $tardanza_cantidad = 0;
        $cantidad_departamento_mal = 0;
        $cantidad_departamento_bien = 0;

        $diasFestivo = $this->model->findAllFestividad();

        if (isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];
            // Verifica si no hay errores al subir el archivo
            if ($archivo['error'] === UPLOAD_ERR_OK) {
                // Obtiene la extensión del archivo
                $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                $archivo =  $_FILES['archivo']['tmp_name'];
                // Verifica si la extensión es CSV o XLSX
                switch ($extension) {
                    case 'csv':
                        $file = fopen($archivo, 'r');
                        if ($file) {

                            fgets($file);
                            while (($linea = fgets($file)) !== false) {
                                $bandera = '';
                                $valores_separados = str_getcsv($linea, ',');

                                $tamaño_1 = count($valores_separados);
                                $cantidad_registros++;
                                // TRABAJADORES
                                if ($tamaño_1 == 20) {
                                    // $valores_separados[0];ID USUARIO
                                    // $valores_separados[1];NOMBRE
                                    // $valores_separados[2];DEPARTAMENTO
                                    $institucion = 'DIRESA';
                                    if ($valores_separados[3] == 'RED SALUD') {
                                        $institucion = 'RED SALUD';
                                    }
                                    $result = $this->model->getTrabajador($valores_separados[0]);
                                    if (empty($result)) {
                                        $nombre = mb_convert_encoding($valores_separados[1], "ISO-8859-1");
                                        // $str = str_replace('�', '1', $str);
                                        $nombre = str_replace('?', 'Ñ', $nombre);
                                        $modalidad_trabajo = 'Presencial';
                                        $result = $this->model->registrarTrabajador($nombre, $valores_separados[0], $institucion, $modalidad_trabajo);
                                        if ($result > 0) {
                                            $cantidad_registrado++;
                                        } else {
                                            $cantidad_ignorados++;
                                        }
                                    } else {
                                        $cantidad_ignorados++;
                                    }
                                }
                                // ASISTENCIAS
                                if ($tamaño_1 == 14) {
                                    // $valores_separados[0] Fecha	
                                    // $valores_separados[1]  ID	
                                    // $valores_separados[2] Nombre 
                                    // $valores_separados[3] Departamento	
                                    // $valores_separados[4] Entrada - Salida 1	
                                    // $valores_separados[5] Entrada - Salida 2	
                                    // $valores_separados[6] Entrada - Salida 3	
                                    // $valores_separados[7]  Entrada - Salida 4	
                                    // $valores_separados[8] Entrada - Salida 5	
                                    // $valores_separados[9] Entrada - Salida 6	
                                    // $valores_separados[10] Entrada - Salida 7	
                                    // $valores_separados[11] Entrada - Salida 8
                                    if (
                                        $valores_separados[3] == 'DIRESA/PASIVOS ' ||
                                        $valores_separados[3] == 'DIRESA/PASIVOS /CESADOS DESIGNA' ||
                                        // $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DIRESA'||
                                        $valores_separados[3] == 'DIRESA/PASIVOS /CESADOS DIRESA' ||
                                        $valores_separados[3] == 'DIRESA/PASIVOS /CESADOS PRACTICANTES'
                                    ) {
                                        $cantidad_ignorados++;
                                    } else if (
                                        $valores_separados[3] == 'DIRESA/276' ||
                                        $valores_separados[3] == 'DIRESA/CONTRALORIA' ||
                                        $valores_separados[3] == 'DIRESA/CONTRATADO REGUL' ||
                                        $valores_separados[3] == 'DIRESA/CONTRATADO TEMPORAL' ||
                                        $valores_separados[3] == 'DIRESA/CONTRATO COVID A CAS' ||
                                        $valores_separados[3] == 'DIRESA/DESIGNADOS' ||
                                        $valores_separados[3] == 'DIRESA/DESTACADOS' ||
                                        $valores_separados[3] == 'DIRESA/NOMBRADO' ||
                                        // $valores_separados[3]=='DIRESA/NOMBRADO'||

                                        $valores_separados[3] == 'DIRESA/PRACTICANTES' ||
                                        $valores_separados[3] == 'DIRESA/PROGRAMA_CANCER_DESA' ||
                                        $valores_separados[3] == 'DIRESA/PROYECTOS' ||
                                        $valores_separados[3] == 'DIRESA/REPUEST JUD' ||
                                        $valores_separados[3] == 'RED SALUD'
                                    ) {

                                        $cantidad_departamento_bien++;
                                        // CALCULO PARA LA ASISTENCIA
                                        $fechaString = $valores_separados[0];
                                        $Telefono_id_trabajador = $valores_separados[1];
                                        $nombre = $valores_separados[2];
                                        // $timestamp = strtotime($fechaString);
                                        // $fecha_0 = date('Y-m-d', $timestamp);
                                        $partesFecha = explode('/', $fechaString);
                                        $dia = str_pad($partesFecha[0], 2, '0', STR_PAD_LEFT);
                                        $mes = $partesFecha[1];
                                        $año = $partesFecha[2];
                                        $fecha_0 = sprintf('%04d-%02d-%02d', $año, $mes, $dia);
                                        $reloj_1 = $valores_separados[4];
                                        $reloj_2 = $valores_separados[5];
                                        $reloj_3 = $valores_separados[6];
                                        $reloj_4 = $valores_separados[7];
                                        $reloj_5 = $valores_separados[8];
                                        $reloj_6 = $valores_separados[9];
                                        $reloj_7 = $valores_separados[10];
                                        $reloj_8 = $valores_separados[11];


                                        $entrada = '00:00';
                                        $salida = '00:00';
                                        $licencia = 'prueba';

                                        // RECORRO LAS MARCADAS PARA OBTENER ENTRADA Y SALIDA
                                        for ($i = 4; $i <= 11; $i++) {
                                            // Verifica si la marcación es diferente de "00:00"
                                            if ($valores_separados[$i] !== '00:00') {
                                                // Si aún no se ha establecido la hora de entrada, asigna la marcación actual como la hora de entrada
                                                if ($entrada === '00:00') {
                                                    $entrada = $valores_separados[$i];
                                                }
                                                // Asigna cada marcación diferente de "00:00" como la hora de salida hasta que no haya más
                                                // $salida = $valores_separados[$i];
                                                // if ($salida ==$entrada){
                                                //     $salida ='00:00';
                                                // }
                                                if ($valores_separados[$i] !== $entrada) {
                                                    $salida = $valores_separados[$i];
                                                }
                                            }
                                        }
                                        $hora_marcada = new DateTime($entrada);
                                        $salida_marcada = new DateTime($salida);
                                        //  VALIDAR SI EXISTE TRABAJADOR, EN CASO NO, SE IGNORA ASISTENCIA

                                        $result = $this->model->getTrabajador($Telefono_id_trabajador);
                                        if (empty($result)) {
                                            $cantidad_ignorados++;
                                            $aviso = $aviso . $valores_separados[1];
                                            $bandera = 'no existe trabajador';
                                            // BIEN;
                                        } else {
                                            // $trabajador_id =$result['id'];
                                            // $horario_id=$result['horario_id'];
                                            // $result =$this->model->gethorarioDetalle($horario_id);
                                            // // VALIDARSI TIENE UN HORARIO
                                            // if(empty($result)){
                                            //     $cantidad_ignorados++;
                                            //     $bandera = 'no existe horario';
                                            //     // BIEN
                                            // }else{
                                            // SIES QUE FUNCIONA ACTIVAR ARRIBA

                                            // $entrada_horario= $result['hora_entrada'];
                                            // $salida_horario = $result['hora_salida'];

                                            // VALIDAR SI HAY UN HORARIO PROGRAMADO
                                            // $result =$this->model->getProgramacion($fecha_0,$trabajador_id);
                                            // if(empty($result)){HORARIO PROGRAMADO}else{ HORARIO NORMAL}
                                            // $entrada_PROGRAM = new DateTime($result['hora_entrada']); programada
                                            // $salida_horario = new DateTime($result['hora_salida']); programada
                                            //EN CASO TENGA SE MARCARA ESE HORARIO COMO LA ENTRADA Y LA SALIDA NUEVA
                                            $trabajador_id = $result['tid'];
                                            $entrada_horario = new DateTime($result['hora_entrada']);
                                            $salida_horario = new DateTime($result['hora_salida']);
                                            $total = new DateTime($result['total']);

                                            $total_reloj = '00:00';
                                            $entrada_horario_string = $entrada_horario->format('H:i');
                                            $salida_horario_string = $salida_horario->format('H:i');
                                            $total_string = $total->format('H:i');
                                            // $salida_horario

                                            // primer validador +5
                                            $normal  = clone $entrada_horario;
                                            // $horaSalidaModificada = clone $salida_horario;

                                            // Validador +5 minutos
                                            $normal->modify('+5 minutes');

                                            // Validador +6 minutos (tardanza 1)
                                            $tardanza1a  = clone $entrada_horario;
                                            $tardanza1a->modify('+6 minutes');

                                            $tardanza1b  = clone $tardanza1a;
                                            $tardanza1b->modify('+9 minutes');

                                            // Validador +16 minutos (tardanza 2)
                                            $tardanza2a = clone $entrada_horario;
                                            $tardanza2a->modify('+16 minutes');

                                            $tardanza2b  = clone $tardanza2a;
                                            $tardanza2b->modify('+9 minutes');

                                            // Validador +30 minutos (tardanza 3)
                                            $tardanza3a  = clone $entrada_horario;
                                            $tardanza3a->modify('+26 minutes');

                                            $tardanza3b = clone $tardanza2a;
                                            $tardanza3b->modify('+14 minutes');
                                            $tardanza_cantidad = 0;

                                            $tardanza = '00:00';

                                            $entrada_marcada_segundos = $hora_marcada->getTimestamp();
                                            $entrada_horario_Segundos = $normal->getTimestamp();
                                            $salida_marcada_segundos = $salida_marcada->getTimestamp();

                                            if ($entrada == '00:00') {
                                                $licencia = 'SR';
                                                $entrada = '00:00';
                                                // $tardanza='00:00';
                                                // $tardanza='00:00';

                                            } else {

                                                // $hora_entrada = strtotime($entrada);
                                                // $hora_marcada = date('H:i', $hora_entrada);


                                                // $hora_entrada_formato = date('H:i', $normal);
                                                // entre 7:46
                                                // normal = entrada =< 7:35
                                                // $entrada_horario = $entrada_horario->format('H:i');

                                                if ($hora_marcada <= $entrada_horario) {
                                                    $tardanza_cantidad = 0; // marque 7:46 pero temprano es 7:35
                                                    $licencia = 'NMS';
                                                } else {



                                                    if ($hora_marcada >= $tardanza1a && $hora_marcada <= $tardanza1b) {
                                                        $tardanza_cantidad = 1; // marque 7:46 pero pide de  07:36 a 07:45
                                                        // $tardanza = $entrada_horario - $hora_marcada;

                                                        $licencia = 'NMS';
                                                    } elseif ($hora_marcada >= $tardanza2a && $hora_marcada <= $tardanza2b) {
                                                        $tardanza_cantidad = 2; // marque 7:46 pero pide de 07:46 a 07:55

                                                        $licencia = 'NMS';
                                                    } elseif ($hora_marcada >= $tardanza3a && $hora_marcada <= $tardanza3b) {
                                                        $tardanza_cantidad = 3; // marque 7:46 pero pide de 07:56 a 08:00

                                                        $licencia = 'NMS';
                                                    } elseif ($hora_marcada > $tardanza3b) {

                                                        $tardanza_cantidad = 0;
                                                        $licencia = '+30';
                                                    } else {
                                                        // $tardanza_cantidad = 0;
                                                        // $licencia ='NMS';
                                                    }


                                                    // $diferenciaSegundos = $entrada_horario_Segundos - $entradaSegundos;
                                                    // $horas = floor($diferenciaSegundos / 3600);
                                                    // $minutos = floor(($diferenciaSegundos % 3600) / 60);
                                                    // $diferenciaFormateada = sprintf('%02d:%02d', $horas, $minutos);
                                                    // $diferenciaString = (string) $diferenciaFormateada;
                                                    // $tardanza = $diferenciaString;

                                                }


                                                if ($entrada != '00:00' && $salida == '00:00' && $licencia !== '+30') {
                                                    // $salida ='00:00';
                                                    $licencia = 'NMS';
                                                } else {


                                                    if ($salida_marcada < $salida_horario && $licencia !== '+30') {
                                                        $licencia = 'NMS';
                                                    } else {
                                                        if ($hora_marcada <= $tardanza3b && $salida_marcada >= $salida_horario) {
                                                            $licencia = 'OK';
                                                        } else {
                                                            // $licencia ='otro';
                                                        }
                                                    }
                                                }
                                                // $totalMinutos = ($tardanza->h * 60) + $tardanza->i;
                                                // $horas = floor($totalMinutos / 60);
                                                // $minutos = $totalMinutos % 60;


                                                // $tardanza = sprintf('%02d:%02d', $horas, $minutos);
                                                if (($entrada_marcada_segundos > $entrada_horario_Segundos) && $entrada != '00:00') {
                                                    $diferenciaSegundos = $entrada_marcada_segundos - $entrada_horario_Segundos;
                                                    $diferenciaFormateada = gmdate("H:i", $diferenciaSegundos);
                                                    $tardanza = $diferenciaFormateada;
                                                    // $tardanza = '00:01';
                                                }
                                                if ($entrada != '00:00' && $salida != '00:00') {
                                                    $diferenciaSegundos =  $salida_marcada_segundos - $entrada_marcada_segundos;
                                                    $diferenciaFormateada = gmdate("H:i", $diferenciaSegundos);
                                                    $total_reloj = $diferenciaFormateada;
                                                }
                                            }

                                            if ($licencia == 'SR') {
                                                $total_string = '00:00';
                                            }
                                            if ($licencia == "NMS") {
                                                $total_string = '00:00';
                                            }
                                            $justificacion = '';
                                            // $result=$this->model->getBoleta($trabajador_id,$fecha_0);
                                            // if(empty($result)){
                                            //     $justificacion='si';
                                            // }


                                            $result = $this->model->getAsistencia($Telefono_id_trabajador, $fecha_0);
                                            $marcada = $hora_marcada->format('H:i');
                                            $salida = $salida_marcada->format('H:i');
                                            // $tardanzaString = $tardanza;
                                            if (empty($result)) {
                                                // REGISTRO ASISTENCIA
                                                $cantidad_registrado++;
                                                // $cantidad_actualizado++;
                                                $this->model->registrarAsistencia($trabajador_id, $licencia, $fecha_0, $marcada, $salida, $tardanza, $tardanza_cantidad, $total_reloj, $total_string, $justificacion, $reloj_1, $reloj_2, $reloj_3, $reloj_4, $reloj_5, $reloj_6, $reloj_7, $reloj_8);
                                            } else {
                                                $asistencia_id = $result['aid'];
                                                // ACTUALIZO ASISTENCIA
                                                $cantidad_actualizado++;
                                                $this->model->modificarAsistencia($trabajador_id, $licencia, $fecha_0, $marcada, $salida, $tardanza, $tardanza_cantidad, $total_reloj, $total_string, $justificacion, $reloj_1, $reloj_2, $reloj_3, $reloj_4, $reloj_5, $reloj_6, $reloj_7, $reloj_8, $asistencia_id);
                                            }
                                            $mensaje =
                                                // '<br>Diferencia: '. $diferencia.
                                                '<br>Llegada: ' . $hora_marcada->format('H:i') .
                                                '<br>temprano: ' . $normal->format('H:i') .
                                                '<br>Tarde 1a: ' . $tardanza1a->format('H:i') .
                                                '<br>Tarde 1b: ' . $tardanza1b->format('H:i') .
                                                '<br>Tarde 2a: ' . $tardanza2a->format('H:i') .
                                                '<br>Tarde 2b: ' . $tardanza2b->format('H:i') .
                                                '<br>Tarde 3a: ' . $tardanza3a->format('H:i') .
                                                '<br>Tarde 3b: ' . $tardanza3b->format('H:i') .
                                                '<br>cantidad Tarde: ' . $tardanza_cantidad .
                                                '<br>Horario Entrada: ' . $entrada_horario_string .
                                                '<br>Horario Salida: ' . $salida_horario_string .
                                                '<br>Horario total: ' . $total_string .
                                                '<br>Entrada: ' . $entrada .
                                                '<br>Salida: ' . $salida .
                                                '<br>nombre: ' . $nombre .
                                                '<br>fecha: ' . $fecha_0 .
                                                '<br>id:' . $id .
                                                '<br>r1:' . $reloj_1 .
                                                '<br>r2:' . $reloj_2 .
                                                '<br>r3:' . $reloj_3 .
                                                '<br>r4:' . $reloj_4 .
                                                '<br>r5:' . $reloj_5 .
                                                '<br>r6:' . $reloj_6 .
                                                '<br>r7:' . $reloj_7 .
                                                '<br>r8:' . $reloj_8 .
                                                '<br>Licencia:' . $licencia;

                                            // $hora_marcada = $hora_marcada->format('H:i');

                                            // }ACTIVAR CON LA PARTE DE ARRIBA
                                        }
                                    } else {
                                        $cantidad_bug++;
                                    }
                                }
                            }
                            fclose($file);
                            $cantidad_registros = $cantidad_registrado + $cantidad_actualizado + $cantidad_ignorados + $cantidad_bug;
                            $mensaje = 'El archivo es CSV. <br> Tiene:' . $cantidad_registros . $mensaje; //var_dump($dato) ;
                            $icono = 'success';
                            $tamaño = 'Registrados:' . $cantidad_registrado . '|Actualizador:' . $cantidad_actualizado . '|Ignorados:' . $cantidad_ignorados . '|Bugs:' . $cantidad_bug;
                        } else {
                            // Manejo de errores si no se puede abrir el archivo
                            $mensaje = 'El archivo debe ser CSV no se pudo abrir.';
                            $icono = 'warning';
                            $tamaño = '';
                        }
                        break;
                    case 'xls':
                        $mensaje = 'El archivo es XLS.';
                        $icono = 'success';
                        $tamaño = '';
                        break;
                    case 'xlsx':

                        $lector = IOFactory::createReader('Xlsx');
                        $documento = $lector->load($archivo);
                        $hoja = $documento->getActiveSheet();
                        // $filas = $hoja->toArray();
                        // $tamaño_1 = count($filas);
                        $filas = $hoja->getHighestDataRow();
                        $columnas = $hoja->getHighestDataColumn();
                        $columnasTotales = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnas);

                        $fila_contada = 0;
                        $columna_contada = 0;
                        // ASISTENCIA
                        if ($columnasTotales == 15) {
                            for ($fila = 2; $fila <= $filas; $fila++) {
                                $valores_separados = array();
                                // Iterar sobre las columnas
                                // $fila_contada++;
                                for ($columna = 'A'; $columna <= $columnas; $columna++) {
                                    // Obtener el valor de la celda en la fila y columna actual
                                    $valorCelda = $hoja->getCell($columna . $fila)->getFormattedValue();
                                    // $valorCeldaTexto = strval($valorCelda);
                                    // $columna_contada++;
                                    // Realizar las operaciones necesarias con el valor de la celda
                                    $valores_separados[] = $valorCelda;
                                }


                                if (
                                    $valores_separados[3] == 'DIRESA/PASIVOS ' ||
                                    $valores_separados[3] == 'DIRESA/PASIVOS /CESADOS DESIGNA' ||
                                    $valores_separados[3] == 'DIRESA/PASIVOS /CESADOS DIRESA' ||
                                    $valores_separados[3] == 'DIRESA/PASIVOS /CESADOS PRACTICANTES'
                                ) {
                                    $cantidad_ignorados++;
                                } else if (
                                    $valores_separados[3] == 'DIRESA/276' ||
                                    $valores_separados[3] == 'DIRESA/CONTRALORIA' ||
                                    $valores_separados[3] == 'DIRESA/CONTRATADO REGUL' ||
                                    $valores_separados[3] == 'DIRESA/CONTRATADO TEMPORAL' ||
                                    $valores_separados[3] == 'DIRESA/CONTRATO COVID A CAS' ||
                                    $valores_separados[3] == 'DIRESA/DESIGNADOS' ||
                                    $valores_separados[3] == 'DIRESA/DESTACADOS' ||
                                    $valores_separados[3] == 'DIRESA/NOMBRADO' ||
                                    $valores_separados[3] == 'DIRESA/PRACTICANTES' ||
                                    $valores_separados[3] == 'DIRESA/PROGRAMA_CANCER_DESA' ||
                                    $valores_separados[3] == 'DIRESA/PROYECTOS' ||
                                    $valores_separados[3] == 'DIRESA/REPUEST JUD' ||
                                    $valores_separados[3] == 'RED SALUD'
                                ) {



                                    // horas del reloj 
                                    $reloj_1 = $valores_separados[4];
                                    $reloj_2 = $valores_separados[5];
                                    $reloj_3 = $valores_separados[6];
                                    $reloj_4 = $valores_separados[7];
                                    $reloj_5 = $valores_separados[8];
                                    $reloj_6 = $valores_separados[9];
                                    $reloj_7 = $valores_separados[10];
                                    $reloj_8 = $valores_separados[11];
                                    // fecha de la asistencia
                                    $fechaString = $valores_separados[0];
                                    $timestamp = strtotime($fechaString);
                                    $fechaFormateada = date('Y-m-d', $timestamp);


                                    $result = $this->model->getAsistencia($valores_separados[1], $fechaFormateada);
                                    $entrada = null;
                                    $salida = null;
                                    $licencia = '';
                                    // RECORRO LAS MARCADAS
                                    for ($i = 4; $i <= 11; $i++) {
                                        // Verifica si la marcación es diferente de "00:00"
                                        if ($valores_separados[$i] !== '00:00') {
                                            // Si aún no se ha establecido la hora de entrada, asigna la marcación actual como la hora de entrada
                                            if ($entrada === null) {
                                                $entrada = $valores_separados[$i];
                                            }
                                            // Asigna cada marcación diferente de "00:00" como la hora de salida hasta que no haya más
                                            $salida = $valores_separados[$i];
                                        }
                                    }
                                    // OBTENER DATOS DEL TRABAJADOR
                                    $result = $this->model->getTrabajador($valores_separados[1]);
                                    if (empty($result)) {
                                        $cantidad_ignorados++;
                                    } else {
                                        $id = $result['id'];
                                        // $horario_id = $result['horario_id'];
                                        // $result =$this->model->gethorarioDetalle($horario_id);

                                        if (empty($result)) {
                                            // $aviso = 'Debe de Registrar un Horario'
                                        } else {
                                            // foreach ($result as $horario) {
                                            $horaEntrada = new DateTime($result['hora_entrada']);
                                            $horaSalida = new DateTime($result['hora_salida']);
                                            $total = new DateTime($result['total']);

                                            // primer validador +5
                                            $normal  = clone $horaEntrada;
                                            $horaSalidaModificada = clone $horaSalida;

                                            // Validador +5 minutos
                                            $normal->modify('+5 minutes');

                                            // Validador +6 minutos (tardanza 1)
                                            $tardanza1a  = clone $horaEntrada;
                                            $tardanza1a->modify('+6 minutes');

                                            $tardanza1b  = clone $tardanza1a;
                                            $tardanza1b->modify('+15 minutes');

                                            // Validador +16 minutos (tardanza 2)
                                            $tardanza2a = clone $horaEntrada;
                                            $tardanza2a->modify('+16 minutes');

                                            $tardanza2b  = clone $tardanza2a;
                                            $tardanza2b->modify('+25 minutes');

                                            // Validador +30 minutos (tardanza 3)
                                            $tardanza3a  = clone $horaEntrada;
                                            $tardanza3a->modify('+26 minutes');

                                            $tardanza3b = clone $tardanza2a;
                                            $tardanza3b->modify('+30 minutes');

                                            // $hora_entrada = $horaEntrada->format('H:i');
                                            // $hora_entrada = $horaEntrada->format('H:i');
                                            // Aquí puedes hacer lo que necesites con los valores obtenidos
                                            // Por ejemplo, comparar con los valores de tus relojes
                                            if ($entrada == NULL) {
                                                $licencia = 'SR';
                                                $entrada = '00:00';
                                            } else {
                                                $hora_entrada = strtotime($entrada);
                                                $hora_entrada_formato = date('H:i', $hora_entrada);

                                                if ($hora_entrada_formato <= $normal) {
                                                    $tardanza_cantidad = 0;
                                                } elseif ($hora_entrada_formato >= $tardanza1a && $hora_entrada_formato <= $tardanza1b) {
                                                    $tardanza_cantidad = 1;
                                                } elseif ($hora_entrada_formato >= $tardanza2a && $hora_entrada_formato <= $tardanza2b) {
                                                    $tardanza_cantidad = 2;
                                                } elseif ($hora_entrada_formato >= $tardanza3a && $hora_entrada_formato <= $tardanza3b) {
                                                    $tardanza_cantidad = 3;
                                                } elseif ($hora_entrada_formato > $tardanza3b) {
                                                    $tardanza_cantidad = 0;
                                                    $licencia = '+30';
                                                }

                                                if ($entrada != null && $salida == null) {
                                                    $salida = '00:00';
                                                    $licencia = 'NMS';
                                                } else {
                                                    $hora_salida = strtotime($salida);
                                                    $hora_salida_formato = date('H:i', $hora_salida);

                                                    $diferencia_segundos = $hora_salida - $hora_entrada;
                                                    $horas = floor($diferencia_segundos / 3600);
                                                    $minutos = floor(($diferencia_segundos - ($horas * 3600)) / 60);
                                                    $diferencia_formato = sprintf('%02d:%02d', $horas, $minutos);



                                                    if ($hora_salida_formato < '15:30') {
                                                        $licencia = 'NMS';
                                                    } else {
                                                        if ($hora_entrada_formato <= $tardanza3b && $hora_salida_formato >= $horaSalida) {
                                                            $licencia = 'OK';
                                                        } else {
                                                            $licencia = 'otro';
                                                        }
                                                    }
                                                }
                                            }
                                            // }

                                        }

                                        $total_reloj = $diferencia_formato;
                                        $total = $diferencia_formato;
                                        $tardanza = $entrada . '-' . $salida;
                                        // $tardanza_cantidad = 1;
                                        $justificacion = 'justificado';
                                        $comentario = 'comentario';
                                    }
                                }
                            }
                        }

                        $muestra = $licencia . '|' . $fechaFormateada . '|' . $entrada . '|' . $salida . '|' . $total_reloj . '|' . $total . '|' . $tardanza . '|' . $tardanza_cantidad . '|' . $justificacion . '|' . $comentario . '|' . $reloj_1 . '|' . $reloj_2 . '|' . $reloj_3 . '|' . $reloj_4 . '|' . $reloj_5 . '|' . $reloj_6 . '|' . $reloj_7 . '|' . $reloj_8;
                        $cantidad_registros = $cantidad_registrado + $cantidad_actualizado + $cantidad_ignorados + $cantidad_bug;
                        $mensaje = 'El archivo es XLSX.' . $muestra; //var_dump($dato) ;
                        $icono = 'success';
                        $tamaño = 'Registrados:' . $cantidad_registrado . '|Actualizados:' . $cantidad_actualizado . '|Ignorados:' . $cantidad_ignorados . '|Bugs:' . $cantidad_bug;
                        break;
                    default:
                        $mensaje = 'El archivo debe ser CSV,XlS o XLSX.';
                        $icono = 'error';
                        $tamaño = '';
                        break;
                }
            } else {
                $mensaje = 'Error al subir el archivo.';
                $icono = 'error';
            }
        } else {
            echo ".";
            $mensaje = 'Error: No se ha subido ningun archivo.';
            $icono = 'error';
        }
        $datos_post = $_POST;


        $respuesta = array('msg' => $mensaje, 'icono' => $icono, 'encabezado' => $tamaño, 'post' => $datos_post);

        echo json_encode($respuesta);
        die();
    }
}
