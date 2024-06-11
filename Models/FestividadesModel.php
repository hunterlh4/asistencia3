<?php
class FestividadesModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function findAll($mes)
    {
        $sql = "SELECT * 
    FROM festividad
    ORDER BY 
        CASE 
            WHEN mes >= '$mes' THEN 0
            ELSE 1
        END,
        mes,
        dia;";
        return $this->selectAll($sql);
    }

    public function findById($id)
    {
        $sql = "SELECT * from festividad where id = $id";
        return $this->select($sql);
    }

    public function findByDate($dia,$mes)
    {
        $sql = "SELECT * FROM festividad WHERE dia = '$dia' and mes = '$mes' ";
        return $this->select($sql);
    }

    public function create($dia, $mes, $nombre, $descripcion, $tipo)
    {
        $sql = "INSERT INTO festividad (dia,mes,nombre,descripcion,tipo) VALUES (?,?,?,?,?)";
        $array = array($dia, $mes, $nombre, $descripcion, $tipo);
        return $this->insertar($sql, $array);
    }
    public function update($dia, $mes, $nombre, $descripcion, $tipo, $estado, $id)
    {
        $sql = "UPDATE festividad SET dia=?,mes = ?,nombre=?,descripcion=?,tipo=?,estado=?, update_at=NOW()  WHERE id = ?";
        $array = array($dia, $mes, $nombre, $descripcion, $tipo, $estado, $id);
        return $this->save($sql, $array);
    }
    public function createLog($usuario, $accion, $tabla, $detalles)
    {
        $sql = "INSERT INTO log (usuario_id,tipo_accion,tabla_afectada,detalles) VALUES (?,?,?,?)";
        $array = array($usuario, $accion, $tabla, $detalles);
        return $this->save($sql, $array);
    }
}
