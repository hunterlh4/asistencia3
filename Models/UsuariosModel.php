<?php
class UsuariosModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuarios()
    {
        // $sql = "SELECT id, nombres, apellidos, correo, perfil FROM usuarios WHERE estado = $estado";

        // $sql = "SELECT 
        //         usuarios.id AS usuario_id, 
        //         usuarios.username AS usuario_username, 
        //         usuarios.nombre AS usuario_nombre, 
        //         usuarios.apellido AS usuario_apellido, 
        //         usuarios.nivel AS usuario_nivel, 
        //         usuarios.direccion_id AS direccion_id,
        //         CASE 
        //             WHEN direccion.nombre = 'SIN ASIGNAR' THEN ''
        //             ELSE direccion.nombre 
        //         END AS direccion_nombre,
        //         equipo.id AS equipo_id,
        //         CASE 
        //             WHEN equipo.nombre = 'SIN ASIGNAR' THEN ''
        //             ELSE equipo.nombre 
        //         END AS equipo_nombre,
        //         usuarios.estado AS usuario_estado,
        //         usuarios.create_at AS creacion,
        //         usuarios.update_at AS edicion
        //         FROM usuarios
        //         INNER JOIN direccion ON usuarios.direccion_id = direccion.id
        //         INNER JOIN equipo ON direccion.equipo_id = equipo.id 
        //         ORDER BY usuarios.id ASC";

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
    public function getTrabajadores(){
        $sql ="select * from trabajadores";
        return $this->selectAll($sql);
    }

    public function getUsuarios2()
    {
         $sql = "SELECT id, nombre, apellido, estado FROM usuarios ";

       

        return $this->selectAll($sql);
    }
    public function registrar($nombre, $apellido, $correo, $clave)
    {
        $sql = "INSERT INTO usuarios (nombres, apellidos, correo, clave) VALUES (?,?,?,?)";
        $array = array($nombre, $apellido, $correo, $clave);
        return $this->insertar($sql, $array);
    }
    public function verificarCorreo($correo)
    {
        $sql = "SELECT correo FROM usuarios WHERE correo = '$correo' AND estado = 1";
        return $this->select($sql);
    }

    public function eliminar($idUser)
    {
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $array = array(0, $idUser);
        return $this->save($sql, $array);
    }

    public function eliminar2($idUser)
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

    public function modificar($nombre, $apellido, $correo, $id)
    {
        $sql = "UPDATE usuarios SET nombres=?, apellidos=?, correo=? WHERE id = ?";
        $array = array($nombre, $apellido, $correo, $id);
        return $this->save($sql, $array);
    }
}
 
?>