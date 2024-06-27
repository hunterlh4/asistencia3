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
                TO_CHAR(reloj_1, 'HH24:MI') AS reloj_1,
                TO_CHAR(reloj_2, 'HH24:MI') AS reloj_2,
                TO_CHAR(reloj_3, 'HH24:MI') AS reloj_3,
                TO_CHAR(reloj_4, 'HH24:MI') AS reloj_4,
                TO_CHAR(reloj_5, 'HH24:MI') AS reloj_5,
                TO_CHAR(reloj_6, 'HH24:MI') AS reloj_6,
                TO_CHAR(reloj_7, 'HH24:MI') AS reloj_7,
                TO_CHAR(reloj_8, 'HH24:MI') AS reloj_8,
                justificacion,

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

    public function reporteGeneral($mes,$anio){
        $mes_formateado = sprintf("%02d", $mes);
        //  $sql = "SELECT 
        //         t.apellido_nombre AS trabajador_nombre,
        //         fecha,
        //         licencia,
        //         tardanza_cantidad,
        //         justificacion

               
        //         FROM 
        //                 asistencia AS a
        //         INNER JOIN trabajador as t ON t.id = a.trabajador_id
        //         WHERE 
        //                 EXTRACT(MONTH FROM fecha) = $mes
        //                 AND EXTRACT(YEAR FROM fecha) = $anio
                        
        //         ORDER BY t.apellido_nombre, fecha asc";

        $sql = "WITH dias_del_mes AS (
                SELECT generate_series(
                         DATE_TRUNC('month', DATE '$anio-$mes_formateado-01'),  -- Primer día del mes
                        (DATE_TRUNC('month', DATE '$anio-$mes_formateado-01') + INTERVAL '1 month - 1 day'),  -- Último día del mes
                        INTERVAL '1 day'
                    ) AS fecha
                )

                SELECT
                    t.apellido_nombre AS trabajador_nombre,
                    STRING_AGG(
                        TO_CHAR(DATE_TRUNC('day', d.fecha), 'YYYY-MM-DD') || '_' || a.licencia,
                        ' '
                    ) AS detalles
                FROM
                    dias_del_mes d
                LEFT JOIN asistencia a ON DATE_TRUNC('day', a.fecha) = d.fecha
                LEFT JOIN trabajador t ON t.id = a.trabajador_id
                WHERE t.apellido_nombre IS NOT NULL
                GROUP BY
                    trabajador_nombre
                ORDER BY
                    trabajador_nombre ASC";
         return $this->selectAll($sql);


    }

    public function getTrabajador($id,$mes,$anio){
    //     $sql  ="SELECT 
    //     t.apellido_nombre AS trabajador_nombre, 
    //     b.fecha_inicio AS fecha,
    //     hd.hora_entrada AS horario_entrada,
    //     hd.hora_salida AS horario_salida, 
    //     SUM(CASE WHEN b.razon = 'Motivos Particulares' THEN 1 ELSE 0 END) AS total_motivos_particulares
    // FROM 
    //     trabajador AS t
    // INNER JOIN 
    //     horariodetalle AS hd ON hd.id = t.horariodetalle_id
    // LEFT JOIN 
    //     boleta AS b ON b.trabajador_id = t.id
    // WHERE 
    //     t.id = $id AND 
    //     estado_tramite = 'Aprobado' AND 
    //     razon = 'Motivos Particulares' AND
    //     EXTRACT(MONTH FROM b.fecha_inicio) = $mes AND 
    //     EXTRACT(YEAR FROM b.fecha_inicio) = $anio 
    // GROUP BY 
    //     t.apellido_nombre, 
    //     b.fecha_inicio, 
    //     hd.hora_entrada, 
    //     hd.hora_salida";
        $sql = "SELECT 
        t.apellido_nombre AS trabajador_nombre, 
        b.fecha_inicio AS fecha,
        hd.hora_entrada AS horario_entrada,
        hd.hora_salida AS horario_salida, 
        CASE WHEN b.razon = 'Motivos Particulares' THEN 1 ELSE 0 END AS total_motivos_particulares
    FROM 
        trabajador AS t
    INNER JOIN 
        horariodetalle AS hd ON hd.id = t.horariodetalle_id
    left JOIN 
        boleta AS b ON b.trabajador_id = t.id AND EXTRACT(MONTH FROM b.fecha_inicio) = $mes AND EXTRACT(YEAR FROM b.fecha_inicio) = $anio
    WHERE 
        t.id = $id";
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
    // public function verificar($id)
    // {
    //     $sql = "SELECT id,documento FROM seguimientotrabajador WHERE id = '$id' ";
    //     return $this->select($sql);
    // }
    // public function registrar($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin)
    // {
    //     $sql = "INSERT INTO seguimientoTrabajador (trabajador_id, regimen, direccion, cargo, documento, sueldo, fecha_inicio, fecha_fin) VALUES (?,?,?,?,?,?,?,?)";
    //     $array = array($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin);
    //     return $this->insertar($sql, $array);
    // }
    // public function modificar($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin,$estado,$id)
    // {
    //     $sql = "UPDATE seguimientoTrabajador SET trabajador_id=?,regimen=?,direccion=?,cargo=?,documento=?,sueldo=?,fecha_inicio=?,fecha_fin=?,estado=? ,update_at = NOW()  WHERE id = ?";
    //     $array = array($trabajador_id,$regimen,$direccion, $cargo,$documento,$sueldo,$fecha_inicio,$fecha_fin,$estado, $id);
    //     return $this->save($sql, $array);
    // }
    // public function modificarSinArchivo($trabajador_id,$regimen,$direccion, $cargo,$sueldo,$fecha_inicio,$fecha_fin,$estado,$id)
    // {
    //     $sql = "UPDATE seguimientoTrabajador SET trabajador_id=?,regimen=?,direccion=?,cargo=?,sueldo=?,fecha_inicio=?,fecha_fin=?,estado=? ,update_at = NOW()  WHERE id = ?";
    //     $array = array($trabajador_id,$regimen,$direccion, $cargo,$sueldo,$fecha_inicio,$fecha_fin,$estado, $id);
    //     return $this->save($sql, $array);
    // }
   
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
