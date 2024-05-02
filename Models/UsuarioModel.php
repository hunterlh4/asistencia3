<?php
class UsuarioModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuarios()
    {
        $sql = "SELECT
                usuarios.id AS usuario_id, 
                usuarios.username AS usuario_username, 
                usuarios.nombre AS usuario_nombre, 
                usuarios.apellido AS usuario_apellido,
                trabajadores.dni AS DNI,
                usuarios.estado AS usuario_estado,
                CASE
                WHEN usuarios.nivel = 1 THEN 'Administrador'
                WHEN usuarios.nivel = 2 THEN 'Jefe de Oficina'
                WHEN usuarios.nivel = 3 THEN 'Vizualizador'
                WHEN usuarios.nivel = 4 THEN 'Portero'
                WHEN usuarios.nivel = 5 THEN 'Sin permisos'
                ELSE 'Nivel no definido'
                END AS usuario_nivel
                FROM usuarios 
                LEFT JOIN trabajadores ON trabajadores.id = usuarios.trabajador_id
                ORDER BY usuarios.id ASC";

        return $this->selectAll($sql);
    }
    public function getTrabajadores()
    {
        $sql = "SELECT  id,apellido_nombre,dni from trabajadores where estado = 'Activo' order by apellido_nombre asc ";
        return $this->selectAll($sql);
    }
    public function getUsuarios2()
    {
        $sql = "SELECT id, nombre, apellido, estado FROM usuarios ";
        return $this->selectAll($sql);
    }
    public function registrar($usuario,$password,$nombre, $apellido, $nivel, $trabajador_id)
    {
        if($trabajador_id=='0'){
            $sql = "INSERT INTO usuarios (username,password,nombre, apellido, nivel) VALUES (?,?,?,?,?)";
            $array = array($usuario,$password,$nombre, $apellido, $nivel);
        }else{
            $sql = "INSERT INTO usuarios (username,password,nombre, apellido, nivel, trabajador_id) VALUES (?,?,?,?,?,?)";
            $array = array($usuario,$password,$nombre, $apellido, $nivel, $trabajador_id);
        }
        return $this->insertar($sql, $array);
    }
    public function verificarCorreo($correo)
    {
        $sql = "SELECT correo FROM usuarios WHERE correo = '$correo' AND estado = 'Activo";
        return $this->select($sql);
    }
    public function verificarUsuario($usuario)
    {
        $sql = "SELECT id,username FROM usuarios WHERE username = '$usuario' ";
        return $this->select($sql);
    }
    public function eliminar($idUser)
    {
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $array = array(0, $idUser);
        return $this->save($sql, $array);
    }
    public function getUsuario($idUser)
    {
        // $sql = "SELECT id, nombres, apellidos, correo FROM usuarios WHERE id = $idUser";
        $sql = "SELECT * FROM usuarios WHERE id = $idUser";
        return $this->select($sql);
    }

    public function getlogin($username)
    {
        $sql = "SELECT * FROM usuarios WHERE username = $username";
        return $this->select($sql);
    }

    public function modificar($usuario,$password,$nombre, $apellido, $nivel, $trabajador_id,$estado,$id)
    {
        $sql = "UPDATE usuarios SET username=?,password=?,nombre=?, apellido=?,nivel=?,trabajador_id=?,estado=? WHERE id = ?";
        $array = array($usuario,$password,$nombre, $apellido, $nivel, $trabajador_id,$estado, $id);
        return $this->save($sql, $array);
    }

    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }
}
