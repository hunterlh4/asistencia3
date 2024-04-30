<?php
class Trabajador extends Controller
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

        $data['title'] = 'Trabajador';

        $this->views->getView('Administracion', "Trabajador", $data);
    }
    public function listar()
    {
        $data = $this->model->getTrabajadores();

        for ($i = 0; $i < count($data); $i++) {
            $data_estado = $data[$i]['testado'];
            $data_cargo = $data[$i]['cnombre'];
            $data_regimen = $data[$i]['rnombre'];
            if($data_cargo=='SIN ASIGNAR'){
                $data[$i]['cnombre'] = '-';
            }
            if($data_regimen=='SIN ASIGNAR'){
                $data[$i]['rnombre'] = '-';
            }
            if ($data_estado == 'Activo') {
                $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
            } else {
                $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            }
            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="edit(' . $data[$i]['tid'] . ')"><i class="fas fa-edit"></i></button>
            <button class="btn btn-danger" type="button" onclick="view(' . $data[$i]['tid'] . ')"><i class="fas fa-eye"></i></button>
            </div>';
        }
        echo json_encode($data);
        return $data;
    }

    public function listarDireccion()
    {
        $data1 = $this->model->getDireccion();
      
        echo json_encode($data1,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarCargo()
    {
        $data1 = $this->model->getCargo();
      
        echo json_encode($data1,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarHorario()
    {
        $data1 = $this->model->getHorario();
      
        echo json_encode($data1,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarRegimen()
    {
        $data1 = $this->model->getRegimen();
      
        echo json_encode($data1,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        if ((isset($_POST['nombre']))) {

            $nombre = $_POST['nombre'];
            $entrada = $_POST['entrada'];
            $salida = $_POST['salida'];
            $estado = $_POST['estado'];
            $total = $_POST['total'];
            $id = $_POST['id'];

            if(empty($_SESSION['id_temporal'])){
                $horario_id = 'vacio';
            }else{
                $horario_id =  $_SESSION['id_temporal'];
            }
            

            $datos_log = array(
                "nombre" => $nombre,
                "horario_id" => $horario_id,
                "entrada" => $entrada,
                "salida" => $salida,
                "total" => $total,
                "estado" => $estado,

            );
            $datos_log_json = json_encode($datos_log);

            if ((empty($nombre) || (empty($entrada) || (empty($salida))))) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $error_msg = '';
                if (strlen($nombre) < 5 || strlen($nombre) > 50) {
                    $error_msg .= 'El Horario debe tener entre 5 y 50 caracteres. <br>';
                }
                if($horario_id=='vacio'){
                    $error_msg .= 'Debe de Seleccionar Un Horario para Agregar un Detalle. <br>';
                }

                if (!empty($error_msg)) {

                    $respuesta = array('msg' => $error_msg, 'icono' => 'warning');
                } else {
                    // VERIFICO LA EXISTENCIA
                    
                    // AQUI VEO LA DIFERENCIA DE HORARIOS

                    // REGISTRAR
                    if (empty($id)) {

                        $data = $this->model->registrar($nombre, $horario_id, $entrada, $salida,$total);

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
                        $data = $this->model->modificar($nombre, $horario_id, $entrada, $salida,$total, $estado, $id);
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
            $data = $this->model->getTrabajadores($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function view($id)
    {
        if (is_numeric($id)) {
            $_SESSION['id_temporal'] =  $id  ;
        }
        die();
    }
}
