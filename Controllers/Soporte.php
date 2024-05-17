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
        if (empty($_SESSION['nombre'])) {
            header('Location: '. BASE_URL . 'admin/home');
            exit;
        }
        $data['title'] = 'Soporte';
        $data1 = '';
        $this->views->getView('Administracion', "Soporte", $data,$data1);
        
    }
    

    
    
}