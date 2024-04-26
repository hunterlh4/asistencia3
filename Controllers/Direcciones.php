<?php
class Direcciones extends Controller
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

        $data['title'] = 'Direccion';
        $data1 = '';
        $this->views->getView('Administracion', "Direcciones", $data, $data1);
    }
    public function listar()
    {
        $data = $this->model->getDirecciones();
        for ($i = 0; $i < count($data); $i++) {

            $datonuevo = $data[$i]['direccion_estado'];
            
            $data[$i]['direccion_equipo']= $data[$i]['direccion_nombre'].' '.$data[$i]['equipo_nombre'];
            
            if ($datonuevo == 'Activo') {
                $data[$i]['direccion_estado'] = "<div class='badge badge-info'>Activo</div>";
            } else {
                $data[$i]['direccion_estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            }

            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="editUser(' . $data[$i]['direccion_id'] . ')"><i class="fas fa-edit"></i></button>
           
            </div>';
            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }

    public function listarEquipos()
    {
        $data1 = $this->model->getEquipos();
      
        echo json_encode($data1,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        if ((isset($_POST['nombre']))){

            $nombre = $_POST['nombre'];
            $equipo_id = $_POST['equipo'];
            $estado = $_POST['estado'];
            $id = $_POST['id'];

            if($equipo_id=='0'){
                $equipo_id=null;
            }

            $datos_log = array(
                "nombre" => $nombre,
                "equipo_id" => $equipo_id,
                "estado" => $estado,
               
            );
           
            $datos_log_json = json_encode($datos_log);

            if (empty($nombre)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $error_msg = '';
                if (strlen($nombre) < 5 || strlen($nombre) > 50) {
                    $error_msg .= 'El Equipo debe tener entre 5 y 50 caracteres. <br>';
                }

                if (!empty($error_msg)) {
                    
                    $respuesta = array('msg' => $error_msg, 'icono' => 'warning');
                } else {
                    // VERIFICO LA EXISTENCIA
                    $result = $this->model->verificar($nombre);
                    // REGISTRAR
                    if (empty($id)) {
                        if (empty($result)) {
                            $data = $this->model->registrar($nombre,$equipo_id);

                            if ($data > 0) {
                                $respuesta = array('msg' => 'Direccion registrado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'],'Crear','Direccion', $datos_log_json);
                            } else {
                                $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                            }
                        } else {
                            $respuesta = array('msg' => 'Direccion en uso', 'icono' => 'warning');
                        }
                        // MODIFICAR
                    } else {
                        if ($result) {
                            if ($result['id'] != $id) {
                                $respuesta = array('msg' => 'Direccion en uso', 'icono' => 'warning');
                            } else {
                                // El nombre de usuario es el mismo que el original, se permite la modificación
                                $data = $this->model->modificar($nombre,$equipo_id, $estado, $id);
                                if ($data == 1) {
                                    $respuesta = array('msg' => 'Direccion modificado', 'icono' => 'success');
                                    $this->model->registrarlog($_SESSION['id'],'Modificar','Direccion', $datos_log_json);
                                } else {
                                    $respuesta = array('msg' => 'Error al modificar', 'icono' => 'error');
                                }
                            }
                        } else {
                            // El usuario no existe, se permite la modificación
                            $data = $this->model->modificar($nombre,$equipo_id, $estado, $id);
                            if ($data == 1) {
                                $respuesta = array('msg' => 'Direccion modificado', 'icono' => 'success');
                                $this->model->registrarlog($_SESSION['id'],'Modificar','Direccion', $datos_log_json);
                            } else {
                                $respuesta = array('msg' => 'Error al modificar el Direccion', 'icono' => 'error');
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
            $data = $this->model->getDireccion($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
