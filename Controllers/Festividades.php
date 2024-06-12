<?php
class Festividades extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['usuario_autenticado']) || ($_SESSION['usuario_autenticado'] != "true")) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
    }
    public function index()
    {
        $data['title'] = 'Festividades';
        $data1 = '';
        $this->views->getView('Administracion', "Festividades", $data, $data1);
    }

    public function ver()
    {
        $data['title'] = 'Festividades';
        $data1 = '';
        $this->views->getView('Administracion', "Festividades_ver", $data, $data1);
    }

    public function listar()
    {
        $meses = [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ];
        $mes_actual = date('n');

        $data = $this->model->findAll($mes_actual);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['posicion'] = $i + 1;

            $datonuevo = $data[$i]['estado'];
            // $data[$i]['fecha'] = date('d-m-Y', strtotime($data[$i]['fecha']));

            // $data[$i]['fecha'] = date('d-m-Y', strtotime($data[$i]['fecha']));
            
           

            // $dia_formateado = sprintf("%02d", $data[$i]['dia']); // sprintf
            // $mes_formateado = sprintf("%02d",  $data[$i]['mes']); // sprintf
            $dia_formateado_inicio = str_pad($data[$i]['dia_inicio'], 2, '0', STR_PAD_LEFT); // str_pad
            $mes_formateado_inicio = str_pad($data[$i]['mes_inicio'], 2, '0', STR_PAD_LEFT); // str_pad


            $dia_formateado_fin = str_pad($data[$i]['dia_fin'], 2, '0', STR_PAD_LEFT); // str_pad
            $mes_formateado_fin = str_pad($data[$i]['mes_fin'], 2, '0', STR_PAD_LEFT); // str_pad
            $data[$i]['fecha'] = $dia_formateado_inicio ." de ".$meses[$mes_formateado_inicio -1] .'<br>'.$dia_formateado_fin ." de ".$meses[$mes_formateado_fin -1];
            if($dia_formateado_inicio == $dia_formateado_fin && $mes_formateado_inicio ==$mes_formateado_fin){
                $data[$i]['fecha'] = $dia_formateado_inicio ." de ".$meses[$mes_formateado_inicio -1] ;
            }   

            

            if ($datonuevo == 'Activo') {
                $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
            } else {
                $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            }

            $data[$i]['accion'] = '
            <button class="btn btn-primary" type="button" onclick="edit(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            ';
            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }



    public function registrar()
    {
        if (isset($_POST['nombre']) && isset($_POST['fecha']) && isset($_POST['tipo'])) {

            $nombre = $_POST['nombre'];
            $fecha = $_POST['fecha'];
            $descripcion = $_POST['descripcion'];
            $tipo = $_POST['tipo'];
            $estado = $_POST['estado'];
            $id = $_POST['id'];
            $fecha_parts = explode('-', $fecha);
            $mes = $fecha_parts[1];
            $dia = $fecha_parts[2];

            $anio_actual = date('Y');

            $datos_log = [
                "nombre" => $nombre,
                "comentario" => $descripcion,
                "estado" => $estado,

            ];
            $datos_log_json = json_encode($datos_log);


            $error_msg = '';
            if (empty($nombre) || empty($fecha) || empty($tipo)) {
                $error_msg .= 'Ingrese los datos Necesarios. <br>';
            }
            if (strlen($nombre) < 5 || strlen($nombre) > 50) {
                $error_msg .= 'El Festividades debe tener entre 3 y 50 caracteres. <br>';
            }
            if (!empty($descripcion)) {
                if (strlen($descripcion) <= 5) {
                    $error_msg .= 'La descripcion debe de ser mas grande.<br>';
                }
            }
            $data = $this->model->findByDate($dia,$mes);
            if (empty($id) && (!empty($data))) {
                $error_msg .= 'Ingrese una fecha diferente.<br> fecha en uso';
            }
            if ($id) {
                if (!empty($data) && $data['id'] != $id) {
                    $error_msg .= 'Ingrese una fecha diferente.<br>  fecha en uso';
                }
            }


            if (!empty($error_msg)) {

                $respuesta = ['msg' => $error_msg, 'icono' => 'warning'];
            } else {
                if (empty($id)) {
                        $data = $this->model->create($dia,$mes,$nombre,$descripcion,$tipo);
                        if ($data > 0) {
                            $respuesta = ['msg' => 'Festividad registrado', 'icono' => 'success'];
                            $this->model->createLog($_SESSION['id'], 'Crear', 'Festividades', $datos_log_json);
                        } else {
                            $respuesta = ['msg' => 'error al registrar', 'icono' => 'error'];
                        }
                    
                } else {
                    $data = $this->model->update($dia,$mes,$nombre,$descripcion,$tipo, $estado, $id);
                    if ($data == 1) {
                        $respuesta = ['msg' => 'Festividad modificada', 'icono' => 'success'];
                        $this->model->createLog($_SESSION['id'], 'Modificar', 'Festividades', $datos_log_json);
                    } else {
                        $respuesta = ['msg' => 'Error al modificar', 'icono' => 'error'];
                    }
                }
                // $respuesta = ['msg' => 'llego', 'icono' => 'warning'];
            }
        } else {
            $respuesta = ['msg' => 'todo los campos son requeridos', 'icono' => 'warning'];
        }

        echo json_encode($respuesta);

        die();
    }
    //eliminar user
    public function delete($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->eliminar($id);
            if ($data == 1) {
                $respuesta = ['msg' => 'se ha dado de baja', 'icono' => 'success'];
            } else {
                $respuesta = ['msg' => 'error al eliminar', 'icono' => 'error'];
            }
        } else {
            $respuesta = ['msg' => 'error desconocido', 'icono' => 'error'];
        }
        echo json_encode($respuesta);
        die();
    }
    //editar user
    public function edit($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->findById($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
