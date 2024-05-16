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

    public function getMisBoletas($id)
    {
        $sql = "SELECT 

        t1.id AS trabajador_id,
        t1.apellido_nombre AS nombre_trabajador,
        t2.id AS aprobado_por,
        t2.apellido_nombre AS nombre_aprobador,
       
        b.id AS boleta_id,
        b.numero AS numero,
        b.fecha_inicio AS fecha_inicio,
        b.fecha_fin AS fecha_fin,
        b.hora_salida AS hora_salida,
        b.hora_entrada AS hora_entrada,
        b.razon AS razon,
        b.observaciones AS observaciones,
        b.estado_tramite AS estado_tramite
        FROM 
            boleta AS b
        LEFT JOIN 
            trabajador AS t1 ON b.trabajador_id = t1.id
        LEFT JOIN 
            trabajador AS t2 ON b.aprobado_por = t2.id
        inner JOIN
            cargo AS c ON c.id = t1.cargo_id
        
        WHERE b.trabajador_id = $id
        ORDER BY b.id asc ";

        return $this->selectAll($sql);
    }
    public function getusuario($id){
        $sql = "SELECT trabajador_id,t.apellido_nombre
                FROM usuario as u
                inner join trabajador as t on t.id=u.trabajador_id 
                WHERE u.id = $id";
        return $this->select($sql);
    }

    public function getMisRevisiones($id)
    {
        $sql = "SELECT 
        t1.id AS trabajador_id,
        t1.apellido_nombre AS nombre_trabajador,
        t2.id AS aprobado_por,
        t2.apellido_nombre AS nombre_aprobador,
        b.id AS boleta_id,
        b.numero AS numero,
        b.fecha_inicio AS fecha_inicio,
        b.fecha_fin AS fecha_fin,
        b.hora_salida AS hora_salida,
        b.hora_entrada AS hora_entrada,
        b.razon AS razon,
        b.observaciones AS observaciones,
        b.estado_tramite AS estado_tramite
        FROM 
            boleta AS b
        LEFT JOIN 
            trabajador AS t1 ON b.trabajador_id = t1.id
        LEFT JOIN 
            trabajador AS t2 ON b.aprobado_por = t2.id
        inner JOIN
            cargo AS c ON c.id = t1.cargo_id
        WHERE aprobado_por = $id
        ORDER BY b.id asc";
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
    public function verificar($id)
    {
        $sql = "SELECT * FROM Boleta WHERE id = '$id' ";
        return $this->select($sql);
    }
    
                   
    public function registrar($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$estado_tramite)
    {
        $sql = "INSERT INTO boleta (trabajador_id,aprobado_por,fecha_inicio,fecha_fin,hora_salida,hora_entrada,razon,estado_tramite) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$estado_tramite);
        return $this->insertar($sql, $array);
    }
    public function modificar($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon,$id)
    {
        $sql = "UPDATE boleta SET trabajador_id=?,aprobado_por=?,fecha_inicio=?,fecha_fin=?,hora_salida=?,hora_entrada=?,razon =?, update_at=NOW()  WHERE id = ?";
        $array = array($solicitante, $aprobador,$fecha_inicio,$fecha_fin,$salida,$entrada,$razon, $id);
        return $this->save($sql, $array);
    }
    public function Revisar($id,$accion,$observacion)
    {
        $sql = "UPDATE boleta SET estado_tramite = ? , observaciones=? WHERE id = ?";
        $array = array($accion,$observacion,$id);
        return $this->save($sql, $array);
    }

    
    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }
}
