<?php
class BoletaModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getBoletas()
    {
        $sql = "SELECT b.id AS bid, numero,
                trabajador_id AS solicitanteid,
                t.apellido_nombre AS solitantenombre,
                aprobado_por AS aprobadorid, 
                t2.apellido_nombre AS aprobadornombre,
                fecha_inicio,fecha_fin,hora_salida,hora_entrada,duracion,razon,observaciones,estado_tramite,
                b.estado AS bestado 
                from boleta AS b 
                INNER JOIN trabajador AS t ON  t.id =b.trabajador_id 
                left JOIN trabajador AS t2 ON t2.id = b.aprobado_por
                ORDER BY b.id ASC";

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
        boleta 
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
        boleta 
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
        $sql = "SELECT * FROM boleta WHERE id = $id";
        return $this->select($sql);
    }
    // public function verificar($nombre)
    // {
    //     $sql = "SELECT id,nombre FROM Boleta WHERE nombre = '$nombre' ";
    //     return $this->select($sql);
    // }
    
                   
    public function registrar($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$estado_tramite)
    {
        $sql = "INSERT INTO boleta (trabajador_id,aprobado_por,fecha_inicio,fecha_fin,hora_salida,hora_entrada,razon,estado_tramite) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$estado_tramite);
        return $this->insertar($sql, $array);
    }
    public function modificar($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$id)
    {
        $sql = "UPDATE boleta SET trabajador_id=?,aprobado_por=?,fecha_inicio=?,fecha_fin=?,hora_salida=?,hora_entrada=?,razon =?,update_at = NOW()  WHERE id = ?";
        $array = array($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon, $id);
        return $this->save($sql, $array);
    }
    public function eliminar($id)
    {
        $sql = "UPDATE boleta SET estado = ? WHERE id = ?";
        $array = array(0, $id);
        return $this->save($sql, $array);
    }

    
    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }
}
