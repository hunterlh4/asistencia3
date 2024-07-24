<?php
class Api extends Controller
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
    public function listartrabajadores()
    {
        $data1 = $this->model->obtenerTrabajadores();

        echo json_encode($data1, JSON_UNESCAPED_UNICODE);
        die();
    }
}


