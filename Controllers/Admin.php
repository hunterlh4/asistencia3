<?php
class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $sessionTime = 365 * 24 * 60 * 60; // 1 año de duración
        session_set_cookie_params($sessionTime);
        session_start();
    }
    public function index()
    { 
        if (!empty($_SESSION['usuario_autenticado'])) {
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
                $data = $this->model->getLogin(strtolower($_POST['username']));
                if (empty($data)) {
                    $respuesta = array('msg' => ' no existe', 'icono' => 'warning');
                } else {
                    if (password_verify(strtolower($_POST['password']), $data['password'])) {
                        $_SESSION['id'] = $data['id'];
                        $_SESSION['username'] = $data['username'];
                        $_SESSION['nombre'] = $data['nombre'];
                        $_SESSION['apellido']  = $data['apellido'];
                        $_SESSION['nivel']  = $data['nivel'];
                        $_SESSION['usuario_autenticado'] = "true";
                        session_regenerate_id();
                       
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
        $this->views->getView('Administracion', "Mensaje", $data,$data1);
        
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

    // public function pruebaalerta()
    // {
    //     $validar = "vacio";
    //     if (isset($_POST['username']) && isset($_POST['password'])) {
    //         if (empty($_POST['username']) || empty($_POST['password'])) {
    //             $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
    //         } else {
    //             $data = $this->model->getUsuario($_POST['username']);
    //             if (empty($data)) {
    //                 $respuesta = array('msg' => ' no existe', 'icono' => 'warning');
    //             } else {
                    
    //                     // $_SESSION['id'] = $data['id'];
    //                     // $_SESSION['username'] = $data['username'];
    //                     // $_SESSION['nombre'] = $data['nombre'];
    //                     // $_SESSION['apellido']  = $data['apellido'];
    //                     // $_SESSION['nivel']  = $data['nivel'];

    //                     $respuesta = array('msg' => 'datos correcto2', 'icono' => 'success');
                    
    //             }
    //         }
    //     } else {
    //         $respuesta = array('msg' => 'error desconocido', 'icono' => 'error');
    //     }
    //     echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    //     die();
    // }
    function conectado(){
        $validar = $this->model->usuario_conectado($_SESSION['id']);
        if(empty($validar)){
            $this->model->modificar_conectado($validar);
        }else{
            $this->model->registrar_conectado($validar);
        }
        die();
    }


    public function salir()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
    }

    public function aumentar_session(){
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            // Si ha pasado más de 30 minutos desde la última actividad, renueva la sesión
            session_regenerate_id(true); // Renueva el ID de sesión para evitar ataques de fijación de sesión
            $_SESSION['LAST_ACTIVITY'] = time(); // Actualiza la marca de tiempo de la última actividad
        }
        
        
        // Actualizar la marca de tiempo de la última actividad
        $_SESSION['LAST_ACTIVITY'] = time();
    }
    
    
}