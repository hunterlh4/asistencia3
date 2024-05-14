<?php
class Boleta extends Controller
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

        $data['title'] = 'Boleta';
        $data1 = '';
        
        $this->views->getView('Administracion', "Boleta", $data, $data1);
    }
    public function listar()
    {
       
    }

    public function registrar()
    {
       
    }
    //eliminar user
    public function delete($id)
    {
      
    }
    //editar user
    public function edit($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->getCargo($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    //eliminar user
    public function buscarPorFecha()
    {
        if (isset($_POST['fecha']) && isset($_POST['trabajador_id'])) {
            $fecha = $_POST['fecha'] ; 
            $trabajador_id = $_POST['trabajador_id'] ; 
           
                // Llama al modelo para obtener los datos de asistencia
            $data = $this->model->getBoletaPorFecha($fecha, $trabajador_id);
    
                // Devuelve los datos como JSON
            echo json_encode($data, JSON_UNESCAPED_UNICODE);

            
                
            
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            echo json_encode(array('error' => 'Se requieren los parámetros "id", "anio" y "mes"'));
        }
    
        // Detiene la ejecución del script
        die();
    }

    public function buscarPorFechaSola()
    {
        if (isset($_POST['trabajador_id'])) {
            
            $trabajador_id = $_POST['trabajador_id'] ; 
           
                // Llama al modelo para obtener los datos de asistencia
            $data = $this->model->getBoletaPorFechaSola($trabajador_id);
    
                // Devuelve los datos como JSON
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            echo json_encode(array('error' => 'Se requieren los parámetros "trabajador_id"'));
        }
    
        // Detiene la ejecución del script
        die();
    }
}
