<?php
class HorarioDetalleModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getHorarioDetalles()
    {
        $sql = "SELECT * from horario_detalle ORDER BY id ASC";
        // $sql = "SELECT * FROM horario_detalle WHERE horario_id = $id";

        return $this->selectAll($sql);
    }
    public function getHorarioDetallesPorHorario($id)
    {
        $sql = "SELECT * FROM horario_detalle WHERE horario_id = $id";

        return $this->selectAll($sql);
    }
    public function getHorarioDetalle($id)
    {
        $sql = "SELECT * FROM horario_detalle WHERE id = $id";
        return $this->select($sql);
    }
    // public function verificar($nombre)
    // {
    //     $sql = "SELECT id,nombre FROM horario_detalle WHERE nombre = '$nombre' ";
    //     return $this->select($sql);
    // }
    public function registrar($nombre, $horario_id,$hora_entrada,$hora_salida)
    {
        $sql = "INSERT INTO horario_detalle (nombre,horario_id,hora_entrada,hora_salida) VALUES (?,?,?,?)";
        $array = array($nombre,$horario_id,$hora_entrada,$hora_salida);
        return $this->insertar($sql, $array);
    }
    public function modificar($nombre, $horario_id,$hora_entrada,$hora_salida,$estado,$id)
    {
        $sql = "UPDATE horario_detalle SET nombre=?,horario_id=?,hora_entrada=?,hora_salida=?,estado=? WHERE id = ?";
        $array = array($nombre,$horario_id,$hora_entrada,$hora_salida,$estado, $id);
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
