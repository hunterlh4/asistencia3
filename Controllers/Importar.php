<?php
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
    
    public function importar(){
       
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
                if ($extension === 'csv' || $extension === 'xls') {
                    // Procesa el archivo
                    
                    $numerofilas='';
                    $tipoImportacion='';
                    // aqui leo el archivo
                    
                    if($extension === 'csv') {
                        $archivo = fopen($nombreArchivoExtension, 'r');
                    }
                    if($extension === 'xls') {
                        // $excel = new COM('Excel.Application') or die('No se pudo iniciar Excel.');
                        // $libro = $excel->Workbooks->Open($nombreArchivoExtension);
                        // $hoja = $libro->Worksheets(1);
                        // // $primer_fila = $hoja->Range("A1")->EntireRow->Value;
                        // $rango = $hoja->Range("A1:Z1");
                        // $valores_fila = $rango->Value;
                        // $encabezados = array();
                        // foreach ($valores_fila[1] as $valor) {
                        //     $encabezados[] = $valor;
                        // }
                        // $posiciones = array();
                        // $tamano_encabezados = count($encabezados); 
                    }
                  
                    // $encabezados = explode(',', $primer_fila[0]); // lo guardo en un array
                    
                   

                    // Verifica si el archivo se abrió correctamente
                    if ($archivo !== false || $libro !== null) {

                        if($extension === 'csv'){

                            $contenido_csv = file_get_contents($nombreArchivoExtension);
                            // lo parseo
                            $filas_csv = str_getcsv($contenido_csv, "\n"); // Obtener filas
                            $primer_fila  = str_getcsv($filas_csv[0]); // Obtener encabezados
                            $encabezados = array();
                            foreach ($primer_fila as $valor) {
                                $encabezados[] = $valor;
                            }
                            $posiciones = array();
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
                        else if($extension === 'xls'){

                            $primer_fila  = fgetcsv($archivo); // Obtener encabezados
                            $tamano_encabezados = count($encabezados);

                            $encabezados = $primer_fila[0]; // lo guardo en un array
                            // los de samu
                            $tamaño=="usuario-samu";
                        }else{
                            // 
                            $mensaje='No se pudo abrir el archivo CSV.';
                            $icono='error';
                            
                        }

                       
                        if($tamaño=="usuario-diresa"||$tamaño=="asistencia-diresa"||$tamaño=="usuario-samu"||$tamaño=="asistencia-samu"){
                            $mensaje='Se registro '.$tamaño ;
                            $icono='success';
                        }else{
                            $mensaje='EL formato Ingresado del Archivo es Incorrecto.'.$tamano_encabezados;
                            $icono='warning';
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
                        fclose($archivo);
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
}
