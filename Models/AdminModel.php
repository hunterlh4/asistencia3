<?php
class AdminModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getLogin($username)
    {
        $sql = "SELECT * FROM usuario WHERE username = '$username'";
        return $this->select($sql);
    }
    public function getUsuarioId($id)
    {
        $sql = "SELECT id,nombre,apellido,username,nivel FROM usuario WHERE id = '$id'";
        return $this->select($sql);
    }
    public function getUsuarioIdclave($id)
    {
        $sql = "SELECT id,'password' FROM usuario WHERE id = '$id'";
        return $this->select($sql);
    }
    public function usuario_actualizar($id,$pass1)
    {
        $sql = "UPDATE usuario SET password = ? ,update_at = NOW()  WHERE id  = ?";
        $array = array(password_hash($pass1, PASSWORD_BCRYPT),$id); 
        return $this->save($sql, $array);
    }

    public function usuario_conectado($id){
        $sql = "SELECT * FROM usuario_conectado WHERE usuario_id = '$id'";
        return $this->select($sql);
    }
    public function registrar_conectado($id){
        $sql = "INSERT INTO usuario_conectado (usuario_id) VALUES (?)";
        $array = array($id);
        return $this->insertar($sql, $array);
    }
    public function modificar_conectado($id){
        $sql = "UPDATE usuario_conectado SET update_at = NOW() WHERE usuario_id  = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }

}

?>