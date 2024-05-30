<?php
class Soporte extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        if (empty($_SESSION['usuario_autenticado']) || ($_SESSION['usuario_autenticado'] != "true")) {
            header('Location: '. BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Soporte';
        $data1 = '';
        $this->views->getView('Administracion', "Soporte", $data,$data1);
        
    }
    

    
    
}