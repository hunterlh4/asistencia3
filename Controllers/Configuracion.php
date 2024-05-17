<?php
class Configuracion extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Configuracion';
        $data1 = '';
        $this->views->getView('Administracion', "Configuracion", $data,$data1);
        
    }
    public function getConfiguracion()
    {
        $data = $this->model->getConfiguracion();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
     
        die();
    }

    public function actualizar(){
        if (isset($_POST['api'])) {
            $this->model->modificar($_POST['api']);
            $respuesta = array('msg' => 'Datos Actualizados', 'icono' => 'success');
        }else{
            $respuesta = array('msg' => 'Ingrese la API', 'icono' => 'error');
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        die();
    }

    
    
}