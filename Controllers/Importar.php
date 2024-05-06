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
    public function listar()
    {
        // $data = $this->model->getRegimenes();
        // for ($i = 0; $i < count($data); $i++) {

        //     $datonuevo = $data[$i]['estado'];
          
        //     $data[$i]['sueldo']= "S/".$data[$i]['sueldo']."</div> ";
        //     if ($datonuevo == 'Activo') {
        //         $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
        //     } else {
        //         $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
        //     }

        //     $data[$i]['accion'] = '<div class="d-flex">
        //     <button class="btn btn-primary" type="button" onclick="editUser(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
           
        //     </div>';
        //     // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
        //     // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
        //     // colocar eliminar si es necesario
        // }
        // echo json_encode($data);
        // die();
    }

    public function registrar()
    {
        if ((isset($_POST['nombre']))){

            $nombre = $_POST['nombre'];
            $sueldo = $_POST['sueldo'];
            $estado = $_POST['estado'];
            $id = $_POST['id'];

            $datos_log = array(
                "nombre" => $nombre,
                "sueldo" => $sueldo,
                "estado" => $estado,
               
            );
            $datos_log_json = json_encode($datos_log);

            if (empty($nombre)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $error_msg = '';
                if (strlen($nombre) < 5 || strlen($nombre) > 20) {
                    $error_msg .= 'El Regimen debe tener entre 5 y 20 caracteres. <br>';
                }
                
                if ($sueldo <= 50) {
                    $error_msg .= 'El sueldo debe de ser mayor.<br>';
                }
              
                if (!empty($error_msg)) {
                    
                    $respuesta = array('msg' => $error_msg, 'icono' => 'warning');
                } else {
                    // VERIFICO LA EXISTENCIA
                    $result = $this->model->verificar($nombre);
                    // REGISTRAR
                    if (empty($id)) {
                        if (empty($result)) {
                            $data = $this->model->registrar($nombre,$sueldo);

                            if ($data > 0) {
                                $respuesta = array('msg' => 'Regimen registrado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'],'Crear','Regimen', $datos_log_json);
                            } else {
                                $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                            }
                        } else {
                            $respuesta = array('msg' => 'Regimen en uso', 'icono' => 'warning');
                        }
                        // MODIFICAR
                    } else {
                        if ($result) {
                            if ($result['id'] != $id) {
                                $respuesta = array('msg' => 'Regimen en uso', 'icono' => 'warning');
                            } else {
                                // El nombre de usuario es el mismo que el original, se permite la modificación
                                $data = $this->model->modificar($nombre,$sueldo, $estado, $id);
                                if ($data == 1) {
                                    $respuesta = array('msg' => 'Regimen modificado', 'icono' => 'success');
                                    $this->model->registrarlog($_SESSION['id'],'Modificar','Regimen', $datos_log_json);
                                } else {
                                    $respuesta = array('msg' => 'Error al modificar', 'icono' => 'error');
                                }
                            }
                        } else {
                            // El usuario no existe, se permite la modificación
                            $data = $this->model->modificar($nombre,$sueldo, $estado, $id);
                            if ($data == 1) {
                                $respuesta = array('msg' => 'Usuario modificado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'],'Modificar','Regimen', $datos_log_json);
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
    
    public function importarAntiguo(){
       
        $mensaje='';
        $icono='';
        $tamaño='';

        if (isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];
        
            // Verifica si no hay errores al subir el archivo
            if ($archivo['error'] === UPLOAD_ERR_OK) {
                // Obtiene la extensión del archivo
                $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                $nombreArchivoExtension=  $_FILES['archivo']['tmp_name'];
                
                // Verifica si la extensión es CSV o XLSX
                if ($extension === 'csv' || $extension === 'xls'|| $extension === 'xlsx') {
                    // Procesa el archivo
                    
                    $numerofilas='';
                    $tipoImportacion='';
                    // aqui leo el archivo
                    
                    if($extension === 'csv') {
                        $archivo = fopen($nombreArchivoExtension, 'r');

                        $contenido_csv = file_get_contents($nombreArchivoExtension);
                            // lo parseo
                        $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
                        $primer_fila  = str_getcsv($filas_csv[0]); // Obtener encabezados
                        $encabezados = array();
                        foreach ($primer_fila as $valor) {
                            $encabezados[] = $valor;
                        }
                        $posiciones = array();
                    }
                    if($extension === 'xls' || $extension === 'xlsx') {
                        // $archivo  = "C:/laragon/www/asistencia3/Config/db/samu.xlsx";
                        $archivo = $_FILES['archivo']['tmp_name'];
                       
                        $spreadsheet = IOFactory::load($archivo);
                        $hoja = $spreadsheet->getActiveSheet();
                        $encabezados = $hoja->rangeToArray('A1:' . $hoja->getHighestColumn() . '1', null, true, true, true);
                        $datosPrimeraFila = reset($encabezados);
                        $tamano_encabezados = count($datosPrimeraFila); 
                        
                   
                    }
                  
                    // $encabezados = explode(',', $primer_fila[0]); // lo guardo en un array
                    
                   

                    // Verifica si el archivo se abrió correctamente
                    if ($archivo !== false  || $spreadsheet !==null) {

                        if($extension === 'csv'){

                            
                            // empleados
                            // reloj
                            // aqui diresa
                            // $posiciones = array();
                            // $encabezados = explode(',', $primer_fila[0]); // lo guardo en un array
                            $tamano_encabezados = count($encabezados); // tamaño del encabezado
                            if ($tamano_encabezados === 20) {
                                // $tamaño='19';
                                // $idUsuario = array_search("ID Usuario", $encabezados);
                                // $nombre = array_search("Nombre", $encabezados);
                                // $departamento = array_search("Departamento", $encabezados);

                                $datos_buscados = array("ID Usuario", "Nombre", "Departamento");
                                // $posiciones = array();

                                foreach ($datos_buscados as $dato) {
                                    $posicion = array_search($dato, $encabezados);
                                    if ($posicion !== false) {
                                        $posiciones[$dato] = $posicion;
                                        // $tamaño = $tamaño .'-'. $posiciones[$dato];
                                    }
                                }
                                $tamaño = "usuario-diresa";

                            } elseif ($tamano_encabezados === 14) {
                                // $tamaño='13';
                                $datos_buscados = array("Fecha", "ID", "Nombre Usuario", "Departamento", "Entrada - Salida 1", "Entrada - Salida 2", "Entrada - Salida 3", "Entrada - Salida 4", "Entrada - Salida 5", "Entrada - Salida 6", "Entrada - Salida 7", "Entrada - Salida 8");
                                // $posiciones = array();

                                foreach ($datos_buscados as $dato) {
                                    $posicion = array_search($dato, $encabezados);
                                    if ($posicion !== false) {
                                        $posiciones[$dato] = $posicion;
                                        // $tamaño = $tamaño .'-'. $posiciones[$dato];
                                    }
                                }
                                $tamaño = "asistencia-diresa";


                            } else {
                                // $tamaño='fuera de rango';
                                $tamaño = "fallo";
                            }

                            
                            // $encabezado2_posicion = array_search('encabezado2', $encabezados);
                            // $encabezado4_posicion = array_search('encabezado4', $encabezados);
                            // $mensaje='El archivo se ha subido correctamente.'.'Se añadieron';
                            // $icono='success';
                            
                           
                        }
                        else if($extension === 'xls' || $extension === 'xlsx'){

                            // $primer_fila  = fgetcsv($archivo); // Obtener encabezados
                            // $tamano_encabezados = count($encabezados);

                            // $encabezados = $primer_fila[0]; // lo guardo en un array
                            // los de samu

                            if ($tamano_encabezados == 15) {
                                $tamaño="asistencia-samu";
                                $datos_buscados = array("Fecha", "ID", "Nombre Usuario", "Departamento", "Entrada - Salida 1", "Entrada - Salida 2", "Entrada - Salida 3", "Entrada - Salida 4", "Entrada - Salida 5", "Entrada - Salida 6", "Entrada - Salida 7", "Entrada - Salida 8");
                                foreach ($datos_buscados as $dato) {
                                    $posicion = array_search($dato, $encabezados);
                                    if ($posicion !== false) {
                                        $posiciones[$dato] = $posicion;
                                        // $tamaño = $tamaño .'-'. $posiciones[$dato];
                                    }
                                }
                            }else{
                                $tamaño="usuario-samu";
                            }
                           
                        }else{
                            // 
                            $mensaje='No se pudo abrir el archivo CSV.';
                            $icono='error';
                            $tamaño="sin tamaño";
                            
                        }

                       
                        if($tamaño=="usuario-diresa"||$tamaño=="asistencia-diresa"||$tamaño=="usuario-samu"||$tamaño=="asistencia-samu"){
                            // 
                            // 
                            // 
                            // 
                            $mensaje='Se registro '.$tamaño ;
                            $icono='success';


                            if($extension=="csv"){
                                fclose($archivo);
                            }
                            

                           
                        }else{
                            $mensaje='EL formato Ingresado del Archivo es Incorrecto.';
                            $icono='warning';
                            $tamaño =  'y su extension es '. $tamano_encabezados;
                        }
                       


                        // Lee cada línea del archivo CSV
                    
                        // while (($fila = fgetcsv($archivo)) !== false) {
                        // // Aquí puedes incluir el código para procesar el archivo CSV o XLSX
                        //     // Procesa los datos de la fila
                        //     // Aquí deberías implementar la lógica para extraer los datos relevantes de la fila
                        //     // y registrarlos en tu base de datos
                        //     // $id_usuario = $fila[0];
                        //     // $nombre = $fila[1];
                        //     // $departamento = $fila[2];
                        //     // Continúa extrayendo otros campos según sea necesario y regístralos en la base de datos
                        //     $numerofilas++;

                        // }
                        // $mensaje='El archivo se ha subido correctamente.'.$nombreArchivoExtension.'Se añadieron'.$numerofilas;
                        // $icono='success';
                        // Cierra el archivo
                       
                    } else {
                        // Maneja el caso en que no se pudo abrir el archivo
                       
                        $mensaje='No se pudo abrir el archivo CSV.';
                        $icono='error';
                    }


                    
                } else {
                    $mensaje='El archivo debe ser CSV o XLSX.';
                    $icono='warning';
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


    public function importar(){
       
        $mensaje='';
        $icono='';
        $tamaño='';

        $cantidad_ignorados=0;
        $cantidad_registrado=0;
        $cantidad_actualizado=0;
        $cantidad_bug=0;
        

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
                    
                    // $reader = IOFactory::createReader('Csv');
                    // $reader->setDelimiter(',');
                    // $reader->setEnclosure('"');
                    // $reader->setSheetIndex(0);
                    // $reader->setInputEncoding('UTF-8');
                    // $spreadsheet = $reader->load($archivo);
    
                    // // Obtener los datos del archivo CSV
                    // $sheetData = $spreadsheet->getActiveSheet()->toArray();
    
                    // // Mostrar los datos obtenidos
                    // $tamaño= print_r($sheetData);
                   

                    $file = fopen($archivo, 'r');

                    // $contenido_csv = file_get_contents($archivo);
                    // $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
                    // $primer_fila  = str_getcsv($filas_csv[0]); // Obtener encabezados
                    // $encabezados = array();
                    $contenido_csv = file_get_contents($archivo);
                    $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
                    $encabezados = array();
                    $datos_columnas = array();
                    
                   
                 

                    if ($file) {
                        for ($i = 1; $i < count($filas_csv); $i++) {
                            $fila = $filas_csv[$i];
                            
                            // Obtener los valores de la fila
                            // $valores_fila = str_getcsv($fila);
                            $valores_separados = explode(',', $fila);
                            $tamaño_1 = count($valores_separados);

                            if($tamaño_1==20){
                                // $valores_separados[0];ID USUARIO
                                // $valores_separados[1];NOMBRE
                                // $valores_separados[2];DEPARTAMENTO
                                $result=$this->model->getTrabajador($valores_separados[0]);
                                if (empty($result)) {
                                    $cantidad_registrado++;
                                     
                                }else{
                                    $cantidad_ignorados++;
                                    $dato=$dato.'-|-'. $valores_separados[0];
                                   
                                }
                                
                            }
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

                                // DIRESA/276
                                // DIRESA/CONTRALORIA
                                // DIRESA/CONTRATADO REGUL
                                // DIRESA/CONTRATADO TEMPORAL
                                // DIRESA/CONTRATO COVID A CAS
                                // DIRESA/DESIGNADOS
                                // DIRESA/DESTACADOS
                                // DIRESA/NOMBRADO

                                // DIRESA/PASIVOS                           S
                                // DIRESA/PASIVOS /CESADOS DESIGNA          S
                                // DIRESA/PASIVOS /CESADOS DIRESA           S
                                // DIRESA/PASIVOS /CESADOS PRACTICANTES     S

                                // DIRESA/PRACTICANTES
                                // DIRESA/PROGRAMA_CANCER_DESA
                                // DIRESA/PROYECTOS                         S
                                // DIRESA/REPUEST JUD
                                // RED SALUD
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
                                    $cantidad_registrado++;
                                }
                                else{
                                    $cantidad_bug++;
                                    // $dato = $valores_separados[0] . "--" . $valores_separados[1] . "--" . $valores_separados[2];
                                    
                                }   


                            }
                            
                           

                            // Obtener solo los valores de las columnas 0, 1 y 2
                            // $valores_columnas_deseadas = array($valores_separados[0], $valores_separados[1], $valores_separados[2]);
                            
                            // Guardar los valores de las columnas en un array
                            // $datos_columnas[] = $valores_columnas_deseadas;
                            
                            // Imprimir los valores de las columnas

                           
                            
                        }
                        fclose($file);
                        // print_r($tamano_encabezados);
                        // $tamaño =$tamaño_1;
                        // foreach ($datos_columnas as $fila) {
                        //     $tamaño .= implode(' | ', $fila) . "<br>";
                        // }
                        
                        // $tamaño=var_dump($datos_columnas);
                        // $mensaje = "Hola desde PHP!";

                        $mensaje='El archivo es CSV.'.var_dump($dato) ;
                        $icono='success';
                        $tamaño='Registrados:'.$cantidad_registrado .'|Ignorados:'.$cantidad_ignorados .'|Bugs:'.$cantidad_bug ;
                       
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
                    $mensaje='El archivo es XLSX.';
                    $icono='success';
                    $tamaño='';
                    break;
                default:
                    $mensaje='El archivo debe ser CSV o XLSX.';
                    $icono='success';
                    $tamaño='';
                    break;
                }

                // if ($extension === 'csv' || $extension === 'xls'|| $extension === 'xlsx') {

                    
                    
                //     $spreadsheet = IOFactory::load($nombreArchivoExtension);
                  
                //       $hoja = $spreadsheet->getActiveSheet();
                //     // $encabezados = $hoja->rangeToArray('A1:' . $hoja->getHighestColumn() . '1', null, true, true, true);
                //     // $datosPrimeraFila = reset($encabezados);
                //     $tamano_encabezados = ''; 
                //     $posiciones = [];
                        
                //     // Verifica si el archivo se abrió correctamente
                //     if ($spreadsheet !==null) {

                //         if($extension === 'csv'){
                //             $reader = new Csv();
                //             $reader->setDelimiter(',');
                //             $reader->setEnclosure('"');
                //             $reader->setSheetIndex(0);
                //             $reader->setInputEncoding('utf8_encode');

                //             $spreadsheet = $reader->load($nombreArchivoExtension);
                //             $sheetData = $spreadsheet->getActiveSheet()->toArray();



                //             // $encabezadosArray = explode(',', $encabezados);
                //             // $encabezadosArrayOutput = print_r($encabezadosArray, true);

                //             // // $datosSeparados = [];
                //             // // foreach ($encabezadosArray as $indice => $valor) {
                //             // //     $datosSeparados[$indice + 1] = $valor;
                //             // //     $tamaño .= $valor ;
                //             // // }
                //             // $datosPrimeraFila = [];
                //             // foreach ($hoja->getRowIterator(1, 1) as $fila) {
                //             //     foreach ($fila->getCellIterator() as $celda) {
                //             //         $datosPrimeraFila[] = $celda->getValue();
                //             //         $tamaño.='-'.$celda->getValue();
                //             //     }
                //             // }
                //             // $tamaño = $encabezados;

                            
                //             $data_aux = [];
                //             foreach ($sheetData as $row) {
                //                 $row_aux = [];
                //                 foreach ($row as $cell) {
                //                     if ($cell !== null) {
                //                         $row_aux[] = mb_convert_encoding(strtoupper($this->remove_accents($cell)), "UTF-8");
                //                     } else {
                //                         $row_aux[] = null; // Otra acción si el valor es null
                //                     }
                //                 }
                //                 $data_aux[] = $row_aux;
                //             }
                //                 $tamaño=print_r($data_aux);
                               
                           


                //             // $tamano_encabezados = count($datosSeparados);
                          

                //             $ultimaFila = $hoja->getHighestRow();
                //             $ultimaColumna = $hoja->getHighestColumn();
                //             $tipo_archivo = "usuario-diresa";
        
                //             // if ($tamano_encabezados === 20) {
                //             //     // $tamaño='19';
                //             //     // $idUsuario = array_search("ID Usuario", $encabezados);
                //             //     // $nombre = array_search("Nombre", $encabezados);
                //             //     // $departamento = array_search("Departamento", $encabezados);

                //             //     $datos_buscados = array("ID Usuario", "Nombre", "Departamento");
                //             //     // $posiciones = array();

                //             //     $tipo_archivo = "usuario-diresa";
                //             //     foreach ($datos_buscados as $dato) {
                //             //         $posicion = array_search($dato, $encabezadosArray);
                //             //         if ($posicion !== false) {
                //             //             // Si se encuentra el encabezado, almacenar su posición
                //             //             $posiciones[$dato] = $posicion;
                //             //         }
                //             //     }
                //             //     for ($fila = 2; $fila <= $ultimaFila; $fila++) {
                //             //         // Obtener el valor de la celda en la columna A (primera columna)
                //             //         $valorCelda = $hoja->getCell('A' . $fila)->getValue();
                                    
                //             //         // Separar los datos por comas y almacenarlos en un array
                //             //         $datosFila = explode(',', $valorCelda);
                                    
                //             //         // Almacenar la fila en $datosCSV
                //             //         $datosCSV[] = $datosFila;
                //             //         foreach ($datosCSV as $fila) {
                                       
                //             //             foreach ($fila as $dato) {
                                          
                //             //                  $tamaño =$tamaño+ $dato;

                                            
                                            
                //             //             }
                //             //         }
                //             //     }
                //             // }else{
                //             //     $tipo_archivo = "fallo";
                //             // }

                //         }
                //         else if($extension === 'xls' || $extension === 'xlsx'){
                //             // $tamano_encabezados = count($datosPrimeraFila); 

                //             // $primer_fila  = fgetcsv($archivo); // Obtener encabezados
                //             // $tamano_encabezados = count($encabezados);

                //             // $encabezados = $primer_fila[0]; // lo guardo en un array
                //             // los de samu

                //         }else{
                //             // 
                //             $mensaje='No se pudo abrir el archivo CSV.';
                //             $icono='error';
                //             $tamaño="sin tamaño";
                            
                //         }

                       
                //         if($tipo_archivo=="usuario-diresa"||$tipo_archivo=="asistencia-diresa"||$tipo_archivo=="usuario-samu"||$tipo_archivo=="asistencia-samu"){
                //             // 
                //             // 
                //             // 
                //             // 
                //             $mensaje='Se registro '.$tamano_encabezados ;
                //             $icono='success';
                //             // $tamaño = $tamano_encabezados;

                           
                //         }else{
                //             $mensaje='EL formato Ingresado del Archivo es Incorrecto.';
                //             $icono='warning';
                //             // $tamaño =  'y su extension es '. $tamano_encabezados;
                //         }
                       


                //         // Lee cada línea del archivo CSV
                    
                //         // while (($fila = fgetcsv($archivo)) !== false) {
                //         // // Aquí puedes incluir el código para procesar el archivo CSV o XLSX
                //         //     // Procesa los datos de la fila
                //         //     // Aquí deberías implementar la lógica para extraer los datos relevantes de la fila
                //         //     // y registrarlos en tu base de datos
                //         //     // $id_usuario = $fila[0];
                //         //     // $nombre = $fila[1];
                //         //     // $departamento = $fila[2];
                //         //     // Continúa extrayendo otros campos según sea necesario y regístralos en la base de datos
                //         //     $numerofilas++;

                //         // }
                //         // $mensaje='El archivo se ha subido correctamente.'.$nombreArchivoExtension.'Se añadieron'.$numerofilas;
                //         // $icono='success';
                //         // Cierra el archivo
                       
                //     } else {
                //         // Maneja el caso en que no se pudo abrir el archivo
                       
                //         $mensaje='No se pudo abrir el archivo CSV.';
                //         $icono='error';
                //     }


                    
                // } else {
                //     $mensaje='El archivo debe ser CSV o XLSX.';
                //     $icono='warning';
                // }
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
