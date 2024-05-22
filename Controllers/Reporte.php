<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        if ((isset($_POST['nombre']))){

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
                                $this->model->registrarlog($_SESSION['id'],'Crear','Cargos', $datos_log_json);
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
                                    $this->model->registrarlog($_SESSION['id'],'Modificar','Cargos', $datos_log_json);
                                } else {
                                    $respuesta = array('msg' => 'Error al modificar', 'icono' => 'error');
                                }
                            }
                        } else {
                            // El usuario no existe, se permite la modificación
                            $data = $this->model->modificar($nombre, $nivel, $estado, $id);
                            if ($data == 1) {
                                $respuesta = array('msg' => 'Usuario modificado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'],'Modificar','Cargos', $datos_log_json);
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

    public function generar_trabajador(){
        // $respuesta =array();
        
        $data[]=[];
        $mensaje ='';
        if (isset($_POST['trabajadores']) && isset($_POST['meses']) && isset($_POST['anio'])) {
            $trabajadores = $_POST['trabajadores'];
            $meses = $_POST['meses'];
            $anio = $_POST['anio'];
            $cantidad =0;
            $reporte =0;
            foreach($trabajadores as $trabajador) {
                $trabajador_actual = $trabajador;
                foreach($meses as $mes){
                    // $respuesta[] = "Trabajador $trabajador Mes $mes año $anio";
                    $reporte++;
                    $cantidad++;
                    $inasistencia=0;
                    $tardanza=0;
                    $data = $this->model->Reporte_Trabajador($trabajador,$mes,$anio);
                    // $datos = $data;
                    // // $respuesta[] = $data;
                    // // $dato= $data;
                    // // var_dump($respuesta);
                    // if($data ==0 ){
                       
                    // //     $spread = new Spreadsheet();
                     
                    // //     for ($i = 0; $i < count($data); $i++) {
                            
                        
                    // //         $sheet = $spread->getActiveSheet();
                    // //         $sheet->setCellValue('A1', 'Nombre');
                    // //         $sheet->setCellValue('B1','hola');
                    // //         $sheet->setCellValue('C1','hola');
                    // //         $sheet->setCellValue('D1','hola');
                    // //         $sheet->setCellValue('E1','hola');
                    // //         $sheet->setCellValue('F1','hola');
                    // //         $sheet->setCellValue('G1','hola');
                           
                            
                    // //             // $sheet->setCellValue('A1',$data[$i]['trabajador_nombre']);
                    // //             // $sheet->setCellValue('B1',$data[$i]['fecha']);
                    // //             // $sheet->setCellValue('C1',$data[$i]['licencia']);
                    // //             // $sheet->setCellValue('D1',$data[$i]['entrada']);
                    // //             // $sheet->setCellValue('E1',$data[$i]['salida']);
                    // //             // $sheet->setCellValue('F1',$data[$i]['total_reloj']);
                    // //             // $sheet->setCellValue('G1',$data[$i]['total']);

                    // //             $tardanza = $tardanza + $data[$i]['tardanza_cantidad'];
                    // //             $inasistencia = $inasistencia + $data[$i]['inasistencia'];
                    // //             // $coordenadaCelda = PHPExcel_Cell::stringFromColumnIndex($indiceColumna) . $indiceFila;
                    // //             // $sheet->setCellValue([$indiceColumna, $indiceFila], $valor .'-'.$cantidad.'prueba');
                    // //             // $indiceColumna++;
                            
                    // //         // $indiceFila++;
                    // //     }
                    // //     $fileName = "Descarga_excelo_'$cantidad'.xlsx";

                    // //     // Crear un escritor para guardar el archivo
                    // //     $writer = new Xlsx($spread);

                    // //     // Especificar la ruta donde guardar el archivo
                    // //     $filePath = './Uploads/Reportes/'.$fileName;

                    // //     // Guardar el archivo
                    // //     $writer->save($filePath);

                        
                    // //     // $writer = new Xlsx($spreadsheet);
                    // //     // header('Content-Type: application/xls');
                    // //     // // header('Content-Disposition: attachment;filename="seguimiento_'.$cantidad.'_'.$nombre.'.xlsx"');
                    // //     // header('Content-Disposition: attachment;filename="seguimiento_'.$cantidad.'.xlsx"');
                    // //     // header('Pragma: no-cache');
                    // //     // $writer->save('php://output');

                        
                     
                    // //     // $writer->save('php://output');
                       

                    // }else{
                    // //   $respuesta = array('msg' => $data, 'icono' => 'error');
                    // }
                    if(empty($data)){
                        $mensaje += "valor Vacio para Trabajador $trabajador Mes $mes año $anio";
                    }else{
                        for ($i = 0; $i < count($data); $i++) {
                            // $datos[$i] = $data[$i];
                            // $datos[$i] = $data[$i]['trabajador_nombre'];
                            $cantidad++;
                        }
                        array_push($data);
                    }

                   
                  
                }
                // $respuesta = array('msg' => $dato, 'icono' => 'error');
            }
            if(empty($mensaje)){
                $respuesta = array('msg' => 'agregado' . $data, 'icono' => 'success');
            }else{
                $respuesta = array('msg' => 'hola'.$mensaje, 'icono' => 'success');
            }
            
            // $spread = new Spreadsheet();
          

            // // Añadir contenido a la hoja de cálculo
            // $sheet = $spread->getActiveSheet();
            // $sheet->setCellValue('A1', 'Nombre');
            // $sheet->setCellValue('B1', 'Edad');
            // $sheet->setCellValue('A2', 'Juan');
            // $sheet->setCellValue('B2', '25');
            // $sheet->setCellValue('A3', 'María');
            // $sheet->setCellValue('B3', '30');
            // $spread
            //     ->getProperties()
            //     ->setCreator("Nestor Tapia")
            //     ->setLastModifiedBy('BaulPHP')
            //     ->setTitle('Excel creado con PhpSpreadSheet')
            //     ->setSubject('Excel de prueba')
            //     ->setDescription('Excel generado como demostración')
            //     ->setKeywords('PHPSpreadsheet')
            //     ->setCategory('Categoría Excel');
            
            // $fileName="Descarga_excelo_'$cantidad'.xlsx";
            // # Crear un "escritor"
            // $writer = new Xlsx($spread);
            // # Le pasamos la ruta de guardado
            
            // $filePath = './Uploads/Reportes/'.$fileName;
            // $writer->save($filePath);
            
            // $data = $this->model->Reporte_Trabajador(812,5,2024);
            // $nombre = $data[0]['trabajador_nombre'];
            // $respuesta = array($data,$nombre);
            // $respuesta = array($data);
            //  $respuesta = array('msg' => 'registrado el reporte', 'icono' => 'error');
            $respuesta = array('msg' => var_dump($data), 'icono' => 'success');

        }else{
            $respuesta = array('msg' => 'falta de datos', 'icono' => 'error');
           
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function exportar($data){

    }
}
