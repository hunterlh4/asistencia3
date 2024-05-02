<?php
class Seguimiento extends Controller
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

        $data['title'] = 'Seguimiento';

        $this->views->getView('Administracion', "Seguimiento", $data);
    }
    public function listar()
    {
        if (empty($_SESSION['id_seguimiento_trabajador'])) {
            $data = $this->model->getSeguimientos();
        
        } else {
            $id =  $_SESSION['id_seguimiento_trabajador'];
            $data = $this->model->getSeguimientoPorTrabajador($id);
        }
        for ($i = 0; $i < count($data); $i++) {

           
            $inicio = $data[$i]['fecha_inicio'];
            $fin = $data[$i]['fecha_fin'];

            if(!empty($inicio)){
                $inicio = date('d-m-Y', strtotime($inicio));
                $data[$i]['fecha_inicio_2'] = $inicio;
            }else{
                $data[$i]['fecha_inicio_2'] = '';
            }
            if(!empty($fin)){
                $fin = date('d-m-Y', strtotime($fin));
                $data[$i]['fecha_fin_2'] = $fin;
            }else{
                $data[$i]['fecha_inicio_2'] = '';
            }

            $datonuevo = $data[$i]['estado'];
            if ($datonuevo == 'Activo') {
                $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
            } else {
                $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            }

            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="Edit(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>

            </div>';
            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        return $data;
    }

    public function registrar()
    {
        if ((isset($_POST['nombre']))) {

            $regimen = $_POST['regimen'];
            $direccion = $_POST['direccion'];
            $cargo = $_POST['cargo'];
            $documento = $_POST['documento'];
            $sueldo = $_POST['sueldo'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];
            $id = $_POST['id'];

            if(empty($_SESSION['id_seguimiento_trabajador'])){
                $trabajador_id = 'vacio';
            }else{
                $trabajador_id =  $_SESSION['id_seguimiento_trabajador'];
            }

            $inicio='';
            $fin='';

            if (empty($fecha_inicio)) {
                $fecha_inicio = null;
                // $inicio = 'vacio';
            }else{
                $fecha_inicio = date('d-m-Y', strtotime($_POST['fecha_inicio']));
                
            }

            if (empty($fecha_fin)) {
                $fecha_fin = null;
                
            }else{
                $fecha_fin = date('d-m-Y', strtotime($_POST['fecha_fin']));
                
            }

            
            $datos_log = array(
                "id" => $id,
                "trabajador_id" => $trabajador_id,
                "regimen" => $regimen,
                "direccion" => $direccion,
                "cargo" => $cargo,
                "documento" => $documento,
                "sueldo" => $sueldo,
                "fecha_inicio" => $fecha_inicio,
                "fecha_fin" => $fecha_fin,
                "estado" => $estado,

            );
            $datos_log_json = json_encode($datos_log);

            if (((empty($regimen)) || (empty($direccion)) || (empty($cargo)) || (empty($documento)) || (empty($sueldo)) || (empty($fecha_inicio)) || (empty($fecha_fin)) )) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $error_msg = '';
                if (strlen($sueldo) < 2 || strlen($sueldo) > 7) {
                    $error_msg .= 'Ingrese un sueldo diferente. <br>';
                }
                if($trabajador_id=='vacio'){
                    $error_msg .= 'Debe de Seleccionar Un Trabajador para Agregar un Seguimiento. <br>';
                }

                if (!empty($error_msg)) {

                    $respuesta = array('msg' => $error_msg, 'icono' => 'warning');
                } else {
                    // VERIFICO LA EXISTENCIA
                    
                    // AQUI VEO LA DIFERENCIA DE HORARIOS

                    // REGISTRAR
                    if (empty($id)) {

                        $data = $this->model->registrar($trabajador_id,$regimen,$direccion, $salida,$total);

                        if ($data > 0) {
                            $respuesta = array('msg' => 'Detalle registrado', 'icono' => 'success');
                            $this->model->registrarlog($_SESSION['id'], 'Crear', 'Horario Detalle', $datos_log_json);
                        } else {
                            $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                        }
                        // MODIFICAR
                    } else {
                        // COLOCAR AQUI VALIDADOR QUE AL MODIFICAR DE ACTIVO A INACTIVO CAMBIE A NULL
                        // El nombre de usuario es el mismo que el original, se permite la modificación
                        $data = $this->model->modificar($nombre, $trabajador_id, $entrada, $salida,$total, $estado, $id);
                        if ($data == 1) {
                            $respuesta = array('msg' => 'Detalle modificado', 'icono' => 'success');
                            $this->model->registrarlog($_SESSION['id'], 'Modificar', 'Horario Detalle', $datos_log_json);
                        } else {
                            $respuesta = array('msg' => 'Error al modificar', 'icono' => 'error');
                        }
                    }
                }
            }
        }

        echo json_encode($respuesta);


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
            $data = $this->model->getSeguimiento($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
