<?php
class DireccionesModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getDirecciones()
    {
        $sql = "SELECT direccion.id as direccion_id,
        direccion.nombre as direccion_nombre,
        -- direccion.equipo_id as direccion_equipo_id,
      
        equipo.nombre as equipo_nombre ,
        direccion.estado as direccion_estado
        FROM direccion left JOIN equipo ON equipo.id = direccion.equipo_id ORDER BY  direccion.id asc;";

        return $this->selectAll($sql);
    }
    public function getEquipos()
    {
        $sql = "SELECT  id,nombre from equipo where estado = 'Activo' order by nombre asc ";
        return $this->selectAll($sql);
    }
    public function getDireccion($id)
    {
        $sql = "SELECT * FROM Direccion WHERE id = $id";
        return $this->select($sql);
    }
    public function verificar($nombre)
    {
        $sql = "SELECT id,nombre FROM Direccion WHERE nombre = '$nombre' ";
        return $this->select($sql);
    }
    public function registrar($nombre, $equipo_id)
    {
        
        $sql = "INSERT INTO direccion (nombre,equipo_id) VALUES (?,?)";
        $array = array($nombre,$equipo_id);
        return $this->insertar($sql, $array);
    }
    public function modificar($nombre,$equipo_id,$estado,$id)
    {
        $sql = "UPDATE Direccion SET nombre=?,equipo_id=?,estado=? WHERE id = ?";
        $array = array($nombre,$equipo_id,$estado, $id);
        return $this->save($sql, $array);
    }
    public function eliminar($id)
    {
        $sql = "UPDATE Direccion SET estado = ? WHERE id = ?";
        $array = array(0, $id);
        return $this->save($sql, $array);
    }

    
    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }
}
