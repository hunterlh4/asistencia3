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
    
        // $data1 = $this->model->getTrabajadores();
        $data1 = '';
        $this->views->getView('administracion', "users", $data,$data1);
    }
    public function listar()
    {
        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
        
            $datonuevo = $data[$i]['usuario_estado'];
            if($datonuevo=='Activo'){
                $data[$i]['usuario_estado'] ="<div class='badge badge-info'>Activo</div>";  
            }else{
                $data[$i]['usuario_estado'] ="<div class='badge badge-danger'>Inactivo</div>";
            }
            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="editUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-edit"></i></button>
           
            </div>';
            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
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
    
    public function listartrabajadores()
    {
        $data1 = $this->model->getTrabajadores();
      
        echo json_encode($data1,JSON_UNESCAPED_UNICODE);
        die();
    }
    


    public function registrar()
    {
        if (isset($_POST['username'])) {
            $usuario = $_POST['username'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $nivel = $_POST['nivel'];
            $password = $_POST['password'];
            $estado = $_POST['estado'];
            $trabajador_id = $_POST['trabajadores'];
            $id = $_POST['id'];
            $hash = password_hash($password, PASSWORD_DEFAULT);

           
            
            if (empty($_POST['nivel']) ){//|| empty($_POST['password'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                if (empty($id)) {
                    $result = $this->model->verificarUsuario($usuario);
                    
                    if (empty($result)) {

                     
                        $data = $this->model->registrar($usuario,$hash,$nombre, $apellido, $nivel, $trabajador_id);
                        
                        if ($data > 0) {
                            $respuesta = array('msg' => 'usuario registrado', 'icono' => 'success');
                        } else {
                            $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                        }
                    } else {
                        $respuesta = array('msg' => 'Usuario ya existe', 'icono' => 'warning');
                    }
                } else {
                    $result = $this->model->verificarUsuario($usuario);
                    $clave = $this->model->getUsuario($id);

                    if($trabajador_id=='0'){
                        $trabajador_id=null;
                    }
                    if($password==''){
                        $hash= $clave['password'];
                    }
                    

                    if ($result) {
                        if ($result['id'] != $id) {
                            $respuesta = array('msg' => 'El nombre de usuario ya está en uso', 'icono' => 'warning');
                        } else {
                            // El nombre de usuario es el mismo que el original, se permite la modificación
                            $data = $this->model->modificar($usuario, $hash, $nombre, $apellido, $nivel, $trabajador_id, $estado, $id);
                            if ($data == 1) {
                                $respuesta = array('msg' => 'Usuario modificado', 'icono' => 'success');
                            } else {
                                $respuesta = array('msg' => 'Error al modificar el usuario', 'icono' => 'error');
                            }
                        }
                    } else {
                        // El usuario no existe, se permite la modificación
                        $data = $this->model->modificar($usuario, $hash, $nombre, $apellido, $nivel, $trabajador_id, $estado, $id);
                        if ($data == 1) {
                            $respuesta = array('msg' => 'Usuario modificado', 'icono' => 'success');
                        } else {
                            $respuesta = array('msg' => 'Error al modificar el usuario', 'icono' => 'error');
                        }
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
