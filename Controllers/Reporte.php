<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Reporte extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['nombre'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {

        $data['title'] = 'Reporte General';
        $data1 = '';

        $this->views->getView('Administracion', "Reporte", $data, $data1);
    }
    public function Trabajador()
    {

        $data['title'] = 'Reporte Trabajador';
        $data1 = '';

        $this->views->getView('Administracion', "Reporte_Trabajador", $data, $data1);
    }

    public function Direccion()
    {

        $data['title'] = 'Reporte por Direccion';
        $data1 = '';

        $this->views->getView('Administracion', "Reporte_Direccion", $data, $data1);
    }
    public function listar()
    {
        $data = $this->model->getReporteTrabajadorAll();

        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        if ((isset($_POST['nombre']))) {

            $nombre = $_POST['nombre'];
            $nivel = $_POST['nivel'];
            $estado = $_POST['estado'];
            $id = $_POST['id'];

            $datos_log = array(
                "nombre" => $nombre,
                "nivel" => $nivel,
                "estado" => $estado,

            );
            $datos_log_json = json_encode($datos_log);

            if (empty($nombre)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $error_msg = '';
                if (strlen($nombre) < 5 || strlen($nombre) > 31) {
                    $error_msg .= 'El Cargo debe tener entre 5 y 30 caracteres. <br>';
                }
                if ($nivel <= 0 || $nivel >= 10) {
                    $error_msg .= 'El Nivel debe de entre 1 a 10. <br>';
                }

                if (!empty($error_msg)) {
                    $respuesta = array('msg' => $error_msg, 'icono' => 'warning');
                } else {
                    // VERIFICO LA EXISTENCIA
                    $result = $this->model->verificar($nombre);
                    // REGISTRAR
                    if (empty($id)) {
                        if (empty($result)) {
                            $data = $this->model->registrar($nombre, $nivel);

                            if ($data > 0) {
                                $respuesta = array('msg' => 'Cargo registrado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'], 'Crear', 'Cargos', $datos_log_json);
                            } else {
                                $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                            }
                        } else {
                            $respuesta = array('msg' => 'Cargo en uso', 'icono' => 'warning');
                        }
                        // MODIFICAR
                    } else {
                        if ($result) {
                            if ($result['id'] != $id) {
                                $respuesta = array('msg' => 'Cargo en uso', 'icono' => 'warning');
                            } else {
                                // El nombre de usuario es el mismo que el original, se permite la modificación
                                $data = $this->model->modificar($nombre, $nivel, $estado, $id);
                                if ($data == 1) {
                                    $respuesta = array('msg' => 'Cargo modificado', 'icono' => 'success');
                                    $this->model->registrarlog($_SESSION['id'], 'Modificar', 'Cargos', $datos_log_json);
                                } else {
                                    $respuesta = array('msg' => 'Error al modificar', 'icono' => 'error');
                                }
                            }
                        } else {
                            // El usuario no existe, se permite la modificación
                            $data = $this->model->modificar($nombre, $nivel, $estado, $id);
                            if ($data == 1) {
                                $respuesta = array('msg' => 'Usuario modificado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'], 'Modificar', 'Cargos', $datos_log_json);
                            } else {
                                $respuesta = array('msg' => 'Error al modificar el usuario', 'icono' => 'error');
                            }
                        }
                    }
                }
            }

            echo json_encode($respuesta);
        }

        die();
    }
    //eliminar user
    public function delete($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->eliminar($id);
            if ($data == 1) {
                $respuesta = array('msg' => 'se ha dado de baja', 'icono' => 'success');
            } else {
                $respuesta = array('msg' => 'error al eliminar', 'icono' => 'error');
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta);
        die();
    }
    //editar user
    public function edit($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->getCargo($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function generar_trabajador()
    {
        // $respuesta =array();

        $data[] = [];
        $mensaje = '';
        if (isset($_POST['trabajador']) && isset($_POST['mes']) && isset($_POST['anio'])) {
            $trabajador = $_POST['trabajador'];
            $mes = $_POST['mes'];
            $anio = $_POST['anio'];
            $cantidad = 0;
            $reporte = 0;
            $filePath = '';
            $fileName = '';
            $nuevo_nombre="";
            $nombreArchivo='';

            $nombre_mes = [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Setiembre',
                'Octubre',
                'Noviembre',
                'Diciembre'

            ];
            $diasDeLaSemana = [
                'lun.',
                'mar.',
                'mié.',
                'jue.',
                'vie.',
                'sáb.',
                'dom.'
            ];
            
            // foreach($trabajadores as $trabajador) {

            //     foreach($meses as $mes){
            // $respuesta[] = "Trabajador $trabajador Mes $mes año $anio";
            $nombreMes = $nombre_mes[$mes - 1];
            $reporte++;
            $cantidad++;
            $nombre = '';

            $total = 0;
            $total_real = 0;
            $data = $this->model->Reporte_Trabajador($trabajador, $mes, $anio);
            // $datos = $data;
            // // $respuesta[] = $data;
            // // $dato= $data;
            // // var_dump($respuesta);
            if ($data > 0) {
                $datos_trabajador = $this->model->getTrabajador($trabajador, $mes, $anio);

                date_default_timezone_set("America/Lima");
                setlocale(LC_TIME, 'es_PE.UTF-8', 'esp');
                $fecha_boletas = [];
                $fecha_actual = date('d-m-Y');
                $hora_actual = date('H:i:s');

                $inasistencia = 0;
                $tardanza = 0;
                $Cantidad_licencia = 0;
                $horario_entrada = '';
                $horario_salida = '';
                $boleta = '';


                if ($datos_trabajador > 0) {
                    foreach ($datos_trabajador as $datos) {

                        $nombre = $datos['trabajador_nombre'];
                        $fecha_str = date('d-m-Y', strtotime($datos['fecha']));
                        array_push($fecha_boletas, $fecha_str);
                        $Cantidad_licencia += intval($datos['total_motivos_particulares']);
                        $horario_entrada = $datos['horario_entrada'];
                        $horario_salida = $datos['horario_salida'];
                    }
                        $spread = new Spreadsheet();
                        $sheet = $spread->getActiveSheet();

                        $sheet->setCellValue('A1', 'CÁLCULO DE HORAS ' . strtoupper($nombreMes) . ' del ' . $anio);
                        $sheet->mergeCells('A1:Q1');
                        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
                        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                        $sheet->mergeCells('A2:Q2');

                        $sheet->setCellValue('A3', 'Nombre:');
                        $sheet->mergeCells('A3:C3');
                        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(10);
                        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('A3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ff424242'); // Cambia 'FFFF0000' al color deseado
                        $sheet->getStyle('A3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

                        $sheet->setCellValue('D3', $nombre);
                        $sheet->mergeCells('D3:I3');
                        $sheet->getStyle('D3')->getFont()->setBold(true)->setSize(10);
                        $sheet->getStyle('D3')->getAlignment()->setHorizontal('center');


                        $sheet->setCellValue('A4', 'Hora de Reporte:');
                        $sheet->mergeCells('A4:C4');
                        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(10);
                        $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('A4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ff424242'); // Cambia 'FFFF0000' al color deseado
                        $sheet->getStyle('A4')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

                        $sheet->setCellValue('D4', "$hora_actual - $fecha_actual");
                        $sheet->mergeCells('D4:I4');
                        $sheet->getStyle('D4')->getFont()->setBold(true)->setSize(10);
                        $sheet->getStyle('D4')->getAlignment()->setHorizontal('center');

                        $sheet->setCellValue('A5', 'Horario:');
                        $sheet->mergeCells('A5:C5');
                        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(10);
                        $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
                        $sheet->getStyle('A5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ff424242'); // Cambia 'FFFF0000' al color deseado
                        $sheet->getStyle('A5')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

                        $sheet->setCellValue('D5', "$horario_entrada - $horario_salida");
                        $sheet->mergeCells('D5:I5');
                        $sheet->getStyle('D5')->getFont()->setBold(true)->setSize(10);
                        $sheet->getStyle('D5')->getAlignment()->setHorizontal('center');

                        $sheet->mergeCells('A6:Q6');
                        $sheet->mergeCells('J3:Q5');

                        $headers = ['Día', 'Fecha', 'Entrada', 'Salida', '', 'R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7', 'R8', 'Total', 'Total Real', 'Observación', 'Justificacion'];
                        $sheet->fromArray($headers, NULL, 'A7');
                        $sheet->getStyle('A7:Q7')->getFont()->setBold(true);
                        $sheet->getStyle('A7:Q7')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
                        $sheet->getStyle('A7:Q7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ff5dade2');
                        $sheet->getStyle('E7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ffffffff');

                        $row = 8; // Comienza en la fila 7
                    foreach ($data as $datos) {
                        $boleta = '';
                        $Observacion = '';
                        if ($datos['licencia'] == 'NMS') {
                            $Observacion = "No marco Salida";
                        }
                        $inasistencia += intval($datos['inasistencia']);
                        $tardanza += intval($datos['tardanza_cantidad']);

                        list($horas, $minutos) = explode(':', $datos['total']);
                        $segundos = ($horas * 3600) + ($minutos * 60);
                        $total += $segundos;

                        list($horas, $minutos) = explode(':', $datos['total_reloj']);
                        $segundos = ($horas * 3600) + ($minutos * 60);
                        $total_real += $segundos;

                        $fecha  = $datos['fecha'];
                        $fecha = date('d-m-Y', strtotime($fecha));
                        $justificacion = '';
                        if (in_array($fecha, $fecha_boletas)) {
                            $boleta = 'boleta'; // Asigna la fecha a $boleta si coincide
                        }
                        if (strlen($datos['justificacion']) > 1) {
                            $justificacion .= $datos['justificacion'] . " ";
                        }
                        if (strlen($boleta) > 1) {
                            $justificacion .=  $boleta;
                        }
                        $dia = date("N", strtotime($fecha));

                        $nombreDia = $diasDeLaSemana[$dia - 1];


                        // $sheet->setCellValue('A'.$row, $datos['trabajador_nombre']);    
                        $sheet->setCellValue('A' . $row, $nombreDia);
                        $sheet->setCellValue('B' . $row, $fecha);
                        $sheet->setCellValue('C' . $row, $datos['entrada']);
                        $sheet->setCellValue('D' . $row, $datos['salida']);
                        $sheet->setCellValue('F' . $row, $datos['reloj_1']);
                        $sheet->setCellValue('G' . $row, $datos['reloj_2']);
                        $sheet->setCellValue('H' . $row, $datos['reloj_3']);
                        $sheet->setCellValue('I' . $row, $datos['reloj_4']);
                        $sheet->setCellValue('J' . $row, $datos['reloj_5']);
                        $sheet->setCellValue('K' . $row, $datos['reloj_6']);
                        $sheet->setCellValue('L' . $row, $datos['reloj_7']);
                        $sheet->setCellValue('M' . $row, $datos['reloj_8']);
                        $sheet->setCellValue('N' . $row, $datos['total']);
                        $sheet->setCellValue('O' . $row, $datos['total_reloj']);
                        $sheet->setCellValue('P' . $row, $Observacion);
                        $sheet->setCellValue('Q' . $row, $justificacion);
                        $row++;
                    }
                    foreach (range('A', 'Q') as $columnID) {
                        $sheet->getColumnDimension($columnID)->setAutoSize(false);
                    }
                    $sheet->mergeCells('E7:E' . ($row - 1));

                    $total_horas = floor($total / 3600); // Obtener las horas completas
                    $total_minutos = floor(($total % 3600) / 60); // Obtener los minutos restantes
                    if ($total_minutos == 0) {
                        $total_minutos = '00';
                    }
                    $total = "$total_horas:$total_minutos";

                    $total_real_horas = floor($total_real / 3600); // Obtener las horas completas
                    $total_real_minutos = floor(($total_real % 3600) / 60); // Obtener los minutos restantes
                    if ($total_real_minutos == 0) {
                        $total_real_minutos = '00';
                    }
                    $total_real = "$total_real_horas:$total_real_minutos";

                    // $sheet->mergeCells('A2:Q2');
                    $sheet->mergeCells('A' . $row . ':Q' . $row);
                    $row++;


                    $sheet->mergeCells('A' . $row . ':O' . $row);
                    $sheet->setCellValue('A' . $row, 'Total Horas:');
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
                    $sheet->setCellValue('P' . $row, $total);
                    $sheet->setCellValue('Q' . $row, $total_real);
                    $sheet->getStyle('A1:Q' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                    $row++;
                    // $sheet->mergeCells('A'. $row.':Q'. $row);
                    $row++;
                    $sheet->mergeCells('A' . $row . ':E' . $row);
                    $sheet->setCellValue('A' . $row, 'Licencia por Enfermedad:');
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('right');
                    $sheet->setCellValue('F' . $row, $Cantidad_licencia);

                    $row++;
                    $sheet->mergeCells('A' . $row . ':E' . $row);
                    $sheet->setCellValue('A' . $row, 'Tardanzas:');
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('right');
                    $sheet->setCellValue('F' . $row, $tardanza);

                    $row++;
                    $sheet->mergeCells('A' . $row . ':E' . $row);
                    $sheet->setCellValue('A' . $row, 'Inasistencias:');
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('right');
                    $sheet->setCellValue('F' . $row, $inasistencia);

                    $sheet->getStyle('A7:Q' . $row)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A7:Q' . $row)->getFont()->setSize(11);

                    $sheet->getStyle('A1:Q' . $row)->getFont()->setName('Arial');
                    $sheet->getStyle('A' . ($row - 2) . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                    $sheet->getColumnDimension('A')->setWidth(5);
                    $sheet->getColumnDimension('B')->setWidth(12);
                    $sheet->getColumnDimension('C')->setWidth(8.43);
                    $sheet->getColumnDimension('D')->setWidth(8.43);
                    $sheet->getColumnDimension('E')->setWidth(1.4);
                    $sheet->getColumnDimension('F')->setWidth(8.43);
                    $sheet->getColumnDimension('G')->setWidth(8.43);
                    $sheet->getColumnDimension('H')->setWidth(8.43);
                    $sheet->getColumnDimension('I')->setWidth(8.43);
                    $sheet->getColumnDimension('J')->setWidth(8.43);
                    $sheet->getColumnDimension('K')->setWidth(8.43);
                    $sheet->getColumnDimension('L')->setWidth(8.43);
                    $sheet->getColumnDimension('M')->setWidth(8.43);
                    $sheet->getColumnDimension('N')->setWidth(8.43);
                    $sheet->getColumnDimension('O')->setWidth(17.71);
                    $sheet->getColumnDimension('P')->setWidth(18.40);
                    $sheet->getColumnDimension('Q')->setWidth(17);
                    
                    $nombre = str_replace(' ', '_', $nombre);

                    $nombreArchivo = "Reporte_" . $nombreMes . "_$nombre.xlsx";
                    $fileName = "Reporte_" . $nombreMes . "_$nombre.xlsx";

                    // Crear un escritor para guardar el archivo
                    $writer = new Xlsx($spread);

                    // Especificar la ruta donde guardar el archivo
                    $filePath = './Uploads/Reportes/' . $fileName;

                    // Guardar el archivo
                    $writer->save($filePath);
                    
                }
            }
            if ($data==0) {
                $mensaje = "valor Vacio para Trabajador $trabajador Mes $mes año $anio";
               
            } else {

                for ($i = 0; $i < count($data); $i++) {
                    // $datos[$i] = $data[$i];
                    // $datos[$i] = $data[$i]['trabajador_nombre'];
                    $cantidad++;
                }
            }
            //     }
            // }
            if (empty($mensaje)) {
                $respuesta = array('msg' => 'agregado', 'icono' => 'success', 'archivo' => $filePath,'nombre'=>$nombreArchivo);
            } else {
                $respuesta = array('msg' =>  $mensaje, 'icono' => 'warning', 'archiv0' => $filePath,'nombre'=>$nombreArchivo);
            }
        } else {
            $respuesta = array('msg' => 'falta de datos', 'icono' => 'error');
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function exportar($data)
    {
    }
}
