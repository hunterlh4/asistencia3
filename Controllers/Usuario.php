<?php
class Usuario extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['usuario_autenticado']) || ($_SESSION['usuario_autenticado'] != "true")) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
        if($_SESSION['nivel'] !==1 &&  $_SESSION['nivel'] !==100){
            header('Location: ' . BASE_URL . 'errors');
            exit;
        }
    }
    public function index()
    {
        
        // $data = $this->model->getUsuarioId($_SESSION['id']);
        $data['title'] = 'usuario';
        // $data1="";

        // $data1 = $this->model->getTrabajadores();
        $data1 = '';
        $this->views->getView('Administracion', "Usuario", $data, $data1);
    }
    public function listar()
    {
        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['contador'] = $i + 1;
            $datonuevo = $data[$i]['usuario_estado'];
            if ($datonuevo == 'Activo') {
                $data[$i]['usuario_estado'] = "<div class='badge badge-info'>Activo</div>";
            }
            if ($datonuevo == 'Inactivo') {
                $data[$i]['usuario_estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            }
            if ($datonuevo == 'Pendiente') {
                $data[$i]['usuario_estado'] = "<div class='badge badge-warning'>Pendiente</div>";
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

        echo json_encode($data1, JSON_UNESCAPED_UNICODE);
        die();
    }



    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['username'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $nivel = $_POST['nivel'] ?? '';
            $password = $_POST['password'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $trabajador_id = $_POST['trabajadores'] ?? '';
            $direccion_id = $_POST['direccion'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $id = $_POST['id'] ?? '';
            $error_msg = '';
            $hash = password_hash($password, PASSWORD_DEFAULT);


            $datos_adicionales = array(
                "usuario" => $usuario,
                "password" => $password,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "nivel" => $nivel,
                "trabajador_id" => $trabajador_id,
                "estado" => $estado
            );
            $datos_adicionales_json = json_encode($datos_adicionales);
            
            $accion = $id ? 'editar' : 'crear';

            if(empty($trabajador_id)){
                $error_msg .= 'El <b>Trabajador</b> es obligatorio.<br>';
            }
            if (empty($direccion_id)) {
                $error_msg .= "Debes seleccionar una dirección.<br>";
            }
            if (empty($usuario)) {
                $error_msg .= 'El <b>Usuario</b> es obligatorio.<br>';
            }
            if ($accion == 'crear' && empty($password)) {
                $error_msg .= 'El <b>Contraseña</b> es obligatorio.<br>';
            }
            if (empty($nombre)) {
                $error_msg .= 'El <b>Nombre</b> es obligatorio.<br>';
            }
            if (empty($apellido)) {
                $error_msg .= 'El <b>Apellido</b> es obligatorio.<br>';
            }
            if (empty($nivel)) {
                $error_msg .= 'El <b>Nivel</b> es obligatorio.<br>';
            }
            if (!preg_match('/^\d{8}$/', $dni)) {
                $error_msg .= "El DNI debe tener exactamente 8 dígitos.<br>";
            }
            if (empty($fecha_nacimiento)) {
                $error_msg .= "La fecha de nacimiento es obligatoria.<br>";
            }
            

            if (!preg_match('/^[a-zA-Z0-9]{5,16}$/', $usuario)&& $usuario) {
                $error_msg .= 'El "usuario" de usuario debe tener entre 5 y 16 caracteres y solo contener letras y números.<br>';
            }
        
            if ((strlen($nombre) < 3 || strlen($nombre) > 30)&& $nombre ) {
                $error_msg .= 'El <b>Nombre</b> debe tener entre 3 y 30 caracteres.<br>';
            }
            
            // Validación del nombre por caracteres permitidos
            if (!preg_match('/^[A-ZÑ\s]+$/', $nombre) && $nombre) {
                $error_msg .= 'El <b>Nombre</b> solo debe contener letras mayúsculas.<br>';
            }
            
            // Validación del apellido por longitud
            if ((strlen($apellido) < 5 || strlen($apellido) > 30) && $apellido) {
                $error_msg .= 'El <b>Apellido</b> debe tener entre 5 y 30 caracteres.<br>';
            }
            
            // Validación del apellido por caracteres permitidos
            if (!preg_match('/^[A-ZÑ\s]+$/', $apellido) && $apellido) {
                $error_msg .= 'El <b>Apellido</b> solo debe contener letras mayúsculas.<br>';
            }
            // Validación del usuario
          
            $result = $this->model->verificarUsuario($usuario);

            if (($accion == 'crear' && !empty($result)) || ($accion == 'editar' && !empty($result) && $result['id'] != $id)) {
                $error_msg .= 'El <b>Usuario</b> ya existe.<br>';
            }

            if (!empty($password) && (strlen($password) < 5 || strlen($password) > 20)) {
                $error_msg .= 'El campo "Contraseña" debe tener entre 5 y 20 caracteres.<br>';
            }
        
            if (!empty($error_msg)) {
                echo json_encode(["icono" => "error", "msg" => $error_msg]);
                exit;
            }

            if ($accion == 'crear') {
                $data = $this->model->registrar($usuario, $hash, $nombre, $apellido, $nivel,$trabajador_id,$direccion_id,$fecha_nacimiento,$dni);
                if ($data > 0) {
                    $respuesta = ['msg' => 'usuario registrado', 'icono' => 'success'];
                    $this->model->registrarlog($_SESSION['id'], 'Crear', 'Usuario', $datos_adicionales_json);
                    $this->actualizarTrabajador($trabajador_id,$nombre, $apellido,$dni,$fecha_nacimiento,$direccion_id);
                } else {
                    $respuesta = ['msg' => 'error al registrar', 'icono' => 'error'];
                }
            }
            if ($accion == 'editar') {
                $clave = $this->model->getUsuario($id);
                if ($trabajador_id == '0') {
                    $trabajador_id = null;
                }
                if (empty($password)) {
                    $hash = $clave['password'];
                }
                $data = $this->model->modificar($usuario, $hash, $nombre, $apellido, $nivel, $trabajador_id,$estado,$direccion_id,$fecha_nacimiento,$dni, $id);
                $this->actualizarTrabajador($trabajador_id,$nombre, $apellido,$dni,$fecha_nacimiento,$direccion_id);
                if ($data == 1) {
                    $respuesta = ['msg' => 'Usuario modificado', 'icono' => 'success'];
                    $this->model->registrarlog($_SESSION['id'], 'Modificar', 'Usuario', $datos_adicionales_json);
                } else {
                    $respuesta = ['msg' => 'Error al modificar el usuario', 'icono' => 'error'];
                }
            }

            echo json_encode($respuesta);
            exit;
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
            $data1 = $this->model->getUsuario($idUser);
            $data2 = $this->model->getTrabajadoresconBuscar($idUser);
            $response = [
                'usuario' => $data1,
                'trabajadores' => $data2
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function actualizarTrabajador($trabajador_id,$nombre,$apellido,$dni,$nacimiento,$direccion_id){
        
        $this->model->modificarTrabajador($nombre,$apellido,$dni,$nacimiento,$direccion_id,$trabajador_id);
        
    }
}
