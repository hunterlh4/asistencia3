<?php
class Usuarios extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {
        // $data = $this->model->getUsuarioId($_SESSION['id']);
        $data['title'] = 'usuarios';
        // $data1="";
    
        $data1 = $this->model->getTrabajadores();

        $this->views->getView('administracion', "users", $data,$data1);
    }
    public function listar()
    {
        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
        
            $datonuevo = $data[$i]['usuario_estado'];
            if($datonuevo==true){
                $data[$i]['usuario_estado'] ="<div class='badge badge-info'>Activo</div>";  
            }else{
                $data[$i]['usuario_estado'] ="<div class='badge badge-danger'>Inactivo</div>";
            }
            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-success" type="button" onclick="editUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-edit"></i></button>
            <button class="btn btn-danger" type="button" onclick="verUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            
        </div>';
            // colocar eliminar si es necesario
        
        }
        echo json_encode($data);
        die();
    }

    public function listar2()
    {
        $data = $this->model->getUsuarios2();
      
        echo json_encode($data);
        die();
    }
    


    public function registrar()
    {
        if (isset($_POST['nombre'])) {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $clave = $_POST['clave'];
            $id = $_POST['id'];
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            if (empty($_POST['nombre']) || empty($_POST['apellido'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                if (empty($id)) {
                    $result = $this->model->verificarCorreo($correo);
                    if (empty($result)) {
                        $data = $this->model->registrar($nombre, $apellido, $correo, $hash);
                        if ($data > 0) {
                            $respuesta = array('msg' => 'usuario registrado', 'icono' => 'success');
                        } else {
                            $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                        }
                    } else {
                        $respuesta = array('msg' => 'correo ya existe', 'icono' => 'warning');
                    }
                } else {
                    $data = $this->model->modificar($nombre, $apellido, $correo, $id);
                    if ($data == 1) {
                        $respuesta = array('msg' => 'usuario modificado', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'error al modificar', 'icono' => 'error');
                    }
                }
            }
            echo json_encode($respuesta);
        }
        die();
    }
    //eliminar user
    public function delete($idUser)
    {
        if (is_numeric($idUser)) {
            $data = $this->model->eliminar($idUser);
            if ($data == 1) {
                $respuesta = array('msg' => 'usuario dado de baja', 'icono' => 'success');
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
    public function edit($idUser)
    {
        if (is_numeric($idUser)) {
            $data = $this->model->getUsuario($idUser);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
