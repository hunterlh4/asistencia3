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

    public function Porteria()
    {

        $data['title'] = 'Porteria';
        $data1 = '';

        $this->views->getView('Administracion', "Boleta_Porteria", $data, $data1);
    }
    public function listar()
    {
        $data = $this->model->getBoletas();
        for ($i = 0; $i < count($data); $i++) {
            $numero = $data[$i]['numero'];
            if ($data[$i]['numero'] == null) {
                $numero = '0';
            }
            $data[$i]['posicion'] = $i + 1;

            $numero_formateado = str_pad($numero, 9, '0', STR_PAD_LEFT);
            $data[$i]['numero'] = $numero_formateado;


            $fecha_inicio = $data[$i]['fecha_inicio'];
            $fecha_fin = $data[$i]['fecha_fin'];
            $estado_tramite = $data[$i]['estado_tramite'];

            $fecha_inicio = date('d-m-Y', strtotime($fecha_inicio));
            $fecha_fin = date('d-m-Y', strtotime($fecha_fin));

            if ($fecha_inicio == $fecha_fin) {
                $data[$i]['fecha_nueva'] = $fecha_inicio;
            } else {
                $data[$i]['fecha_nueva'] = $fecha_inicio . '<br>' . $fecha_fin;
            }

            $data[$i]['accion'] = '<div class="d-flex">
                <button class="btn btn-info" type="button" onclick="view(' . $data[$i]['bid'] . ')"><i class="fas fa-eye"></i></button>
                </div>';
            if ($estado_tramite == 'Pendiente') {
                $data[$i]['accion'] = '<div class="d-flex">
                <button class="btn btn-primary" type="button" onclick="edit(' . $data[$i]['bid'] . ')"><i class="fas fa-edit"></i></button>
                </div>';
            }
            if ($estado_tramite == 'Aprobado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-success">Aprobado</span>';
            }
            if ($estado_tramite == 'Rechazado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-danger">Rechazado</span>';
            }
            if ($estado_tramite == 'Pendiente') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-warning">Pendiente</span>';
            }



            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        // if (isset($_POST['solicitante']) && isset($_POST['aprobador']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && isset($_POST['hora_salida']) && isset($_POST['hora_entrada']) && isset($_POST['razon'])&& isset($_POST['otra_razon'])) {

        if (isset($_POST['solicitante']) && isset($_POST['aprobador']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])  && isset($_POST['razon']) && isset($_POST['otra_razon'])) {
            $id = $_POST['id'];
            $solicitante = $_POST['solicitante'];
            $aprobador = $_POST['aprobador'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            // $salida = $_POST['hora_salida'];
            // $entrada = $_POST['hora_entrada'];
            $razon = $_POST['razon'];
            $razon_especifica = $_POST['otra_razon'];

            if (empty($solicitante) || empty($aprobador) || empty($fecha_inicio) || empty($razon) || empty($razon_especifica)) {
                // if (empty($solicitante) || empty($aprobador) || empty($fecha_inicio) || empty($salida) || empty($entrada) || empty($razon) || empty($razon_especifica)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {

                $datos_log = array(
                    "id" => $id,
                    "solicitante" => $solicitante,
                    "aprobador" => $aprobador,
                    "fecha_inicio" => $fecha_inicio,
                    "fecha_fin" => $fecha_fin,
                    // "salida" => $salida,
                    // "entrada" => $entrada,
                    "razon" => $razon,
                    "razon_especifica" => $razon_especifica,


                );
                $datos_log_json = json_encode($datos_log);

                if (empty($id)) {
                    $estado_tramite = 'Pendiente';
                    // $data = $this->model->registrar($solicitante, $aprobador, $fecha_inicio, $fecha_fin, $salida, $entrada, $razon,$razon_especifica, $estado_tramite);
                    $data = $this->model->registrar($solicitante, $aprobador, $fecha_inicio, $fecha_fin, $razon, $razon_especifica, $estado_tramite);
                    if ($data > 0) {
                        $respuesta = array('msg' => 'Boleta registrada', 'icono' => 'success');
                        // $this->model->registrarlog($_SESSION['id'],'Crear','Boleta', $datos_log_json);
                    } else {
                        $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                    }
                    // $respuesta = array('msg' => 'modificar', 'icono' => 'success');
                } else {
                    $result = $this->model->verificar($id);
                    if ($result['estado_tramite'] == 'Pendiente') {

                        $data = $this->model->modificar($solicitante, $aprobador, $fecha_inicio, $fecha_fin, $razon, $razon_especifica, $id);
                        if ($data > 0) {
                            $respuesta = array('msg' => 'Boleta Actualizada', 'icono' => 'success');
                            // $this->model->registrarlog($_SESSION['id'],'Actualizar','Boleta', $datos_log_json);
                        } else {
                            $respuesta = array('msg' => 'error al Actualizar', 'icono' => 'error');
                        }
                    } else {
                        $respuesta = array('msg' => 'La Solicitud ya fue enviada, Espere su Respuesta', 'icono' => 'error');
                    }
                    // $data = $this->model->modificar($nombre, $nivel);

                    // $respuesta = array('msg' => 'Actualizar', 'icono' => 'success');
                }
            }
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            $respuesta = array('msg' => 'error', 'icono' => 'error');
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function registrarme()
    {
        if (isset($_POST['aprobador']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])  && isset($_POST['razon']) && isset($_POST['otra_razon'])) {
            $id = $_SESSION['id'];
            $data = $this->model->getusuario($id);
            $solicitante = $data['trabajador_id'];


            $id = $_POST['id'];

            $aprobador = $_POST['aprobador'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            // $salida = $_POST['hora_salida'];
            // $entrada = $_POST['hora_entrada'];
            $razon = $_POST['razon'];
            $razon_especifica = $_POST['otra_razon'];


            if (empty($solicitante) || empty($aprobador) || empty($fecha_inicio) ||  empty($razon) || empty($razon_especifica)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {

                $datos_log = array(
                    "id" => $id,
                    "solicitante" => $solicitante,
                    "aprobador" => $aprobador,
                    "fecha_inicio" => $fecha_inicio,
                    "fecha_fin" => $fecha_fin,
                    // "salida" => $salida,
                    // "entrada" => $entrada,
                    "razon" => $razon,
                    "razon_especifica" => $razon_especifica,

                );
                $datos_log_json = json_encode($datos_log);

                if (empty($id)) {
                    $estado_tramite = 'Pendiente';

                    // $data = $this->model->registrar($solicitante, $aprobador, $fecha_inicio, $fecha_fin, $salida, $entrada, $razon,$razon_especifica, $estado_tramite);
                    $data = $this->model->registrar($solicitante, $aprobador, $fecha_inicio, $fecha_fin, $razon, $razon_especifica, $estado_tramite);
                    if ($data > 0) {
                        $respuesta = array('msg' => 'Boleta registrada', 'icono' => 'success');
                        // $this->model->registrarlog($_SESSION['id'],'Crear','Boleta', $datos_log_json);
                    } else {
                        $respuesta = array('msg' => 'error al registrar', 'icono' => 'error');
                    }
                    // $respuesta = array('msg' => 'modificar', 'icono' => 'success');
                } else {
                    $result = $this->model->verificar($id);
                    if ($result['estado_tramite'] == 'Pendiente') {

                        $data = $this->model->modificar($solicitante, $aprobador, $fecha_inicio, $fecha_fin, $razon, $razon_especifica, $id);
                        if ($data > 0) {
                            $respuesta = array('msg' => 'Boleta Actualizada', 'icono' => 'success');
                            // $this->model->registrarlog($_SESSION['id'],'Actualizar','Boleta', $datos_log_json);
                        } else {
                            $respuesta = array('msg' => 'error al Actualizar', 'icono' => 'error');
                        }
                    } else {
                        $respuesta = array('msg' => 'La Solicitud ya fue enviada, Espere su Respuesta', 'icono' => 'error');
                    }
                    // $data = $this->model->modificar($nombre, $nivel);

                    // $respuesta = array('msg' => 'Actualizar', 'icono' => 'success');
                }
            }
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error

            $respuesta = array('msg' => 'error', 'icono' => 'error');
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function registrarHora()
    {
        // if (isset($_POST['solicitante']) && isset($_POST['aprobador']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && isset($_POST['hora_salida']) && isset($_POST['hora_entrada']) && isset($_POST['razon'])&& isset($_POST['otra_razon'])) {

        if ((isset($_POST['hora_salida']) || isset($_POST['hora_entrada'])) && isset($_POST['id'])) {
            $id = $_POST['id'];

            $salida = $_POST['hora_salida'];
            $entrada = $_POST['hora_entrada'];



            if (empty($salida) && empty($entrada)) {
                $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
            } else {

                $datos_log = array(
                    "id" => $id,
                    "salida" => $salida,
                    "entrada" => $entrada,
                );
                $datos_log_json = json_encode($datos_log);


                $result = $this->model->verificar($id);
                if ($result['estado_tramite'] == 'Aprobado') {
                    if (empty($salida)) {
                        $data = $this->model->modificarEntrada($entrada, $id);
                    }
                    if (empty($entrada)) {
                        $data = $this->model->modificarSalida($salida, $id);
                    }
                    if (!empty($entrada) && !empty($salida)) {
                        $data = $this->model->modificarHora($salida, $entrada, $id);
                    }


                    if ($data > 0) {
                        $respuesta = array('msg' => 'Boleta Actualizada', 'icono' => 'success');
                        // $this->model->registrarlog($_SESSION['id'],'Actualizar','Boleta', $datos_log_json);
                    } else {
                        $respuesta = array('msg' => 'error al Actualizar', 'icono' => 'error');
                    }
                } else {
                    $respuesta = array('msg' => 'La Solicitud Solo es Para Boletas Aprobadas, Espere su Respuesta', 'icono' => 'error');
                }
                // $data = $this->model->modificar($nombre, $nivel);

                // $respuesta = array('msg' => 'Actualizar', 'icono' => 'success');

            }
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            $salida = $_POST['hora_salida'];
            $entrada = $_POST['hora_entrada'];
            $respuesta = array('msg' => 'error datos vacios'.$salida.'-'.$entrada, 'icono' => 'error');
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    //eliminar user
    public function delete($id)
    {
    }
    //editar user
    public function edit($id)
    {
        if (is_numeric($id)) {
            $data = $this->model->getBoleta($id);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    //eliminar user
    public function buscarPorFecha()
    {
        if (isset($_POST['fecha']) && isset($_POST['trabajador_id'])) {
            $fecha = $_POST['fecha'];
            $trabajador_id = $_POST['trabajador_id'];

            // Llama al modelo para obtener los datos de asistencia
            $data = $this->model->getBoletaPorFecha($fecha, $trabajador_id);

            // Devuelve los datos como JSON
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            $respuesta = array('msg' => 'error', 'icono' => 'error');
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }

        // Detiene la ejecución del script
        die();
    }

    public function buscarPorFechaSola()
    {
        if (isset($_POST['trabajador_id'])) {

            $trabajador_id = $_POST['trabajador_id'];

            // Llama al modelo para obtener los datos de asistencia
            $data = $this->model->getBoletaPorFechaSola($trabajador_id);

            // Devuelve los datos como JSON
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            // Si no se recibieron todos los datos esperados, devuelve un mensaje de error
            $respuesta = array('msg' => 'error', 'icono' => 'error');
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }

        // Detiene la ejecución del script
        die();
    }

    public function MisBoletas()
    {
        $data['title'] = 'Mis Boletas';
        $data1 = '';

        $this->views->getView('Administracion', "Boleta_Trabajador", $data, $data1);
    }


    public function listarMisBoletas()
    {
        $id = $_SESSION['id'];
        $data = $this->model->getusuario($id);
        $id = $data['trabajador_id'];
        $data = $this->model->getMisBoletas($id);
        for ($i = 0; $i < count($data); $i++) {
            $numero = $data[$i]['numero'];
            if ($data[$i]['numero'] == null) {
                $numero = '0';
            }
            $numero_formateado = str_pad($numero, 9, '0', STR_PAD_LEFT);
            $data[$i]['numero'] = $numero_formateado;

            $data[$i]['posicion'] = $i + 1;
            $fecha_inicio = $data[$i]['fecha_inicio'];
            $fecha_fin = $data[$i]['fecha_fin'];
            $estado_tramite = $data[$i]['estado_tramite'];

            $fecha_inicio = date('d-m-Y', strtotime($fecha_inicio));
            $fecha_fin = date('d-m-Y', strtotime($fecha_fin));

            if ($fecha_inicio == $fecha_fin) {
                $data[$i]['fecha_nueva'] = $fecha_inicio;
            } else {
                $data[$i]['fecha_nueva'] = $fecha_inicio . '<br>' . $fecha_fin;
            }


            // $datonuevo = $data[$i]['bestado'];
            // if ($datonuevo == 'Activo') {
            //     $data[$i]['estado'] = "<div class='badge badge-info'>Activo</div>";
            // } else {
            //     $data[$i]['estado'] = "<div class='badge badge-danger'>Inactivo</div>";
            // }
            $data[$i]['accion'] = '<div class="d-flex">
                <button class="btn btn-info" type="button" onclick="view(' . $data[$i]['boleta_id'] . ')"><i class="fas fa-eye"></i></button>
                </div>';
            if ($estado_tramite == 'Pendiente') {
                $data[$i]['accion'] = '<div class="d-flex">
                <button class="btn btn-primary" type="button" onclick="edit(' . $data[$i]['boleta_id'] . ')"><i class="fas fa-edit"></i></button>
                </div>';
            }

            if ($estado_tramite == 'Aprobado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-success">Aprobado</span>';
            }
            if ($estado_tramite == 'Rechazado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-danger">Rechazado</span>';
            }
            if ($estado_tramite == 'Pendiente') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-warning">Pendiente</span>';
            }


            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }

    public function RevisarBoletas()
    {
        $data['title'] = 'Revisar Boletas';
        $data1 = '';

        $this->views->getView('Administracion', "boleta_revision", $data, $data1);
    }

    public function listarRevisionBoletas()
    {
        $id = $_SESSION['id'];
        $data = $this->model->getusuario($id);
        $id = $data['trabajador_id'];
        $data = $this->model->getMisRevisiones($id);

        for ($i = 0; $i < count($data); $i++) {
            $numero = $data[$i]['numero'];
            if ($data[$i]['numero'] == null) {
                $numero = '0';
            }
            $data[$i]['posicion'] = $i + 1;
            $numero_formateado = str_pad($numero, 9, '0', STR_PAD_LEFT);
            $data[$i]['numero'] = $numero_formateado;


            $fecha_inicio = $data[$i]['fecha_inicio'];
            $fecha_fin = $data[$i]['fecha_fin'];
            $estado_tramite = $data[$i]['estado_tramite'];

            $fecha_inicio = date('d-m-Y', strtotime($fecha_inicio));
            $fecha_fin = date('d-m-Y', strtotime($fecha_fin));

            if ($fecha_inicio == $fecha_fin) {
                $data[$i]['fecha_nueva'] = $fecha_inicio;
            } else {
                $data[$i]['fecha_nueva'] = $fecha_inicio . '<br>' . $fecha_fin;
            }


            $data[$i]['accion'] = '<div class="d-flex">
                <button class="btn btn-info" type="button" onclick="view(' . $data[$i]['boleta_id'] . ')"><i class="fas fa-eye"></i></button>
                </div>';

            if ($estado_tramite == 'Aprobado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-success">Aprobado</span>';
            }
            if ($estado_tramite == 'Rechazado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-danger">Rechazado</span>';
            }
            if ($estado_tramite == 'Pendiente') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-warning">Pendiente</span>';
            }
        }

        echo json_encode($data);
        die();
    }

    public function listarPorteria()
    {
        $data = $this->model->getBoletasPorteria();
        for ($i = 0; $i < count($data); $i++) {
            $numero = $data[$i]['numero'];
            if ($data[$i]['numero'] == null) {
                $numero = '0';
            }
            $data[$i]['posicion'] = $i + 1;

            $numero_formateado = str_pad($numero, 9, '0', STR_PAD_LEFT);
            $data[$i]['numero'] = $numero_formateado;


            $fecha_inicio = $data[$i]['fecha_inicio'];
            $fecha_fin = $data[$i]['fecha_fin'];
            $estado_tramite = $data[$i]['estado_tramite'];

            $fecha_inicio = date('d-m-Y', strtotime($fecha_inicio));
            $fecha_fin = date('d-m-Y', strtotime($fecha_fin));

            if ($fecha_inicio == $fecha_fin) {
                $data[$i]['fecha_nueva'] = $fecha_inicio;
            } else {
                $data[$i]['fecha_nueva'] = $fecha_inicio . '<br>' . $fecha_fin;
            }


            $data[$i]['accion'] = '<div class="d-flex">
                <button class="btn btn-primary" type="button" onclick="edit(' . $data[$i]['bid'] . ')"><i class="fas fa-edit"></i></button>
                </div>';

            if ($estado_tramite == 'Aprobado') {
                $data[$i]['estado_tramite'] = '<span class="badge badge-success">Aprobado</span>';
            }




            // <button class="btn btn-danger" type="button" onclick="ViewUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // <button class="btn btn-danger" type="button" onclick="DeleteUser(' . $data[$i]['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
            // colocar eliminar si es necesario
        }
        echo json_encode($data);
        die();
    }





    public function revisar()
    {

        if (isset($_POST['id']) || isset($_POST['accion']) || isset($_POST['observacion'])) {
            $id = $_POST['id'];
            $accion = $_POST['accion'];
            $observacion = $_POST['observacion'];

            if ($accion == 'aprobar') {
                $accion = 'Aprobado';
            } else {
                $accion = 'Rechazado';
            }

            $data = $this->model->Revisar($id, $accion, $observacion);
            if ($data > 0) {
                if ($accion == 'Aprobado') {
                    $respuesta = array('msg' => 'Se ha Aprobado con exito', 'icono' => 'success');
                } else {
                    $respuesta = array('msg' => 'Se ha Rechazado con exito', 'icono' => 'success');
                }
            } else {
                $respuesta = array('msg' => 'Se ha Producido un error', 'icono' => 'success');
            }
        } else {
            $respuesta = array('msg' => 'todo los campos son requeridos', 'icono' => 'warning');
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);

        die();
    }


    public function listarTrabajadoresPorCargoNivel()
    {


        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            if ($id == '0') {
                $nivel = '1';
                $data = $this->model->getTrabajadorCargo2($nivel);
            } else {
                $data = $this->model->getTrabajador($id);
                $cargo = $data['cargo_nombre'];
                $nivel = $data['cargo_nivel'];
                $data = $this->model->getTrabajadorCargo($cargo, $nivel);
                if (empty($data)) {
                    $nivel = intval($nivel) - 1;

                    $data = $this->model->getTrabajadorCargo2($nivel);
                }
            }
        } else {
            $nivel = '1';
            $data = $this->model->getTrabajadorCargo2($nivel);
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function MilistarTrabajadoresPorCargoNivel()
    {

        $id = $_SESSION['id'];
        $data = $this->model->getusuario($id);
        $id = $data['trabajador_id'];

        if ($id == '0') {
            $nivel = '1';
            $data = $this->model->getTrabajadorCargo2($nivel);
        } else {
            $data = $this->model->getTrabajador($id);
            $cargo = $data['cargo_nombre'];
            $nivel = $data['cargo_nivel'];
            $data = $this->model->getTrabajadorCargo($cargo, $nivel);
            if (empty($data)) {
                $nivel = intval($nivel) - 1;
                $data = $this->model->getTrabajadorCargo2($nivel);
            }
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
