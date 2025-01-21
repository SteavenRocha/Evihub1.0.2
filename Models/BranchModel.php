<?php
class BranchModel extends Query
{

    private $nombre_sucursal, $id, $estado;

    public function __construct()
    {
        parent::__construct();
    }

    public function getSucursales()
    {
        $sql = "SELECT 
                    *
                FROM 
                    SUCURSAL
                ORDER BY 
                    id_sucursal DESC";
        $data['sucursal'] = $this->selectAll($sql);
        return $data['sucursal'];
    }

    public function registrarSucursal(string $nombre_sucursal)
    { 
        $this->nombre_sucursal = $nombre_sucursal;

        $verificar = "SELECT * FROM SUCURSAL WHERE nombre_sucursal = '$this->nombre_sucursal'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "INSERT INTO SUCURSAL(nombre_sucursal) VALUES (?)";
            $datos['sucursal'] = array($this->nombre_sucursal);
            $data = $this->save($sql, $datos['sucursal']);

            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    public function editarSucursal(int $id)
    {
        $sql = "SELECT 
                    *
                FROM 
                    SUCURSAL
                WHERE 
                    id_sucursal = $id";
        $data['edit'] = $this->select($sql);
        return $data['edit'];
    }

    public function modificarSucursal(string $nombre_sucursal, int $id)
    {
        $this->nombre_sucursal = $nombre_sucursal;
        $this->id = $id;
        
        $verificar = "SELECT * FROM SUCURSAL WHERE nombre_sucursal = '$this->nombre_sucursal' and id_sucursal != $this->id";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "UPDATE SUCURSAL SET nombre_sucursal = ? WHERE id_sucursal = ?";
            $datos['sucursal'] = array($this->nombre_sucursal, $this->id);
            $data = $this->save($sql, $datos['sucursal']);

            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    public function accionSucursal(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;

        $sql = "UPDATE SUCURSAL SET estado_sucursal = ? WHERE id_sucursal = ?";
        $datos['deshabilitar'] = array($this->estado, $this->id);
        $data = $this->save($sql, $datos['deshabilitar']);
        return $data;
    }  
}
?>