<?php
class ReporteModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getReporteTrabajadorAll()
    {
       
        $sql = "SELECT * FROM asistencia ORDER BY id asc ";
        // $sql = "SELECT T.id as tid,T.estado as testado from trabajadores as T ORDER BY id ASC";
        return $this->selectAll($sql);
    }
    public function Reporte_Trabajador($id,$mes,$anio)
    {
        $sql = "SELECT 
                t.apellido_nombre AS trabajador_nombre,
                fecha,
                licencia,
                TO_CHAR(entrada, 'HH24:MI') AS entrada,
                TO_CHAR(salida, 'HH24:MI') AS salida,
                TO_CHAR(total_reloj, 'HH24:MI') AS total_reloj,
                TO_CHAR(total, 'HH24:MI') AS total,
                CASE WHEN tardanza_cantidad <> 0 THEN 1 ELSE 0 END AS tardanza_cantidad,
                CASE WHEN licencia = 'NMS' THEN 1 ELSE 0 END AS inasistencia
                FROM 
                        asistencia AS a
                INNER JOIN trabajador as t ON t.id = a.trabajador_id
                WHERE 
                        EXTRACT(MONTH FROM fecha) = $mes
                        AND EXTRACT(YEAR FROM fecha) = $anio
                        AND trabajador_id = $id
                ORDER BY fecha asc";

        return $this->selectAll($sql);
    }
    public function getSeguimiento($id){
        $sql = "SELECT * FROM seguimientoTrabajador WHERE id = $id";
       
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
    public function verificar($id)
    {
        $sql = "SELECT id,documento FROM seguimientotrabajador WHERE id = '$id' ";
        return $this->select($sql);
    }
    public function registrar($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin)
    {
        $sql = "INSERT INTO seguimientoTrabajador (trabajador_id, regimen, direccion, cargo, documento, sueldo, fecha_inicio, fecha_fin) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin);
        return $this->insertar($sql, $array);
    }
    public function modificar($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin,$estado,$id)
    {
        $sql = "UPDATE seguimientoTrabajador SET trabajador_id=?,regimen=?,direccion=?,cargo=?,documento=?,sueldo=?,fecha_inicio=?,fecha_fin=?,estado=? ,update_at = NOW()  WHERE id = ?";
        $array = array($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin,$estado, $id);
        return $this->save($sql, $array);
    }
    public function modificarSinArchivo($trabajador_id,$regimen,$direccion, $cargo,$sueldo,$fecha_inicio,$fecha_fin,$estado,$id)
    {
        $sql = "UPDATE seguimientoTrabajador SET trabajador_id=?,regimen=?,direccion=?,cargo=?,sueldo=?,fecha_inicio=?,fecha_fin=?,estado=? ,update_at = NOW()  WHERE id = ?";
        $array = array($trabajador_id,$regimen,$direccion, $cargo,$sueldo,$fecha_inicio,$fecha_fin,$estado, $id);
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
