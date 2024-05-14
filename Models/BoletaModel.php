<?php
class BoletaModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getBoletas()
    {
        $sql = "SELECT id, numero,trabajador_id,aprobado_por,fecha_inicio,fecha_fin,hora_salida,hora_entrada,duracion,razon,observaciones,estado_tramite,estado from Boleta ORDER BY id ASC";

        return $this->selectAll($sql);
    }

    public function getBoletaPorFecha($fecha,$trabajador_id){
        $sql = "SELECT 
        id, 
        numero, 
        trabajador_id, 
        aprobado_por, 
        fecha_inicio, 
        fecha_fin, 
        hora_salida, 
        hora_entrada, 
        duracion, 
        razon, 
        observaciones, 
        estado_tramite, 
        estado 
    FROM 
        Boleta 
    WHERE 
        estado = 'Activo' 
        AND estado_tramite = 'Aprobado' 
        AND trabajador_id = '$trabajador_id'
        AND '$fecha' BETWEEN fecha_inicio AND fecha_fin 
    ORDER BY 
        id ASC;";
        return $this->selectAll($sql);
    }


    public function getBoletaPorFechaSola($trabajador_id){
        $sql = "SELECT 
        id, 
        numero, 
        trabajador_id, 
        aprobado_por, 
        fecha_inicio, 
        fecha_fin, 
        hora_salida, 
        hora_entrada, 
        duracion, 
        razon, 
        observaciones, 
        estado_tramite, 
        estado 
    FROM 
        Boleta 
    WHERE 
        estado = 'Activo' 
        AND estado_tramite = 'Aprobado' 
        AND trabajador_id = '$trabajador_id' 
    ORDER BY 
        id ASC;";
        return $this->selectAll($sql);
    }


    
    public function getBoleta($id)
    {
        $sql = "SELECT * FROM Boleta WHERE id = $id";
        return $this->select($sql);
    }
    // public function verificar($nombre)
    // {
    //     $sql = "SELECT id,nombre FROM Boleta WHERE nombre = '$nombre' ";
    //     return $this->select($sql);
    // }
    public function registrar($nombre, $nivel)
    {
        $sql = "INSERT INTO Boleta (nombre,nivel) VALUES (?,?)";
        $array = array($nombre,$nivel);
        return $this->insertar($sql, $array);
    }
    public function modificar($nombre,$nivel,$estado,$id)
    {
        $sql = "UPDATE Boleta SET nombre=?,nivel=?,estado=? ,update_at = NOW()  WHERE id = ?";
        $array = array($nombre,$nivel,$estado, $id);
        return $this->save($sql, $array);
    }
    public function eliminar($id)
    {
        $sql = "UPDATE Boleta SET estado = ? WHERE id = ?";
        $array = array(0, $id);
        return $this->save($sql, $array);
    }

    
    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }
}
