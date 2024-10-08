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
                usuario.id AS usuario_id, 
                usuario.username AS usuario_username, 
                usuario.nombre AS usuario_nombre, 
                usuario.apellido AS usuario_apellido,
                trabajador.dni AS DNI,
               
                usuario.estado AS usuario_estado,
                CASE
                WHEN usuario.nivel = 1 THEN 'Administrador'
                WHEN usuario.nivel = 2 THEN 'Jefe de Oficina'
                WHEN usuario.nivel = 3 THEN 'Vizualizador'
                WHEN usuario.nivel = 4 THEN 'Portero'
                WHEN usuario.nivel = 5 THEN 'Sin permisos'
                WHEN usuario.nivel = 100 THEN 'Desarrollador'
                ELSE 'Nivel no definido'
                END AS usuario_nivel
                FROM usuario 
                LEFT JOIN trabajador ON trabajador.id = usuario.trabajador_id
                WHERE usuario.nivel != 100
                ORDER BY 
                CASE
                    WHEN trabajador.dni IS NULL OR trabajador.dni = '' THEN 0
                    ELSE 1
                END ASC,
                trabajador.dni  ASC";

        return $this->selectAll($sql);
    }
    public function getTrabajadores()
    {
        $sql = "SELECT  id,apellido_nombre,dni,estado from trabajador 
        where estado = 'Activo' 
        order by apellido_nombre asc ";
        return $this->selectAll($sql);
    }

    public function getTrabajadoresconBuscar($id)
    {
        $sql = "SELECT t.id AS id, t.apellido_nombre AS apellido_nombre, t.dni AS dni, t.estado AS estado
                FROM trabajador AS t
                WHERE t.estado = 'Activo'
                UNION
                SELECT t.id AS id, t.apellido_nombre AS apellido_nombre, t.dni AS dni, t.estado AS estado
                FROM trabajador AS t
                INNER JOIN usuario AS u ON t.id = u.trabajador_id
                WHERE u.id = $id
                ORDER BY apellido_nombre;";
        return $this->selectAll($sql);
    }
    public function getUsuarios2()
    {
        $sql = "SELECT id, nombre, apellido, estado FROM usuario ";
        return $this->selectAll($sql);
    }
    public function registrar($usuario, $password, $nombre, $apellido, $nivel, $trabajador_id, $direccion_id, $fecha_nacimiento, $dni)
    {
        $sql = "INSERT INTO usuario (username,password,nombre, apellido, nivel, trabajador_id,direccion,nacimiento,dni) VALUES (?,?,?,?,?,?,?,?,?)";
        $array = array($usuario, $password, $nombre, $apellido, $nivel, $trabajador_id, $direccion_id, $fecha_nacimiento, $dni);

        return $this->insertar($sql, $array);
    }
    public function verificarCorreo($correo)
    {
        $sql = "SELECT correo FROM usuario WHERE correo = '$correo' AND estado = 'Activo";
        return $this->select($sql);
    }
    public function verificarUsuario($usuario)
    {
        $sql = "SELECT id,username FROM usuario WHERE username = '$usuario' ";
        return $this->select($sql);
    }
    public function eliminar($idUser)
    {
        $sql = "UPDATE usuario SET estado = ? WHERE id = ?";
        $array = array(0, $idUser);
        return $this->save($sql, $array);
    }
    public function getUsuario($idUser)
    {

        $sql = "SELECT * FROM usuario WHERE id = $idUser";
        return $this->select($sql);
    }

    public function getlogin($username)
    {
        $sql = "SELECT * FROM usuario WHERE username = $username";
        return $this->select($sql);
    }

    public function modificar($usuario, $password, $nombre, $apellido, $nivel, $trabajador_id, $estado, $direccion_id, $fecha_nacimiento, $dni, $id)
    {
        $sql = "UPDATE usuario SET username=?,password=?,nombre=?, apellido=?,nivel=?,trabajador_id=?,estado=? ,direccion=?,nacimiento=?,dni=?,update_at = NOW()  WHERE id = ?";
        $array = array($usuario, $password, $nombre, $apellido, $nivel, $trabajador_id, $estado, $direccion_id, $fecha_nacimiento, $dni, $id);
        return $this->save($sql, $array);
    }

    public function registrarlog($usuario, $accion, $tabla, $detalles)
    {
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario, $accion, $tabla, $detalles);
        return $this->save($sql, $array);
    }

    public function modificarTrabajador($nombre,$apellido,$dni,$nacimiento,$direccion,$trabajador_id){
        $sql = "UPDATE trabajador SET nombre=?,apellido=?,dni=?,fecha_nacimiento=?,direccion_id=?,update_at = NOW()  WHERE id = ?";
        $array = array($nombre,$apellido,$dni,$nacimiento,$direccion,$trabajador_id);
        return $this->save($sql, $array);
    }
}
