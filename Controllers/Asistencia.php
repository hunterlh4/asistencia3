<?php
class Asistencia extends Controller
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

        $data['title'] = 'Hoja de Asistencia';
        $data1 = '';
        
        $this->views->getView('Administracion', "Asistencia", $data, $data1);
    }
    public function listar()
    {
        $data = $this->model->getCargos();
        for ($i = 0; $i < count($data); $i++) {

            $datonuevo = $data[$i]['estado'];
            if ($datonuevo == 'Activo') {
                $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
            } else {
                $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            }

            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="editUser(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
           
            </div>';
            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        
     
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
    // public function listaCalendarioAsistenciaTrabajador($id,$anio,$mes)
    // {
    //     if (is_numeric($id)) {
    //         $data = $this->model->getAsistenciaPorFecha($id,$anio,$mes);
    //         echo json_encode($data, JSON_UNESCAPED_UNICODE);
    //     }
    //     die();
    // }

    public function listaCalendarioAsistenciaTrabajador()
    {
        // $id = 812;
        // $anio = '2024';
        // $mes = '5';
        if (isset($_POST['id']) && isset($_POST['anio']) && isset($_POST['mes'])) {
            $mes = $_POST['mes'] ; 
            $anio = $_POST['anio'] ; 
            $id = $_POST['id'] ;
            if (is_numeric($id)) {
                // Llama al modelo para obtener los datos de asistencia
                $data = $this->model->getAsistenciaPorFecha($id, $anio, $mes);
    
                // Devuelve los datos como JSON
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                
            } else {
                // Si el ID no es numérico, devuelve un mensaje de error
                echo json_encode(array('error' => 'El ID no es válido'));
            }
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            echo json_encode(array('error' => 'Se requieren los parámetros "id", "anio" y "mes"'));
        }
    
        // Detiene la ejecución del script
        die();
    }
}
