<?php
class Boleta extends Controller
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

        $data['title'] = 'Boleta';
        $data1 = '';
        
        $this->views->getView('Administracion', "Boleta", $data, $data1);
    }
    public function listar()
    {
        $data = $this->model->getBoletas();
        for ($i = 0; $i < count($data); $i++) {
            $numero = $data[$i]['numero'];
            if($data[$i]['numero']==null){
                $numero = '0';
            }
          
            
            $numero_formateado = str_pad($numero, 7, '0', STR_PAD_LEFT);
            $data[$i]['numero'] = $numero_formateado;


            $fecha_inicio = $data[$i]['fecha_inicio'];
            $fecha_fin = $data[$i]['fecha_fin'];

            $fecha_inicio = date('d-m-Y', strtotime($fecha_inicio));
            $fecha_fin = date('d-m-Y', strtotime($fecha_fin));

            if($fecha_inicio == $fecha_fin){
                $data[$i]['fecha_nueva']= $fecha_inicio;
            }else{
                $data[$i]['fecha_nueva'] = $fecha_inicio .'<br>'.$fecha_fin;
            }
            

            // $datonuevo = $data[$i]['bestado'];
            // if ($datonuevo == 'Activo') {
            //     $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
            // } else {
            //     $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            // }

            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="edit(' . $data[$i]['bid'] . ')"><i class="fas fa-edit"></i></button>
           
            </div>';
            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        if (isset($_POST['solicitante']) && isset($_POST['aprobador'])&& isset($_POST['fecha_inicio'])&& isset($_POST['fecha_fin'])&& isset($_POST['hora_salida'])&& isset($_POST['hora_entrada'])&& isset($_POST['razon'])) {
            $id = $_POST['id'];
            $solicitante = $_POST['solicitante'];
            $aprobador = $_POST['aprobador'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin =$_POST['fecha_fin'];
            $salida = $_POST['hora_salida'];
            $entrada = $_POST['hora_entrada'];
            $razon =$_POST['razon'];

            if($razon =='Otra'){
                $razon =$_POST['otra_razon'];
            }
            if(empty($solicitante)||empty($aprobador)||empty($fecha_inicio)||empty($salida)||empty($entrada)||empty($razon)){
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            }else{

                $datos_log = array(
                    "id" => $id,
                    "solicitante" => $solicitante,
                    "aprobador" => $aprobador,
                    "fecha_inicio" => $fecha_inicio,
                    "fecha_fin" => $fecha_fin,
                    "salida" => $salida,
                    "entrada" => $entrada,
                    "razon" => $razon,
                   
                );
                $datos_log_json = json_encode($datos_log);
                
                if (empty($id)) {
                    $estado_tramite = 'sin_enviar';
                    // $data = $solicitante.'|'. $aprobador.'|'.$fecha_inicio.'|'.$fecha_fin.'|'.$salida.'|'.$entrada.'|'.$razon.'|'.$estado_tramite;
                    $data = $this->model->registrar($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$estado_tramite);
                    if ($data > 0) {
                        $respuesta = array('msg' => 'Boleta registrada', 'icono' => 'success');
                        // $this->model->registrarlog($_SESSION['id'],'Crear','Boleta', $datos_log_json);
                    } else {
                        $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                    }
                    // $respuesta = array('msg' => 'modificar', 'icono' => 'success');
                }else{
                    $result = $this->model->verificar($id);
                    if($result['estado_tramite']='sin_enviar'){
                        $data = $this->model->modificar($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$id);
                        if ($data > 0) {
                            $respuesta = array('msg' => 'Boleta Actualizar', 'icono' => 'success');
                            // $this->model->registrarlog($_SESSION['id'],'Actualizar','Boleta', $datos_log_json);
                        } else {
                            $respuesta = array('msg' => 'error al Actualizar', 'icono' => 'error');
                        }
                    }else{
                        $respuesta = array('msg' => 'La Solicitud ya fue enviada, Espere su Respuesta', 'icono' => 'error');
                    }
                    // $data = $this->model->modificar($nombre, $nivel);
                   
                    // $respuesta = array('msg' => 'Actualizar', 'icono' => 'success');
                }   
            }
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }else{
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
           
            echo json_encode(array('error' => 'Se requieren los parámetros faltan'));
           
        }
       die();
    }
    //eliminar user
    public function delete($id)
    {
      
    }
    //editar user
    public function edit($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->getBoleta($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    //eliminar user
    public function buscarPorFecha()
    {
        if (isset($_POST['fecha']) && isset($_POST['trabajador_id'])) {
            $fecha = $_POST['fecha'] ; 
            $trabajador_id = $_POST['trabajador_id'] ; 
           
                // Llama al modelo para obtener los datos de asistencia
            $data = $this->model->getBoletaPorFecha($fecha, $trabajador_id);
    
                // Devuelve los datos como JSON
            echo json_encode($data, JSON_UNESCAPED_UNICODE);

            
                
            
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            echo json_encode(array('error' => 'Se requieren los parámetros "id", "anio" y "mes"'));
        }
    
        // Detiene la ejecución del script
        die();
    }

    public function buscarPorFechaSola()
    {
        if (isset($_POST['trabajador_id'])) {
            
            $trabajador_id = $_POST['trabajador_id'] ; 
           
                // Llama al modelo para obtener los datos de asistencia
            $data = $this->model->getBoletaPorFechaSola($trabajador_id);
    
                // Devuelve los datos como JSON
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            echo json_encode(array('error' => 'Se requieren los parámetros "trabajador_id"'));
        }
    
        // Detiene la ejecución del script
        die();
    }
}
