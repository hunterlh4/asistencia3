<?php
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (!empty($_SESSION['usuario_autenticado'])) {
            header('Location: ' . BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Acceso al sistema';
        $this->views->getView('home', "Login", $data);
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
                        

                        $validar = $this->model->usuario_conectado($data['id']);




                        if (empty($validar)) {
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
}