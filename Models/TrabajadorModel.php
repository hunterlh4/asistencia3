<?php
class TrabajadorModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getTrabajadores()
    {
        // $sql = "SELECT * from horario_detalle ORDER BY id ASC";
        // $sql = "SELECT * FROM horario_detalle WHERE horario_id = $id";

        $sql = "SELECT 
                T.id as tid,
                T.dni as tdni,
                T.apellido_nombre AS tnombre, 
                D.nombre AS dnombre, 
                C.nombre AS cnombre, 
                R.nombre AS rnombre, 
                T.estado as testado
              
                FROM trabajadores AS T 
                INNER JOIN direccion AS D ON T.direccion_id = D.id 
                INNER JOIN cargo AS C ON T.cargo_id = C.id
                INNER JOIN regimen AS R ON T.regimen_id = R.id
                ORDER BY T.id asc ";
        // $sql = "SELECT T.id as tid,T.estado as testado from trabajadores as T ORDER BY id ASC";
        return $this->selectAll($sql);
    }
    public function getTrabajador($id)
    {
        $sql = "SELECT 
        T.id AS tid,
        T.dni tdni,
        T.apellido_nombre AS tnombre,
        T.email AS temail,
        T.telefono AS ttelefono,
        T.nro_tarjeta AS ttarjeta,
        T.sexo AS tsexo,
        T.fecha_inicio AS tnacimiento,
        T.modalidad_trabajo AS tmodalidad,
        T.estado AS testado,
        D.id AS did,
        D.nombre AS dnombre,
        E.id AS eid,
        E.nombre AS enombre,
        C.id AS cid,
        C.nombre AS cnombre,
        R.id AS rid ,
        R.nombre AS rnombre,
        R.sueldo AS rsueldo,
        H.id AS hid, 
        H.nombre AS hnombre
        
        FROM trabajadores AS T 
        INNER JOIN direccion AS D ON T.direccion_id = D.id 
        LEFT JOIN  equipo AS E ON D.equipo_id = E.id
        INNER JOIN cargo AS C ON T.cargo_id = C.id
        INNER JOIN regimen AS R ON T.regimen_id = R.id
        INNER JOIN horario AS H ON T.horario_id = H.id 
        WHERE T.id = $id
        ORDER BY T.id ASC;";

        return $this->select($sql);
    }
    public function getDireccion()
    {
        $sql = "SELECT 
        d.id as did,
        d.nombre as dnombre,
        e.nombre as enombre ,
        d.estado as destado,
        e.estado as eestado 
        FROM direccion as d LEFT JOIN equipo as e on d.equipo_id = e.id order by d.nombre";
        return $this->selectAll($sql);
    }

    public function getHorario()
    {
        $sql = "SELECT * FROM horario";
        return $this->selectAll($sql);
    }

    public function getRegimen()
    {
        $sql = "SELECT * FROM regimen";
        return $this->selectAll($sql);
    }

    public function getCargo()
    {
        $sql = "SELECT * FROM cargo";
        return $this->selectAll($sql);
    }
    // public function verificar($nombre)
    // {
    //     $sql = "SELECT id,nombre FROM horario_detalle WHERE nombre = '$nombre' ";
    //     return $this->select($sql);
    // }
    public function registrar($dni,$apellido_nombre,$direccion_id,$regimen_id,$horario_id,$cargo_id,$email,$telefono,$numero_tarjeta,$sexo,$fecha_nacimiento,$modalidad_trabajo)
    {
        $sql = "INSERT INTO trabajadores (dni,apellido_nombre,direccion_id,regimen_id,horario_id,cargo_id,email,telefono,nro_tarjeta,sexo,fecha_nacimiento,modalidad_trabajo) VALUES (?,?,?,?,?)";
        $array = array($dni,$apellido_nombre,$direccion_id,$regimen_id,$horario_id,$cargo_id,$email,$telefono,$numero_tarjeta,$sexo,$fecha_nacimiento,$modalidad_trabajo);
        return $this->insertar($sql, $array);
    }
    public function modificar($dni,$apellido_nombre,$direccion_id,$regimen_id,$horario_id,$cargo_id,$email,$telefono,$numero_tarjeta,$sexo,$fecha_nacimiento,$modalidad_trabajo,$estado,$id)
    {
        $sql = "UPDATE trabajadores SET dni=?,apellido_nombre=?,direccion_id=?,regimen_id=?,horario_id=?,cargo_id=?,email=?,telefono=?,nro_tarjeta=?,sexo=?,fecha_nacimiento=?,modalidad=?,estado=? WHERE id = ?";
        $array = array($dni,$apellido_nombre,$direccion_id,$regimen_id,$horario_id,$cargo_id,$email,$telefono,$numero_tarjeta,$sexo,$fecha_nacimiento,$modalidad_trabajo,$estado, $id);
        return $this->save($sql, $array);
    }
    // public function eliminar($id)
    // {
    //     $sql = "UPDATE HorarioDetalle SET estado = ? WHERE id = ?";
    //     $array = array(0, $id);
    //     return $this->save($sql, $array);
    // }
    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }
}
