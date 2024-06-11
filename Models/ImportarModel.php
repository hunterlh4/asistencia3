<?php
class ImportarModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getTrabajadorAll()
    {
        $sql = "SELECT * from trabajador";
        return $this->selectAll($sql);
    }
    public function getAsistenciaAll()
    {
        $sql = "SELECT * from asistencia";
        return $this->selectAll($sql);
    }
    public function gethorarioDetalle($horario_id)
    {
        $sql = "SELECT * from horarioDetalle where horario_id='$horario_id'";
        return $this->select($sql);
    }
   
    // public function getTrabajador($telefono_id)
    // {
    //     $sql = "SELECT id,telefono_id,horario_id FROM trabajador WHERE telefono_id = '$telefono_id' ";
    //     return $this->select($sql);
    // }
    public function getTrabajador($telefono_id)
    {
        $sql = "SELECT t.id AS tid,t.horarioDetalle_id ,hd.hora_entrada,hd.hora_salida,hd.total
        FROM trabajador AS t INNER JOIN horariodetalle AS hd
        ON hd.id = t.horariodetalle_id WHERE telefono_id = '$telefono_id' ";
        return $this->select($sql);
    }
    public function getAsistencia($telefono_id,$fecha)
    {
        $sql = "SELECT t.id AS tid,a.id as aid,fecha,entrada,salida ,t.horarioDetalle_id as th
        FROM asistencia AS a INNER JOIN trabajador as t ON t.id = a.trabajador_id 
        where telefono_id = '$telefono_id' and fecha = '$fecha'";
        // $sql = "SELECT id,trabajador_id,fecha FROM asistencia WHERE trabajador_id = '$telefono_id' and fecha = '$fecha' ";
        return $this->select($sql);
    }
    
    public function registrarTrabajador($nombre,$telefono_id,$institucion,$modalidad_trabajo)
    {
        $sql = "INSERT INTO trabajador (apellido_nombre,telefono_id,institucion,modalidad_trabajo) VALUES (?,?,?,?)";
        $array = array($nombre,$telefono_id);
        return $this->insertar($sql, $array);
    }

    // public function registrarAsistenciaantiguo($trabajador_id,$licencia,$fecha,$entrada,$salida,$total_reloj,$total,$tardanza,$tardanza_cantidad,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8)
    // {
    //     $sql = "INSERT INTO asistencia (trabajador_id,licencia_id,fecha,entrada,salida,total_reloj,total,tardanza,tardanza_cantidad,justificacion,reloj_1,reloj_2,reloj_3,reloj_4,reloj_5,reloj_6,reloj_7,reloj_8) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    //     $array = array($trabajador_id,$licencia,$fecha,$entrada,$salida,$total_reloj,$total,$tardanza,$tardanza_cantidad,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8);
    //     return $this->insertar($sql, $array);
    // }
   
    public function registrarAsistencia($trabajador_id,$licencia,$fecha,$entrada,$salida,$tardanza,$tardanza_cantidad,$total_reloj,$total,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8)
    {
        $sql = "INSERT INTO asistencia (trabajador_id,licencia,fecha,entrada,salida,tardanza,tardanza_cantidad,total_reloj,total,justificacion,reloj_1,reloj_2,reloj_3,reloj_4,reloj_5,reloj_6,reloj_7,reloj_8) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($trabajador_id,$licencia,$fecha,$entrada,$salida,$tardanza,$tardanza_cantidad,$total_reloj,$total,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8);
        return $this->insertar($sql, $array);
    }
    public function modificarAsistencia($trabajador_id,$licencia,$fecha,$entrada,$salida,$tardanza,$tardanza_cantidad,$total_reloj,$total,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8,$id)
    {
        $sql = "UPDATE asistencia SET trabajador_id=?,licencia=?,fecha=?,entrada=?,salida=?,tardanza=?,tardanza_cantidad=?,total_reloj=?,total=?,justificacion=?,reloj_1=?,reloj_2=?,reloj_3=?,reloj_4=?,reloj_5=?,reloj_6=?,reloj_7=?,reloj_8=?  ,update_at = NOW()  WHERE id = ?";
        $array = array($trabajador_id,$licencia,$fecha,$entrada,$salida,$tardanza,$tardanza_cantidad,$total_reloj,$total,$justificacion,$reloj_1,$reloj_2,$reloj_3,$reloj_4,$reloj_5,$reloj_6,$reloj_7,$reloj_8, $id);
        return $this->save($sql, $array);
    }

    public function registrarlog($usuario,$accion,$tabla,$detalles){
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario,$accion,$tabla,$detalles);
        return $this->save($sql, $array);
    }


    public function findAllFestividades(){
        $sql = "SELECT * from festividad";
        return $this->selectAll($sql);
    }
}
