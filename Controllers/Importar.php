<?php
require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Importar extends Controller
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

        $data['title'] = 'Importar';
        $data1 = '';
        $this->views->getView('Administracion', "Importar", $data, $data1);
    }
    
    // public function importarAntiguo(){
       
    //     $mensaje='';
    //     $icono='';
    //     $tamaño='';

    //     if (isset($_FILES['archivo'])) {
    //         $archivo = $_FILES['archivo'];
        
    //         // Verifica si no hay errores al subir el archivo
    //         if ($archivo['error'] === UPLOAD_ERR_OK) {
    //             // Obtiene la extensión del archivo
    //             $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    //             $nombreArchivoExtension=  $_FILES['archivo']['tmp_name'];
                
    //             // Verifica si la extensión es CSV o XLSX
    //             if ($extension === 'csv' || $extension === 'xls'|| $extension === 'xlsx') {
    //                 // Procesa el archivo
                    
    //                 $numerofilas='';
    //                 $tipoImportacion='';
    //                 // aqui leo el archivo
                    
    //                 if($extension === 'csv') {
    //                     $archivo = fopen($nombreArchivoExtension, 'r');

    //                     $contenido_csv = file_get_contents($nombreArchivoExtension);
    //                         // lo parseo
    //                     $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
    //                     $primer_fila  = str_getcsv($filas_csv[0]); // Obtener encabezados
    //                     $encabezados = array();
    //                     foreach ($primer_fila as $valor) {
    //                         $encabezados[] = $valor;
    //                     }
    //                     $posiciones = array();
    //                 }
    //                 if($extension === 'xls' || $extension === 'xlsx') {
    //                     // $archivo  = "C:/laragon/www/asistencia3/Config/db/samu.xlsx";
    //                     $archivo = $_FILES['archivo']['tmp_name'];
                       
    //                     $spreadsheet = IOFactory::load($archivo);
    //                     $hoja = $spreadsheet->getActiveSheet();
    //                     $encabezados = $hoja->rangeToArray('A1:' . $hoja->getHighestColumn() . '1', null, true, true, true);
    //                     $datosPrimeraFila = reset($encabezados);
    //                     $tamano_encabezados = count($datosPrimeraFila); 
                        
                   
    //                 }
                  
    //                 // $encabezados = explode(',', $primer_fila[0]); // lo guardo en un array
                    
                   

    //                 // Verifica si el archivo se abrió correctamente
    //                 if ($archivo !== false  || $spreadsheet !==null) {

    //                     if($extension === 'csv'){

                            
    //                         // empleados
    //                         // reloj
    //                         // aqui diresa
    //                         // $posiciones = array();
    //                         // $encabezados = explode(',', $primer_fila[0]); // lo guardo en un array
    //                         $tamano_encabezados = count($encabezados); // tamaño del encabezado
    //                         if ($tamano_encabezados === 20) {
    //                             // $tamaño='19';
    //                             // $idUsuario = array_search("ID Usuario", $encabezados);
    //                             // $nombre = array_search("Nombre", $encabezados);
    //                             // $departamento = array_search("Departamento", $encabezados);

    //                             $datos_buscados = array("ID Usuario", "Nombre", "Departamento");
    //                             // $posiciones = array();

    //                             foreach ($datos_buscados as $dato) {
    //                                 $posicion = array_search($dato, $encabezados);
    //                                 if ($posicion !== false) {
    //                                     $posiciones[$dato] = $posicion;
    //                                     // $tamaño = $tamaño .'-'. $posiciones[$dato];
    //                                 }
    //                             }
    //                             $tamaño = "usuario-diresa";

    //                         } elseif ($tamano_encabezados === 14) {
    //                             // $tamaño='13';
    //                             $datos_buscados = array("Fecha", "ID", "Nombre Usuario", "Departamento", "Entrada - Salida 1", "Entrada - Salida 2", "Entrada - Salida 3", "Entrada - Salida 4", "Entrada - Salida 5", "Entrada - Salida 6", "Entrada - Salida 7", "Entrada - Salida 8");
    //                             // $posiciones = array();

    //                             foreach ($datos_buscados as $dato) {
    //                                 $posicion = array_search($dato, $encabezados);
    //                                 if ($posicion !== false) {
    //                                     $posiciones[$dato] = $posicion;
    //                                     // $tamaño = $tamaño .'-'. $posiciones[$dato];
    //                                 }
    //                             }
    //                             $tamaño = "asistencia-diresa";


    //                         } else {
    //                             // $tamaño='fuera de rango';
    //                             $tamaño = "fallo";
    //                         }

                            
    //                         // $encabezado2_posicion = array_search('encabezado2', $encabezados);
    //                         // $encabezado4_posicion = array_search('encabezado4', $encabezados);
    //                         // $mensaje='El archivo se ha subido correctamente.'.'Se añadieron';
    //                         // $icono='success';
                            
                           
    //                     }
    //                     else if($extension === 'xls' || $extension === 'xlsx'){

    //                         // $primer_fila  = fgetcsv($archivo); // Obtener encabezados
    //                         // $tamano_encabezados = count($encabezados);

    //                         // $encabezados = $primer_fila[0]; // lo guardo en un array
    //                         // los de samu

    //                         if ($tamano_encabezados == 15) {
    //                             $tamaño="asistencia-samu";
    //                             $datos_buscados = array("Fecha", "ID", "Nombre Usuario", "Departamento", "Entrada - Salida 1", "Entrada - Salida 2", "Entrada - Salida 3", "Entrada - Salida 4", "Entrada - Salida 5", "Entrada - Salida 6", "Entrada - Salida 7", "Entrada - Salida 8");
    //                             foreach ($datos_buscados as $dato) {
    //                                 $posicion = array_search($dato, $encabezados);
    //                                 if ($posicion !== false) {
    //                                     $posiciones[$dato] = $posicion;
    //                                     // $tamaño = $tamaño .'-'. $posiciones[$dato];
    //                                 }
    //                             }
    //                         }else{
    //                             $tamaño="usuario-samu";
    //                         }
                           
    //                     }else{
    //                         // 
    //                         $mensaje='No se pudo abrir el archivo CSV.';
    //                         $icono='error';
    //                         $tamaño="sin tamaño";
                            
    //                     }

                       
    //                     if($tamaño=="usuario-diresa"||$tamaño=="asistencia-diresa"||$tamaño=="usuario-samu"||$tamaño=="asistencia-samu"){
    //                         // 
    //                         // 
    //                         // 
    //                         // 
    //                         $mensaje='Se registro '.$tamaño ;
    //                         $icono='success';


    //                         if($extension=="csv"){
    //                             fclose($archivo);
    //                         }
                            

                           
    //                     }else{
    //                         $mensaje='EL formato Ingresado del Archivo es Incorrecto.';
    //                         $icono='warning';
    //                         $tamaño =  'y su extension es '. $tamano_encabezados;
    //                     }
                       


    //                     // Lee cada línea del archivo CSV
                    
    //                     // while (($fila = fgetcsv($archivo)) !== false) {
    //                     // // Aquí puedes incluir el código para procesar el archivo CSV o XLSX
    //                     //     // Procesa los datos de la fila
    //                     //     // Aquí deberías implementar la lógica para extraer los datos relevantes de la fila
    //                     //     // y registrarlos en tu base de datos
    //                     //     // $id_usuario = $fila[0];
    //                     //     // $nombre = $fila[1];
    //                     //     // $departamento = $fila[2];
    //                     //     // Continúa extrayendo otros campos según sea necesario y regístralos en la base de datos
    //                     //     $numerofilas++;

    //                     // }
    //                     // $mensaje='El archivo se ha subido correctamente.'.$nombreArchivoExtension.'Se añadieron'.$numerofilas;
    //                     // $icono='success';
    //                     // Cierra el archivo
                       
    //                 } else {
    //                     // Maneja el caso en que no se pudo abrir el archivo
                       
    //                     $mensaje='No se pudo abrir el archivo CSV.';
    //                     $icono='error';
    //                 }


                    
    //             } else {
    //                 $mensaje='El archivo debe ser CSV o XLSX.';
    //                 $icono='warning';
    //             }
    //         } else {
    //             $mensaje='Error al subir el archivo.';
    //             $icono='error';
    //         }
    //     } else {
    //         echo ".";
    //         $mensaje='Error: No se ha subido ningún archivo.';
    //         $icono='error';
    //     }

    //     $respuesta = array('msg' => $mensaje, 'icono' => $icono,'encabezado' =>$tamaño);

    //     echo json_encode($respuesta);
    //     die();
    // }


    // public function importar2(){
       
    //     $mensaje='';
    //     $icono='';
    //     $tamaño='';

    //     $cantidad_ignorados=0;
    //     $cantidad_registrado=0;
    //     $cantidad_actualizado=0;
    //     $cantidad_bug=0;
    //     $cantidad_registros=0;  
    //     $fechaFormateada=null;
    //     $tardanza=0;
        

    //     if (isset($_FILES['archivo'])) {
    //         $archivo = $_FILES['archivo'];
        
    //         // Verifica si no hay errores al subir el archivo
    //         if ($archivo['error'] === UPLOAD_ERR_OK) {
    //             // Obtiene la extensión del archivo
    //             $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    //             $archivo=  $_FILES['archivo']['tmp_name'];
                
    //             // Verifica si la extensión es CSV o XLSX
               
    //             switch($extension)
    //             {       
    //             case 'csv':
                    
    //                 // $reader = IOFactory::createReader('Csv');
    //                 // $reader->setDelimiter(',');
    //                 // $reader->setEnclosure('"');
    //                 // $reader->setSheetIndex(0);
    //                 // $reader->setInputEncoding('UTF-8');
    //                 // $spreadsheet = $reader->load($archivo);
    
    //                 // // Obtener los datos del archivo CSV
    //                 // $sheetData = $spreadsheet->getActiveSheet()->toArray();
    
    //                 // // Mostrar los datos obtenidos
    //                 // $tamaño= print_r($sheetData);
                   

    //                 $file = fopen($archivo, 'r');

    //                 // $contenido_csv = file_get_contents($archivo);
    //                 // $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
    //                 // $primer_fila  = str_getcsv($filas_csv[0]); // Obtener encabezados
    //                 // $encabezados = array();
    //                 $contenido_csv = file_get_contents($archivo);
    //                 $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
    //                 $encabezados = array();
    //                 $datos_columnas = array();
                    
                   
                 

    //                 if ($file) {
    //                     for ($i = 1; $i < count($filas_csv); $i++) {
    //                         $fila = $filas_csv[$i];
                            
    //                         // Obtener los valores de la fila
    //                         // $valores_fila = str_getcsv($fila);
    //                         $valores_separados = explode(',', $fila);
    //                         $tamaño_1 = count($valores_separados);
    //                         // TRABAJADORES
    //                         if($tamaño_1==20){
    //                             // $valores_separados[0];ID USUARIO
    //                             // $valores_separados[1];NOMBRE
    //                             // $valores_separados[2];DEPARTAMENTO
    //                             $result=$this->model->getTrabajador($valores_separados[0]);
    //                             if (empty($result)) {
    //                                 $cantidad_registrado++;
                                     
    //                             }else{
    //                                 $cantidad_ignorados++;
    //                                 // $dato=$dato.'-|-'. $valores_separados[0];
                                   
    //                             }
                                
    //                         }
    //                         // ASISTENCIAS
    //                         if($tamaño_1==14){
    //                             // $valores_separados[0] Fecha	
    //                             // $valores_separados[1]  ID	
    //                             // $valores_separados[2] Nombre 
    //                             // $valores_separados[3] Departamento	
    //                             // $valores_separados[4] Entrada - Salida 1	
    //                             // $valores_separados[5] Entrada - Salida 2	
    //                             // $valores_separados[6] Entrada - Salida 3	
    //                             // $valores_separados[7]  Entrada - Salida 4	
    //                             // $valores_separados[8] Entrada - Salida 5	
    //                             // $valores_separados[9] Entrada - Salida 6	
    //                             // $valores_separados[10] Entrada - Salida 7	
    //                             // $valores_separados[11] Entrada - Salida 8

    //                             // DIRESA/276
    //                             // DIRESA/CONTRALORIA
    //                             // DIRESA/CONTRATADO REGUL
    //                             // DIRESA/CONTRATADO TEMPORAL
    //                             // DIRESA/CONTRATO COVID A CAS
    //                             // DIRESA/DESIGNADOS
    //                             // DIRESA/DESTACADOS
    //                             // DIRESA/NOMBRADO

    //                             // DIRESA/PASIVOS                           S
    //                             // DIRESA/PASIVOS /CESADOS DESIGNA          S
    //                             // DIRESA/PASIVOS /CESADOS DIRESA           S
    //                             // DIRESA/PASIVOS /CESADOS PRACTICANTES     S

    //                             // DIRESA/PRACTICANTES
    //                             // DIRESA/PROGRAMA_CANCER_DESA
    //                             // DIRESA/PROYECTOS                         S
    //                             // DIRESA/REPUEST JUD
    //                             // RED SALUD
    //                             if(
    //                                 $valores_separados[3]=='DIRESA/PASIVOS '||
    //                                 $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DESIGNA'||
    //                                 $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DIRESA'||
    //                                 $valores_separados[3]=='DIRESA/PASIVOS /CESADOS PRACTICANTES'){
    //                                 $cantidad_ignorados++;
    //                             }else if(
                                   
    //                                 $valores_separados[3]=='DIRESA/276'||
    //                                 $valores_separados[3]=='DIRESA/CONTRALORIA'||
    //                                 $valores_separados[3]=='DIRESA/CONTRATADO REGUL'||
    //                                 $valores_separados[3]=='DIRESA/CONTRATADO TEMPORAL'||
    //                                 $valores_separados[3]=='DIRESA/CONTRATO COVID A CAS'||
    //                                 $valores_separados[3]=='DIRESA/DESIGNADOS'||
    //                                 $valores_separados[3]=='DIRESA/DESTACADOS'||
    //                                 $valores_separados[3]=='DIRESA/NOMBRADO'||
    //                                 $valores_separados[3]=='DIRESA/PRACTICANTES'||
    //                                 $valores_separados[3]=='DIRESA/PROGRAMA_CANCER_DESA'||
    //                                 $valores_separados[3]=='DIRESA/PROYECTOS'||
    //                                 $valores_separados[3]=='DIRESA/REPUEST JUD'||
    //                                 $valores_separados[3]=='RED SALUD'){
    //                                 $cantidad_registrado++;
    //                             }
    //                             else{
    //                                 $cantidad_bug++;
    //                                 // $dato = $valores_separados[0] . "--" . $valores_separados[1] . "--" . $valores_separados[2];
                                    
    //                             }   


    //                         }
                            
                           

    //                         // Obtener solo los valores de las columnas 0, 1 y 2
    //                         // $valores_columnas_deseadas = array($valores_separados[0], $valores_separados[1], $valores_separados[2]);
                            
    //                         // Guardar los valores de las columnas en un array
    //                         // $datos_columnas[] = $valores_columnas_deseadas;
                            
    //                         // Imprimir los valores de las columnas

                           
                            
    //                     }
    //                     fclose($file);
    //                     // print_r($tamano_encabezados);
    //                     // $tamaño =$tamaño_1;
    //                     // foreach ($datos_columnas as $fila) {
    //                     //     $tamaño .= implode(' | ', $fila) . "<br>";
    //                     // }
                        
    //                     // $tamaño=var_dump($datos_columnas);
    //                     // $mensaje = "Hola desde PHP!";
    //                     $cantidad_registros = $cantidad_registrado + $cantidad_ignorados + $cantidad_bug;
    //                     $mensaje='El archivo es CSV.'.$cantidad_registros;//var_dump($dato) ;
    //                     $icono='success';
    //                     $tamaño='Registrados:'.$cantidad_registrado .'|Ignorados:'.$cantidad_ignorados .'|Bugs:'.$cantidad_bug ;
                       
    //                 } else {
    //                     // Manejo de errores si no se puede abrir el archivo
    //                     $mensaje='El archivo debe ser CSV no se pudo abrir.';
    //                     $icono='warning';
    //                     $tamaño='';
    //                 }
    //                 break;
    //             case 'xls':
    //                 $mensaje='El archivo es XLS.';
    //                 $icono='success';
    //                 $tamaño='';
    //                 break;
    //             case 'xlsx':

    //                 $lector = IOFactory::createReader('Xlsx');
    //                 $documento = $lector->load($archivo);
    //                 $hoja = $documento->getActiveSheet();
    //                 // $filas = $hoja->toArray();
    //                 // $tamaño_1 = count($filas);
    //                 $filas = $hoja->getHighestDataRow();
    //                 $columnas = $hoja->getHighestDataColumn();
    //                 $columnasTotales = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnas);
                    
    //                 $fila_contada=0;
    //                 $columna_contada=0;
    //                 // ASISTENCIA
    //                 if($columnasTotales==15){
    //                     for ($fila = 2; $fila <= $filas; $fila++) {
    //                         $valores_separados = array();
    //                         // Iterar sobre las columnas
    //                         // $fila_contada++;
    //                         for ($columna = 'A'; $columna <= $columnas; $columna++) {
    //                             // Obtener el valor de la celda en la fila y columna actual
    //                             $valorCelda = $hoja->getCell($columna . $fila)->getFormattedValue();
    //                             // $valorCeldaTexto = strval($valorCelda);
    //                             // $columna_contada++;
    //                             // Realizar las operaciones necesarias con el valor de la celda
    //                             $valores_separados[] = $valorCelda;
                                
    //                         }
                           
                            
    //                         if(
    //                             $valores_separados[3]=='DIRESA/PASIVOS '||
    //                             $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DESIGNA'||
    //                             $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DIRESA'||
    //                             $valores_separados[3]=='DIRESA/PASIVOS /CESADOS PRACTICANTES'){
    //                             $cantidad_ignorados++;
    //                         }else if(
    //                             $valores_separados[3]=='DIRESA/276'||
    //                             $valores_separados[3]=='DIRESA/CONTRALORIA'||
    //                             $valores_separados[3]=='DIRESA/CONTRATADO REGUL'||
    //                             $valores_separados[3]=='DIRESA/CONTRATADO TEMPORAL'||
    //                             $valores_separados[3]=='DIRESA/CONTRATO COVID A CAS'||
    //                             $valores_separados[3]=='DIRESA/DESIGNADOS'||
    //                             $valores_separados[3]=='DIRESA/DESTACADOS'||
    //                             $valores_separados[3]=='DIRESA/NOMBRADO'||
    //                             $valores_separados[3]=='DIRESA/PRACTICANTES'||
    //                             $valores_separados[3]=='DIRESA/PROGRAMA_CANCER_DESA'||
    //                             $valores_separados[3]=='DIRESA/PROYECTOS'||
    //                             $valores_separados[3]=='DIRESA/REPUEST JUD'||
    //                             $valores_separados[3]=='RED SALUD'){

    //                             // $reloj_1 = gmdate('H:i', round($valores_separados[4] * 86400));
    //                             // $reloj_2 = gmdate('H:i', round($valores_separados[5] * 86400));
    //                             // $reloj_3 = gmdate('H:i', round($valores_separados[6] * 86400));
    //                             // $reloj_4 = gmdate('H:i', round($valores_separados[7] * 86400));
    //                             // $reloj_5 = gmdate('H:i', round($valores_separados[8] * 86400));
    //                             // $reloj_6 = gmdate('H:i', round($valores_separados[9] * 86400));
    //                             // $reloj_7 = gmdate('H:i', round($valores_separados[10] * 86400));
    //                             // $reloj_8 = gmdate('H:i', round($valores_separados[11] * 86400));

    //                             // horas del reloj 
    //                             $reloj_1 = $valores_separados[4];
    //                             $reloj_2 = $valores_separados[5];
    //                             $reloj_3 = $valores_separados[6];
    //                             $reloj_4 = $valores_separados[7];
    //                             $reloj_5 = $valores_separados[8];
    //                             $reloj_6 = $valores_separados[9];
    //                             $reloj_7 = $valores_separados[10];
    //                             $reloj_8 = $valores_separados[11];
    //                             // fecha de la asistencia
    //                             $fechaString = $valores_separados[0];
    //                             $timestamp = strtotime($fechaString);
    //                             $fechaFormateada = date('Y-m-d', $timestamp);
                               
   
    //                             $result=$this->model->getAsistencia($valores_separados[1],$fechaFormateada);
    //                             $entrada = null;
    //                             $salida = null;
    //                             $licencia = '';
    //                             // RECORRO LAS MARCADAS
    //                             for ($i = 4; $i <= 11; $i++) {
    //                                 // Verifica si la marcación es diferente de "00:00"
    //                                 if ($valores_separados[$i] !== '00:00') {
    //                                     // Si aún no se ha establecido la hora de entrada, asigna la marcación actual como la hora de entrada
    //                                     if ($entrada === null) {
    //                                         $entrada = $valores_separados[$i];
    //                                     }
    //                                     // Asigna cada marcación diferente de "00:00" como la hora de salida hasta que no haya más
    //                                     $salida = $valores_separados[$i];
    //                                 }
    //                             }
    //                             // OBTENER DATOS DEL TRABAJADOR
    //                             $result =$this->model->getTrabajador($valores_separados[1]);
    //                             if(empty($result)){
    //                                 $cantidad_ignorados++;
    //                             }else{
    //                                 $id = $result['id'];
    //                                 $horario_id = $result['horario_id'];
    //                                 $result =$this->model->gethorarioDetalle($horario_id);

    //                                 if(empty($result)){
    //                                 // $aviso = 'Debe de Registrar un Horario'
    //                                 }else{
    //                                     // foreach ($result as $horario) {
    //                                         $horaEntrada = new DateTime($result['hora_entrada']);
    //                                         $horaSalida = new DateTime($result['hora_salida']);
    //                                         $total = new DateTime($result['total']);
                                            
    //                                         // primer validador +5
    //                                         $normal  = clone $horaEntrada;
    //                                         $horaSalidaModificada = clone $horaSalida;

    //                                         // Validador +5 minutos
    //                                         $normal->modify('+5 minutes');

    //                                         // Validador +6 minutos (tardanza 1)
    //                                         $tardanza1a  = clone $horaEntrada;
    //                                         $tardanza1a->modify('+6 minutes');

    //                                         $tardanza1b  = clone $tardanza1a ;
    //                                         $tardanza1b->modify('+15 minutes');

    //                                         // Validador +16 minutos (tardanza 2)
    //                                         $tardanza2a = clone $horaEntrada;
    //                                         $tardanza2a->modify('+16 minutes');

    //                                         $tardanza2b  = clone $tardanza2a;
    //                                         $tardanza2b->modify('+25 minutes');

    //                                         // Validador +30 minutos (tardanza 3)
    //                                         $tardanza3a  = clone $horaEntrada;
    //                                         $tardanza3a->modify('+26 minutes');

    //                                         $tardanza3b= clone $tardanza2a;
    //                                         $tardanza3b->modify('+30 minutes');

    //                                         // $hora_entrada = $horaEntrada->format('H:i');
    //                                         // $hora_entrada = $horaEntrada->format('H:i');
    //                                         // Aquí puedes hacer lo que necesites con los valores obtenidos
    //                                         // Por ejemplo, comparar con los valores de tus relojes
    //                                         if($entrada ==NULL){
    //                                             $licencia='SR';
    //                                             $entrada='00:00';
    //                                         }else{
    //                                             $hora_entrada = strtotime($entrada);
    //                                             $hora_entrada_formato = date('H:i', $hora_entrada);
                                                
    //                                             if ($hora_entrada_formato <= $normal) {
    //                                                 $tardanza_cantidad = 0;
    //                                             } elseif ($hora_entrada_formato >= $tardanza1a && $hora_entrada_formato <= $tardanza1b) {
    //                                                 $tardanza_cantidad = 1;
    //                                             } elseif ($hora_entrada_formato >= $tardanza2a && $hora_entrada_formato <= $tardanza2b) {
    //                                                 $tardanza_cantidad = 2;
    //                                             } elseif ($hora_entrada_formato >= $tardanza3a && $hora_entrada_formato <= $tardanza3b) {
    //                                                 $tardanza_cantidad = 3;
    //                                             } elseif ($hora_entrada_formato > $tardanza3b) {
    //                                                 $tardanza_cantidad = 0;
    //                                                 $licencia = '+30';
    //                                             }
            
    //                                             if($entrada !=null && $salida == null){
    //                                                 $salida ='00:00';
    //                                                 $licencia='NMS';
    //                                             }else{
    //                                                 $hora_salida = strtotime($salida);
    //                                                 $hora_salida_formato = date('H:i', $hora_salida);

    //                                                 $diferencia_segundos = $hora_salida - $hora_entrada;
    //                                                 $horas = floor($diferencia_segundos / 3600);
    //                                                 $minutos = floor(($diferencia_segundos - ($horas * 3600)) / 60);
    //                                                 $diferencia_formato = sprintf('%02d:%02d', $horas, $minutos);


            
    //                                                 if ($hora_salida_formato < '15:30') {
    //                                                     $licencia = 'NMS';
    //                                                 }else{
    //                                                     if ($hora_entrada_formato <= $tardanza3b && $hora_salida_formato >= $horaSalida) {
    //                                                         $licencia = 'OK';
    //                                                     }else{
    //                                                         $licencia ='otro';
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }
    //                                 // }

    //                             }
    //                             // VALIDAR EL TIPO DE MARCADA QUE FUE
                                
    //                             // if($entrada ==NULL){
    //                             //         $licencia='SR';
    //                             //         $entrada='00:00';
    //                             // }else{
    //                             //     $hora_entrada = strtotime($entrada);
    //                             //     $hora_entrada_formato = date('H:i', $hora_entrada);
                                    
    //                             //     if ($hora_entrada_formato <= '07:35') {
    //                             //         $tardanza_cantidad = 0;
    //                             //     } elseif ($hora_entrada_formato >= '07:36' && $hora_entrada_formato <= '07:45') {
    //                             //         $tardanza_cantidad = 1;
    //                             //     } elseif ($hora_entrada_formato >= '07:46' && $hora_entrada_formato <= '07:55') {
    //                             //         $tardanza_cantidad = 2;
    //                             //     } elseif ($hora_entrada_formato >= '07:56' && $hora_entrada_formato <= '08:00') {
    //                             //         $tardanza_cantidad = 3;
    //                             //     } elseif ($hora_entrada_formato > '08:00') {
    //                             //         $tardanza_cantidad = 0;
    //                             //         $licencia = '+30';
    //                             //     }

    //                             //     if($entrada !=null && $salida == null){
    //                             //         $salida ='00:00';
    //                             //         $licencia='NMS';
    //                             //     }else{
    //                             //         $hora_salida = strtotime($salida);
    //                             //         $hora_salida_formato = date('H:i', $hora_salida);

    //                             //         if ($hora_salida_formato < '15:30') {
    //                             //             $licencia = 'NMS';
    //                             //         }else{
    //                             //             if ($hora_entrada_formato <= '08:00' && $hora_salida_formato >= '15:30') {
    //                             //                 $licencia = 'OK';
    //                             //             }else{
    //                             //                 $licencia ='otro';
    //                             //             }
    //                             //         }
    //                             //     }
    //                             // }
    //                             // if($entrada !=null && $salida == null){
    //                             //     $licencia='NMS';
    //                             // }
    //                             // if($entrada !=null && $salida !=null){
    //                             //     $licencia='OK';
    //                             // }
    //                             //licencia =$licencia 
    //                             $total_reloj = $diferencia_formato;
    //                             $total= $diferencia_formato;
    //                             $tardanza =$entrada .'-'.$salida;
    //                             // $tardanza_cantidad = 1;
    //                             $justificacion ='justificado';
    //                             $comentario = 'comentario';

    //                             // if (empty($result)) {
    //                             //     $cantidad_registrado++;
    //                             //     $result =$this->model->getTrabajador($valores_separados[1]);
    //                             //     if(empty($result)){
    //                             //     $cantidad_ignorados++;
    //                             //     }
    //                             //     // $this->model->registrarAsistenciaPrueba($result['id'],$licencia,$fechaFormateada,$entrada,$salida,$total_reloj,$total,$tardanza,$tardanza_cantidad,$justificacion,$comentario,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8);
    //                             //     $cantidad_registrado++;
    //                             // }else{
    //                             //     $cantidad_actualizado++;
    //                             //     // $dato=$dato.'-|-'. $valores_separados[0];
                                   
    //                             // }
                                
    //                         }
    //                         // else{
    //                         //     $cantidad_bug++;
                                
                                
    //                         // }   
    //                     }
    //                     // $valores_separados[0] Fecha
    //                     // $valores_separados[1] ID
    //                     // $valores_separados[2] Nombre Usuario
    //                     // $valores_separados[3] Departamento
    //                     // $valores_separados[4] Entrada - Salida 1
    //                     // $valores_separados[5] Entrada - Salida 2
    //                     // $valores_separados[6] Entrada - Salida 3
    //                     // $valores_separados[7] Entrada - Salida 4
    //                     // $valores_separados[8] Entrada - Salida 5
    //                     // $valores_separados[9] Entrada - Salida 6
    //                     // $valores_separados[10] Entrada - Salida 7
    //                     // $valores_separados[11] Entrada - Salida 8
                        
    //                 }
    //             }
    //                 // $mensaje='El archivo es XLSX.'.$columnasTotales;
    //                 // $icono='success';
    //                 // $tamaño=$fila_contada.'-'.$columna_contada . '-';

    //                 // $mensaje = "Hola desde PHP!";
    //                 $muestra = $licencia.'|'.$fechaFormateada.'|'.$entrada.'|'.$salida.'|'.$total_reloj.'|'.$total.'|'.$tardanza.'|'.$tardanza_cantidad.'|'.$justificacion.'|'.$comentario.'|'.$reloj_1.'|'.$reloj_2.'|'.$reloj_3.'|'.$reloj_4.'|'.$reloj_5.'|'.$reloj_6.'|'.$reloj_7.'|'.$reloj_8;
    //                 $cantidad_registros = $cantidad_registrado +$cantidad_actualizado+ $cantidad_ignorados + $cantidad_bug;
    //                 $mensaje='El archivo es XLSX.' .$muestra;//var_dump($dato) ;
    //                 $icono='success';
    //                 $tamaño='Registrados:'.$cantidad_registrado .'|Actualizados:'.$cantidad_actualizado .'|Ignorados:'.$cantidad_ignorados .'|Bugs:'.$cantidad_bug ;
    //                 break;
    //             default:
    //                 $mensaje='El archivo debe ser CSV,XlS o XLSX.';
    //                 $icono='error';
    //                 $tamaño='';
    //                 break;
    //             }

    //             // if ($extension === 'csv' || $extension === 'xls'|| $extension === 'xlsx') {

                    
                    
    //             //     $spreadsheet = IOFactory::load($nombreArchivoExtension);
                  
    //             //       $hoja = $spreadsheet->getActiveSheet();
    //             //     // $encabezados = $hoja->rangeToArray('A1:' . $hoja->getHighestColumn() . '1', null, true, true, true);
    //             //     // $datosPrimeraFila = reset($encabezados);
    //             //     $tamano_encabezados = ''; 
    //             //     $posiciones = [];
                        
    //             //     // Verifica si el archivo se abrió correctamente
    //             //     if ($spreadsheet !==null) {

    //             //         if($extension === 'csv'){
    //             //             $reader = new Csv();
    //             //             $reader->setDelimiter(',');
    //             //             $reader->setEnclosure('"');
    //             //             $reader->setSheetIndex(0);
    //             //             $reader->setInputEncoding('utf8_encode');

    //             //             $spreadsheet = $reader->load($nombreArchivoExtension);
    //             //             $sheetData = $spreadsheet->getActiveSheet()->toArray();



    //             //             // $encabezadosArray = explode(',', $encabezados);
    //             //             // $encabezadosArrayOutput = print_r($encabezadosArray, true);

    //             //             // // $datosSeparados = [];
    //             //             // // foreach ($encabezadosArray as $indice => $valor) {
    //             //             // //     $datosSeparados[$indice + 1] = $valor;
    //             //             // //     $tamaño .= $valor ;
    //             //             // // }
    //             //             // $datosPrimeraFila = [];
    //             //             // foreach ($hoja->getRowIterator(1, 1) as $fila) {
    //             //             //     foreach ($fila->getCellIterator() as $celda) {
    //             //             //         $datosPrimeraFila[] = $celda->getValue();
    //             //             //         $tamaño.='-'.$celda->getValue();
    //             //             //     }
    //             //             // }
    //             //             // $tamaño = $encabezados;

                            
    //             //             $data_aux = [];
    //             //             foreach ($sheetData as $row) {
    //             //                 $row_aux = [];
    //             //                 foreach ($row as $cell) {
    //             //                     if ($cell !== null) {
    //             //                         $row_aux[] = mb_convert_encoding(strtoupper($this->remove_accents($cell)), "UTF-8");
    //             //                     } else {
    //             //                         $row_aux[] = null; // Otra acción si el valor es null
    //             //                     }
    //             //                 }
    //             //                 $data_aux[] = $row_aux;
    //             //             }
    //             //                 $tamaño=print_r($data_aux);
                               
                           


    //             //             // $tamano_encabezados = count($datosSeparados);
                          

    //             //             $ultimaFila = $hoja->getHighestRow();
    //             //             $ultimaColumna = $hoja->getHighestColumn();
    //             //             $tipo_archivo = "usuario-diresa";
        
    //             //             // if ($tamano_encabezados === 20) {
    //             //             //     // $tamaño='19';
    //             //             //     // $idUsuario = array_search("ID Usuario", $encabezados);
    //             //             //     // $nombre = array_search("Nombre", $encabezados);
    //             //             //     // $departamento = array_search("Departamento", $encabezados);

    //             //             //     $datos_buscados = array("ID Usuario", "Nombre", "Departamento");
    //             //             //     // $posiciones = array();

    //             //             //     $tipo_archivo = "usuario-diresa";
    //             //             //     foreach ($datos_buscados as $dato) {
    //             //             //         $posicion = array_search($dato, $encabezadosArray);
    //             //             //         if ($posicion !== false) {
    //             //             //             // Si se encuentra el encabezado, almacenar su posición
    //             //             //             $posiciones[$dato] = $posicion;
    //             //             //         }
    //             //             //     }
    //             //             //     for ($fila = 2; $fila <= $ultimaFila; $fila++) {
    //             //             //         // Obtener el valor de la celda en la columna A (primera columna)
    //             //             //         $valorCelda = $hoja->getCell('A' . $fila)->getValue();
                                    
    //             //             //         // Separar los datos por comas y almacenarlos en un array
    //             //             //         $datosFila = explode(',', $valorCelda);
                                    
    //             //             //         // Almacenar la fila en $datosCSV
    //             //             //         $datosCSV[] = $datosFila;
    //             //             //         foreach ($datosCSV as $fila) {
                                       
    //             //             //             foreach ($fila as $dato) {
                                          
    //             //             //                  $tamaño =$tamaño+ $dato;

                                            
                                            
    //             //             //             }
    //             //             //         }
    //             //             //     }
    //             //             // }else{
    //             //             //     $tipo_archivo = "fallo";
    //             //             // }

    //             //         }
    //             //         else if($extension === 'xls' || $extension === 'xlsx'){
    //             //             // $tamano_encabezados = count($datosPrimeraFila); 

    //             //             // $primer_fila  = fgetcsv($archivo); // Obtener encabezados
    //             //             // $tamano_encabezados = count($encabezados);

    //             //             // $encabezados = $primer_fila[0]; // lo guardo en un array
    //             //             // los de samu

    //             //         }else{
    //             //             // 
    //             //             $mensaje='No se pudo abrir el archivo CSV.';
    //             //             $icono='error';
    //             //             $tamaño="sin tamaño";
                            
    //             //         }

                       
    //             //         if($tipo_archivo=="usuario-diresa"||$tipo_archivo=="asistencia-diresa"||$tipo_archivo=="usuario-samu"||$tipo_archivo=="asistencia-samu"){
    //             //             // 
    //             //             // 
    //             //             // 
    //             //             // 
    //             //             $mensaje='Se registro '.$tamano_encabezados ;
    //             //             $icono='success';
    //             //             // $tamaño = $tamano_encabezados;

                           
    //             //         }else{
    //             //             $mensaje='EL formato Ingresado del Archivo es Incorrecto.';
    //             //             $icono='warning';
    //             //             // $tamaño =  'y su extension es '. $tamano_encabezados;
    //             //         }
                       


    //             //         // Lee cada línea del archivo CSV
                    
    //             //         // while (($fila = fgetcsv($archivo)) !== false) {
    //             //         // // Aquí puedes incluir el código para procesar el archivo CSV o XLSX
    //             //         //     // Procesa los datos de la fila
    //             //         //     // Aquí deberías implementar la lógica para extraer los datos relevantes de la fila
    //             //         //     // y registrarlos en tu base de datos
    //             //         //     // $id_usuario = $fila[0];
    //             //         //     // $nombre = $fila[1];
    //             //         //     // $departamento = $fila[2];
    //             //         //     // Continúa extrayendo otros campos según sea necesario y regístralos en la base de datos
    //             //         //     $numerofilas++;

    //             //         // }
    //             //         // $mensaje='El archivo se ha subido correctamente.'.$nombreArchivoExtension.'Se añadieron'.$numerofilas;
    //             //         // $icono='success';
    //             //         // Cierra el archivo
                       
    //             //     } else {
    //             //         // Maneja el caso en que no se pudo abrir el archivo
                       
    //             //         $mensaje='No se pudo abrir el archivo CSV.';
    //             //         $icono='error';
    //             //     }


                    
    //             // } else {
    //             //     $mensaje='El archivo debe ser CSV o XLSX.';
    //             //     $icono='warning';
    //             // }
    //         } else {
    //             $mensaje='Error al subir el archivo.';
    //             $icono='error';
    //         }
    //     } else {
    //         echo ".";
    //         $mensaje='Error: No se ha subido ningún archivo.';
    //         $icono='error';
    //     }

    //     $respuesta = array('msg' => $mensaje, 'icono' => $icono,'encabezado' =>$tamaño);

    //     echo json_encode($respuesta);
    //     die();
    // }

    public function importar(){
       
        $mensaje='';
        $icono='';
        $tamaño='';
        $cantidad_ignorados=0;
        $cantidad_registrado=0;
        $cantidad_actualizado=0;
        $cantidad_bug=0;
        $cantidad_registros=0;  
        $fechaFormateada=null;
        
        
        
        $aviso='';
        $id=NULL;
        $hora_marcada=null;
        $tardanza_cantidad=0;
        $cantidad_departamento_mal=0;
        $cantidad_departamento_bien=0;
    
        if (isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];
            // Verifica si no hay errores al subir el archivo
            if ($archivo['error'] === UPLOAD_ERR_OK) {
                // Obtiene la extensión del archivo
                $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                $archivo=  $_FILES['archivo']['tmp_name'];
                // Verifica si la extensión es CSV o XLSX
                switch($extension)
                {       
                case 'csv':
                    $file = fopen($archivo, 'r');
                    if ($file) {
                        
                        fgets($file);
                        while (($linea = fgets($file)) !== false) {
                                $bandera='';
                                $valores_separados = str_getcsv($linea, ',');
                        
                                $tamaño_1 = count($valores_separados);
                                $cantidad_registros++;
                                // TRABAJADORES
                                if($tamaño_1==20){
                                    // $valores_separados[0];ID USUARIO
                                    // $valores_separados[1];NOMBRE
                                    // $valores_separados[2];DEPARTAMENTO
                                    $institucion='DIRESA';
                                    if($valores_separados[3]=='RED SALUD'){
                                        $institucion='RED SALUD';
                                    }
                                    $result=$this->model->getTrabajador($valores_separados[0]);
                                    if (empty($result)) {
                                        $nombre = mb_convert_encoding($valores_separados[1], "ISO-8859-1");
                                        // $str = str_replace('�', '1', $str);
                                        $nombre = str_replace('?', 'Ñ', $nombre);
                                        $modalidad_trabajo = 'Presencial';
                                        $result=$this->model->registrarTrabajador($nombre,$valores_separados[0],$institucion,$modalidad_trabajo);
                                        if($result >0){
                                            $cantidad_registrado++;
                                        }else{
                                            $cantidad_ignorados++;
                                        }
                                        
                                    }else{
                                        $cantidad_ignorados++;
                                    }
                                    
                                }
                                // ASISTENCIAS
                                if($tamaño_1==14){
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
                                    if(
                                        $valores_separados[3]=='DIRESA/PASIVOS '||
                                        $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DESIGNA'||
                                        // $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DIRESA'||
                                        $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DIRESA'||
                                        $valores_separados[3]=='DIRESA/PASIVOS /CESADOS PRACTICANTES'){
                                        $cantidad_ignorados++;
                                        
                                    }else if( 
                                        $valores_separados[3]=='DIRESA/276'||
                                        $valores_separados[3]=='DIRESA/CONTRALORIA'||
                                        $valores_separados[3]=='DIRESA/CONTRATADO REGUL'||
                                        $valores_separados[3]=='DIRESA/CONTRATADO TEMPORAL'||
                                        $valores_separados[3]=='DIRESA/CONTRATO COVID A CAS'||
                                        $valores_separados[3]=='DIRESA/DESIGNADOS'||
                                        $valores_separados[3]=='DIRESA/DESTACADOS'||
                                        $valores_separados[3]=='DIRESA/NOMBRADO'||
                                        // $valores_separados[3]=='DIRESA/NOMBRADO'||
                                       
                                        $valores_separados[3]=='DIRESA/PRACTICANTES'||
                                        $valores_separados[3]=='DIRESA/PROGRAMA_CANCER_DESA'||
                                        $valores_separados[3]=='DIRESA/PROYECTOS'||
                                        $valores_separados[3]=='DIRESA/REPUEST JUD'||
                                        $valores_separados[3]=='RED SALUD')
                                        {
                                        
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
                                        
                                        $result =$this->model->getTrabajador($Telefono_id_trabajador);
                                        if(empty($result)){
                                            $cantidad_ignorados++;
                                            $aviso = $aviso.$valores_separados[1];
                                            $bandera = 'no existe trabajador';
                                            // BIEN;
                                        }else{
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
                                                $trabajador_id =$result['tid']; 
                                                $entrada_horario = new DateTime($result['hora_entrada']);
                                                $salida_horario = new DateTime($result['hora_salida']);
                                                $total = new DateTime($result['total']);
                                                
                                                $total_reloj='00:00';
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
    
                                                $tardanza1b  = clone $tardanza1a ;
                                                $tardanza1b->modify('+9 minutes');
    
                                                // Validador +16 minutos (tardanza 2)
                                                $tardanza2a = clone $entrada_horario;
                                                $tardanza2a->modify('+16 minutes');
    
                                                $tardanza2b  = clone $tardanza2a;
                                                $tardanza2b->modify('+9 minutes');
    
                                                // Validador +30 minutos (tardanza 3)
                                                $tardanza3a  = clone $entrada_horario;
                                                $tardanza3a->modify('+26 minutes');
    
                                                $tardanza3b= clone $tardanza2a;
                                                $tardanza3b->modify('+14 minutes');
                                                $tardanza_cantidad = 0;

                                                $tardanza='00:00';

                                                $entrada_marcada_segundos = $hora_marcada->getTimestamp();
                                                $entrada_horario_Segundos = $normal->getTimestamp();
                                                $salida_marcada_segundos = $salida_marcada->getTimestamp();
                                                
                                                if($entrada =='00:00'){
                                                    $licencia='SR';
                                                    $entrada='00:00';
                                                    // $tardanza='00:00';
                                                    // $tardanza='00:00';
                                                  
                                                }else{
                                                    
                                                    // $hora_entrada = strtotime($entrada);
                                                    // $hora_marcada = date('H:i', $hora_entrada);
                                                    
                                                   
                                                    // $hora_entrada_formato = date('H:i', $normal);
                                                        // entre 7:46
                                                        // normal = entrada =< 7:35
                                                    // $entrada_horario = $entrada_horario->format('H:i');

                                                    if ($hora_marcada <= $entrada_horario) {
                                                        $tardanza_cantidad = 0; // marque 7:46 pero temprano es 7:35
                                                        $licencia = 'NMS';
                                                    } else{
                                                        
                                                       

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
                                                        
                                                        }else{
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
                                                    
                    
                                                    if($entrada !='00:00' && $salida == '00:00' && $licencia !=='+30'){
                                                        // $salida ='00:00';
                                                        $licencia='NMS';
                                                    }else{

    
                                                        if ($salida_marcada < $salida_horario && $licencia !=='+30') {
                                                            $licencia = 'NMS';
                                                        }else{
                                                            if ($hora_marcada <= $tardanza3b && $salida_marcada >= $salida_horario) {
                                                                $licencia = 'OK';
                                                            }else {
                                                                // $licencia ='otro';
                                                            }
                                                        }
                                                        
                                                    }
                                                    // $totalMinutos = ($tardanza->h * 60) + $tardanza->i;
                                                    // $horas = floor($totalMinutos / 60);
                                                    // $minutos = $totalMinutos % 60;

                                                  
                                                    // $tardanza = sprintf('%02d:%02d', $horas, $minutos);
                                                    if(($entrada_marcada_segundos > $entrada_horario_Segundos) && $entrada !='00:00'){
                                                        $diferenciaSegundos = $entrada_marcada_segundos - $entrada_horario_Segundos; 
                                                        $diferenciaFormateada = gmdate("H:i", $diferenciaSegundos);
                                                        $tardanza =$diferenciaFormateada;
                                                        // $tardanza = '00:01';
                                                    }
                                                    if($entrada !='00:00' && $salida !='00:00'){
                                                        $diferenciaSegundos =  $salida_marcada_segundos - $entrada_marcada_segundos; 
                                                        $diferenciaFormateada = gmdate("H:i", $diferenciaSegundos);
                                                        $total_reloj =$diferenciaFormateada;
                                                    }
                                                    
                                                 
                                                }
                                                $justificacion ='';
                                                // $result=$this->model->getBoleta($trabajador_id,$fecha_0);
                                                // if(empty($result)){
                                                //     $justificacion='si';
                                                // }
                                                $result=$this->model->getAsistencia($Telefono_id_trabajador,$fecha_0);
                                                $marcada = $hora_marcada->format('H:i');
                                                $salida = $salida_marcada->format('H:i');
                                                // $tardanzaString = $tardanza;
                                                if(empty($result)){
                                                    // REGISTRO ASISTENCIA
                                                    $cantidad_registrado++;
                                                    // $cantidad_actualizado++;
                                                    $this->model->registrarAsistencia($trabajador_id,$licencia,$fecha_0,$marcada,$salida,$tardanza,$tardanza_cantidad,$total_reloj,$total_string,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8);
                                                }else{
                                                    $asistencia_id=$result['aid'];
                                                    // ACTUALIZO ASISTENCIA
                                                    $cantidad_actualizado++;
                                                    $this->model->modificarAsistencia($trabajador_id,$licencia,$fecha_0,$marcada,$salida,$tardanza,$tardanza_cantidad,$total_reloj,$total_string,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8,$asistencia_id);
                                                }
                                                $mensaje = 
                                                // '<br>Diferencia: '. $diferencia.
                                                '<br>Llegada: '.$hora_marcada->format('H:i').
                                                '<br>temprano: '.$normal->format('H:i').
                                                '<br>Tarde 1a: '.$tardanza1a->format('H:i') .
                                                '<br>Tarde 1b: '.$tardanza1b->format('H:i') .
                                                '<br>Tarde 2a: '.$tardanza2a->format('H:i') .
                                                '<br>Tarde 2b: '.$tardanza2b->format('H:i') .
                                                '<br>Tarde 3a: '.$tardanza3a->format('H:i') .
                                                '<br>Tarde 3b: '.$tardanza3b->format('H:i') .
                                                '<br>cantidad Tarde: '.$tardanza_cantidad.
                                                '<br>Horario Entrada: '.$entrada_horario_string .
                                                '<br>Horario Salida: '.$salida_horario_string .
                                                '<br>Horario total: '.$total_string .
                                                '<br>Entrada: '.$entrada .
                                                '<br>Salida: '.$salida .
                                                '<br>nombre: '.$nombre .
                                                '<br>fecha: '.$fecha_0.
                                                '<br>id:'. $id.
                                                '<br>r1:'. $reloj_1.
                                                '<br>r2:'. $reloj_2.
                                                '<br>r3:'. $reloj_3.
                                                '<br>r4:'. $reloj_4.
                                                '<br>r5:'. $reloj_5.
                                                '<br>r6:'. $reloj_6.
                                                '<br>r7:'. $reloj_7.
                                                '<br>r8:'. $reloj_8.
                                                '<br>Licencia:'.$licencia;
                                                
                                                // $hora_marcada = $hora_marcada->format('H:i');
                                              
                                            // }ACTIVAR CON LA PARTE DE ARRIBA
                                        }   
                                    }
                                    else{
                                        $cantidad_bug++; 
                                    }   
                                }
                        }
                        fclose($file);
                        // print_r($tamano_encabezados);
                        // $tamaño =$tamaño_1;
                        // foreach ($datos_columnas as $fila) {
                        //     $tamaño .= implode(' | ', $fila) . "<br>";
                        // }
                        
                        // $tamaño=var_dump($datos_columnas);
                        // $mensaje = "Hola desde PHP!";
                        // $mensaje = $mensaje .'Cantidad Mal'.$cantidad_departamento_mal.'BIEN'.$cantidad_departamento_bien;
                        $cantidad_registros = $cantidad_registrado +$cantidad_actualizado+ $cantidad_ignorados + $cantidad_bug;
                        $mensaje='El archivo es CSV. <br> Tiene:'.$cantidad_registros.$mensaje;//var_dump($dato) ;
                        $icono='success';
                        $tamaño='Registrados:'.$cantidad_registrado.'|Actualizador:'.$cantidad_actualizado .'|Ignorados:'.$cantidad_ignorados .'|Bugs:'.$cantidad_bug ;
                       
                    } else {
                        // Manejo de errores si no se puede abrir el archivo
                        $mensaje='El archivo debe ser CSV no se pudo abrir.';
                        $icono='warning';
                        $tamaño='';
                    }
                    break;
                case 'xls':
                    $mensaje='El archivo es XLS.';
                    $icono='success';
                    $tamaño='';
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
                    
                    $fila_contada=0;
                    $columna_contada=0;
                    // ASISTENCIA
                    if($columnasTotales==15){
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
                           
                            
                            if(
                                $valores_separados[3]=='DIRESA/PASIVOS '||
                                $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DESIGNA'||
                                $valores_separados[3]=='DIRESA/PASIVOS /CESADOS DIRESA'||
                                $valores_separados[3]=='DIRESA/PASIVOS /CESADOS PRACTICANTES'){
                                $cantidad_ignorados++;
                            }else if(
                                $valores_separados[3]=='DIRESA/276'||
                                $valores_separados[3]=='DIRESA/CONTRALORIA'||
                                $valores_separados[3]=='DIRESA/CONTRATADO REGUL'||
                                $valores_separados[3]=='DIRESA/CONTRATADO TEMPORAL'||
                                $valores_separados[3]=='DIRESA/CONTRATO COVID A CAS'||
                                $valores_separados[3]=='DIRESA/DESIGNADOS'||
                                $valores_separados[3]=='DIRESA/DESTACADOS'||
                                $valores_separados[3]=='DIRESA/NOMBRADO'||
                                $valores_separados[3]=='DIRESA/PRACTICANTES'||
                                $valores_separados[3]=='DIRESA/PROGRAMA_CANCER_DESA'||
                                $valores_separados[3]=='DIRESA/PROYECTOS'||
                                $valores_separados[3]=='DIRESA/REPUEST JUD'||
                                $valores_separados[3]=='RED SALUD'){

                                // $reloj_1 = gmdate('H:i', round($valores_separados[4] * 86400));
                                // $reloj_2 = gmdate('H:i', round($valores_separados[5] * 86400));
                                // $reloj_3 = gmdate('H:i', round($valores_separados[6] * 86400));
                                // $reloj_4 = gmdate('H:i', round($valores_separados[7] * 86400));
                                // $reloj_5 = gmdate('H:i', round($valores_separados[8] * 86400));
                                // $reloj_6 = gmdate('H:i', round($valores_separados[9] * 86400));
                                // $reloj_7 = gmdate('H:i', round($valores_separados[10] * 86400));
                                // $reloj_8 = gmdate('H:i', round($valores_separados[11] * 86400));

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
                               
   
                                $result=$this->model->getAsistencia($valores_separados[1],$fechaFormateada);
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
                                $result =$this->model->getTrabajador($valores_separados[1]);
                                if(empty($result)){
                                    $cantidad_ignorados++;
                                }else{
                                    $id = $result['id'];
                                    // $horario_id = $result['horario_id'];
                                    // $result =$this->model->gethorarioDetalle($horario_id);

                                    if(empty($result)){
                                    // $aviso = 'Debe de Registrar un Horario'
                                    }else{
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

                                            $tardanza1b  = clone $tardanza1a ;
                                            $tardanza1b->modify('+15 minutes');

                                            // Validador +16 minutos (tardanza 2)
                                            $tardanza2a = clone $horaEntrada;
                                            $tardanza2a->modify('+16 minutes');

                                            $tardanza2b  = clone $tardanza2a;
                                            $tardanza2b->modify('+25 minutes');

                                            // Validador +30 minutos (tardanza 3)
                                            $tardanza3a  = clone $horaEntrada;
                                            $tardanza3a->modify('+26 minutes');

                                            $tardanza3b= clone $tardanza2a;
                                            $tardanza3b->modify('+30 minutes');

                                            // $hora_entrada = $horaEntrada->format('H:i');
                                            // $hora_entrada = $horaEntrada->format('H:i');
                                            // Aquí puedes hacer lo que necesites con los valores obtenidos
                                            // Por ejemplo, comparar con los valores de tus relojes
                                            if($entrada ==NULL){
                                                $licencia='SR';
                                                $entrada='00:00';
                                            }else{
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
            
                                                if($entrada !=null && $salida == null){
                                                    $salida ='00:00';
                                                    $licencia='NMS';
                                                }else{
                                                    $hora_salida = strtotime($salida);
                                                    $hora_salida_formato = date('H:i', $hora_salida);

                                                    $diferencia_segundos = $hora_salida - $hora_entrada;
                                                    $horas = floor($diferencia_segundos / 3600);
                                                    $minutos = floor(($diferencia_segundos - ($horas * 3600)) / 60);
                                                    $diferencia_formato = sprintf('%02d:%02d', $horas, $minutos);


            
                                                    if ($hora_salida_formato < '15:30') {
                                                        $licencia = 'NMS';
                                                    }else{
                                                        if ($hora_entrada_formato <= $tardanza3b && $hora_salida_formato >= $horaSalida) {
                                                            $licencia = 'OK';
                                                        }else{
                                                            $licencia ='otro';
                                                        }
                                                    }
                                                }
                                            }
                                    // }

                                }
                                // VALIDAR EL TIPO DE MARCADA QUE FUE
                                
                                // if($entrada ==NULL){
                                //         $licencia='SR';
                                //         $entrada='00:00';
                                // }else{
                                //     $hora_entrada = strtotime($entrada);
                                //     $hora_entrada_formato = date('H:i', $hora_entrada);
                                    
                                //     if ($hora_entrada_formato <= '07:35') {
                                //         $tardanza_cantidad = 0;
                                //     } elseif ($hora_entrada_formato >= '07:36' && $hora_entrada_formato <= '07:45') {
                                //         $tardanza_cantidad = 1;
                                //     } elseif ($hora_entrada_formato >= '07:46' && $hora_entrada_formato <= '07:55') {
                                //         $tardanza_cantidad = 2;
                                //     } elseif ($hora_entrada_formato >= '07:56' && $hora_entrada_formato <= '08:00') {
                                //         $tardanza_cantidad = 3;
                                //     } elseif ($hora_entrada_formato > '08:00') {
                                //         $tardanza_cantidad = 0;
                                //         $licencia = '+30';
                                //     }

                                //     if($entrada !=null && $salida == null){
                                //         $salida ='00:00';
                                //         $licencia='NMS';
                                //     }else{
                                //         $hora_salida = strtotime($salida);
                                //         $hora_salida_formato = date('H:i', $hora_salida);

                                //         if ($hora_salida_formato < '15:30') {
                                //             $licencia = 'NMS';
                                //         }else{
                                //             if ($hora_entrada_formato <= '08:00' && $hora_salida_formato >= '15:30') {
                                //                 $licencia = 'OK';
                                //             }else{
                                //                 $licencia ='otro';
                                //             }
                                //         }
                                //     }
                                // }
                                // if($entrada !=null && $salida == null){
                                //     $licencia='NMS';
                                // }
                                // if($entrada !=null && $salida !=null){
                                //     $licencia='OK';
                                // }
                                //licencia =$licencia 
                                $total_reloj = $diferencia_formato;
                                $total= $diferencia_formato;
                                $tardanza =$entrada .'-'.$salida;
                                // $tardanza_cantidad = 1;
                                $justificacion ='justificado';
                                $comentario = 'comentario';

                                // if (empty($result)) {
                                //     $cantidad_registrado++;
                                //     $result =$this->model->getTrabajador($valores_separados[1]);
                                //     if(empty($result)){
                                //     $cantidad_ignorados++;
                                //     }
                                //     // $this->model->registrarAsistenciaPrueba($result['id'],$licencia,$fechaFormateada,$entrada,$salida,$total_reloj,$total,$tardanza,$tardanza_cantidad,$justificacion,$comentario,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8);
                                //     $cantidad_registrado++;
                                // }else{
                                //     $cantidad_actualizado++;
                                //     // $dato=$dato.'-|-'. $valores_separados[0];
                                   
                                // }
                                
                            }
                            // else{
                            //     $cantidad_bug++;
                                
                                
                            // }   
                        }
                        // $valores_separados[0] Fecha
                        // $valores_separados[1] ID
                        // $valores_separados[2] Nombre Usuario
                        // $valores_separados[3] Departamento
                        // $valores_separados[4] Entrada - Salida 1
                        // $valores_separados[5] Entrada - Salida 2
                        // $valores_separados[6] Entrada - Salida 3
                        // $valores_separados[7] Entrada - Salida 4
                        // $valores_separados[8] Entrada - Salida 5
                        // $valores_separados[9] Entrada - Salida 6
                        // $valores_separados[10] Entrada - Salida 7
                        // $valores_separados[11] Entrada - Salida 8
                        
                    }
                    }
                   
                    $muestra = $licencia.'|'.$fechaFormateada.'|'.$entrada.'|'.$salida.'|'.$total_reloj.'|'.$total.'|'.$tardanza.'|'.$tardanza_cantidad.'|'.$justificacion.'|'.$comentario.'|'.$reloj_1.'|'.$reloj_2.'|'.$reloj_3.'|'.$reloj_4.'|'.$reloj_5.'|'.$reloj_6.'|'.$reloj_7.'|'.$reloj_8;
                    $cantidad_registros = $cantidad_registrado +$cantidad_actualizado+ $cantidad_ignorados + $cantidad_bug;
                    $mensaje='El archivo es XLSX.' .$muestra;//var_dump($dato) ;
                    $icono='success';
                    $tamaño='Registrados:'.$cantidad_registrado .'|Actualizados:'.$cantidad_actualizado .'|Ignorados:'.$cantidad_ignorados .'|Bugs:'.$cantidad_bug ;
                    break;
                default:
                    $mensaje='El archivo debe ser CSV,XlS o XLSX.';
                    $icono='error';
                    $tamaño='';
                    break;
                }

              
            } else {
                $mensaje='Error al subir el archivo.';
                $icono='error';
            }
        } else {
            echo ".";
            $mensaje='Error: No se ha subido ningún archivo.';
            $icono='error';
        }

        $respuesta = array('msg' => $mensaje, 'icono' => $icono,'encabezado' =>$tamaño);

        echo json_encode($respuesta);
        die();
    }
 
}
