<?php
class AdminModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuario($username)
    {
        $sql = "SELECT * FROM usuarios WHERE username = '$username'";
        return $this->select($sql);
    }
    public function getUsuarioId($id)
    {
        $sql = "SELECT id,nombre,apellido,username,nivel FROM usuarios WHERE id = '$id'";
        return $this->select($sql);
    }
    public function getUsuarioIdclave($id)
    {
        $sql = "SELECT id,'password' FROM usuarios WHERE id = '$id'";
        return $this->select($sql);
    }
    public function usuario_actualizar($id,$pass1)
    {
        $sql = "UPDATE usuarios SET password = ? WHERE id  = ?";
        $array = array(password_hash($pass1, PASSWORD_BCRYPT),$id); 
        return $this->save($sql, $array);
    }

    public function usuario_conectado($id){
        $sql = "SELECT * FROM usuarios_conectados WHERE usuario_id = '$id'";
        return $this->select($sql);
    }
    public function registrar_conectado($id){
        $sql = "INSERT INTO usuarios_conectados (usuario_id) VALUES (?)";
        $array = array($id);
        return $this->insertar($sql, $array);
    }
    public function modificar_conectado($id){
        $sql = "UPDATE usuarios_conectados SET update_at = NOW() WHERE usuario_id  = ?";
        $array = array($id);
        return $this->save($sql, $array);
       
    }

    public function getTotales($estado)
    {
        $sql = "SELECT COUNT(*) AS total FROM pedidos WHERE proceso = $estado";
        return $this->select($sql);
    }
    public function getProductos()
    {
        $sql = "SELECT COUNT(*) AS total FROM productos WHERE estado = 1";
        return $this->select($sql);
    }

    public function productosMinimos()
    {
        $sql = "SELECT * FROM productos WHERE cantidad < 15 AND estado = 1 ORDER BY cantidad DESC LIMIT 3";
        return $this->selectAll($sql);
    }

    public function topProductos()
    {
        $sql = "SELECT producto, SUM(cantidad) AS total FROM detalle_pedidos GROUP BY id_producto ORDER BY total DESC LIMIT 3";
        return $this->selectAll($sql);
    }
}
 
?>