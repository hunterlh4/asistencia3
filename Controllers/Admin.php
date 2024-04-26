<?php
class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (!empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Acceso al sistema';
        $this->views->getView('home', "login", $data);
        
    }
    public function validar()
    {
        $validar = "vacio";
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if (empty($_POST['username']) || empty($_POST['password'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $data = $this->model->getUsuario($_POST['username']);
                if (empty($data)) {
                    $respuesta = array('msg' => ' no existe', 'icono' => 'warning');
                } else {
                    if (password_verify($_POST['password'], $data['password'])) {
                        $_SESSION['id'] = $data['id'];
                        $_SESSION['username'] = $data['username'];
                        $_SESSION['nombre'] = $data['nombre'];
                        $_SESSION['apellido']  = $data['apellido'];
                        $_SESSION['nivel']  = $data['nivel'];

                        $validar = $this->model->usuario_conectado($data['id']);

                        if(empty($validar)) {
                           
                           
                            $this->model->registrar_conectado($data['id']);
                        } else {
                            // $validar no está vacío (es true)
                            // Realiza otra acción
                           
                            $this->model->modificar_conectado($data['id']);
                           
                        }

                        $respuesta = array('msg' => 'datos correcto', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'contraseña incorrecta', 'icono' => 'warning');
                    }
                }
            }
        } else {
            $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function perfil()
    {
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        
        

        $data = $this->model->getUsuarioId($_SESSION['id']);
        $data['id'] =  $_SESSION['id'];
        $data['title'] = 'Perfil';
        
       
        $data1="";
        // $data = $this->model->productosMinimos();
        // $data['pendientes'] = $this->model->getTotales(1);
        // $data['procesos'] = $this->model->getTotales(2);
        // $data['finalizados'] = $this->model->getTotales(3);
        // $data['productos'] = $this->model->getProductos();
        $this->views->getView('Administracion', "Profile", $data,$data1);
        
    }

    public function mensajes()
    {
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        $data['title'] = 'mensajes';
        $data['id'] =  $_SESSION['id'];
       
        $data1="";
        // $data = $this->model->productosMinimos();
        // $data['pendientes'] = $this->model->getTotales(1);
        // $data['procesos'] = $this->model->getTotales(2);
        // $data['finalizados'] = $this->model->getTotales(3);
        // $data['productos'] = $this->model->getProductos();
        $this->views->getView('Administracion', "Mensajes", $data,$data1);
        
    }
    public function home()
    {
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        $data['title'] = 'Administracion';
        $data['id'] =  $_SESSION['id'];
        $data['nivel'] =  $_SESSION['nivel'];
        $data['nombre'] =  $_SESSION['nombre'];
        $data['apellido'] =  $_SESSION['apellido'];
        $data1="";
        // $data = $this->model->productosMinimos();
        // $data['pendientes'] = $this->model->getTotales(1);
        // $data['procesos'] = $this->model->getTotales(2);
        // $data['finalizados'] = $this->model->getTotales(3);
        // $data['productos'] = $this->model->getProductos();
        $this->views->getView('Administracion', "Index", $data,$data1);
    }

    public function actualizar(){
        if (isset($_POST['new_pass_1']) && isset($_POST['new_pass_2'])) {
            if (empty($_POST['new_pass_1']) || empty($_POST['new_pass_2'])) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {
                $data = $this->model->getUsuarioIdclave($_SESSION['id']);
                $this->model->usuario_actualizar($data['id'],$_POST['new_pass_1']);
                
                $respuesta = array('msg' => 'datos actualizados', 'icono' => 'success');
            }
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }
    //  function conectado(){
    //    $validar = $this->model->usuario_conectado($_SESSION['id']);

    //    if(empty($validar)){
    //      $this->model->modificar_conectado($validar);
    //    }else{
    //      $this->model->registrar_conectado($validar);
    //    }
    //    die();
    // }
   

  

    // public function productosMinimos()
    // {
    //     if (empty($_SESSION['nombre_usuario'])) {
    //         header('Location: '. BASE_URL . 'admin');
    //         exit;
    //     }
    //     $data = $this->model->productosMinimos();
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    //     die();

    // }

    // public function topProductos()
    // {
    //     if (empty($_SESSION['nombre_usuario'])) {
    //         header('Location: '. BASE_URL . 'admin');
    //         exit;
    //     }
    //     $data = $this->model->topProductos();
    //     echo json_encode($data, JSON_UNESCAPED_UNICODE);
    //     die();

    // }

    public function salir()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
    }
}
