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

    public function home()
    {
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin');
            exit;
        }
        $data['title'] = 'administracion';
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
        $this->views->getView('administracion', "index", $data,$data1);
    }

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
