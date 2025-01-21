<?php
class EmployeeModel extends Query
{

    private $id_empleado, $estado, $dni, $nombres, $ape_paterno, $ape_materno, $celular, $id_sucursal_empleado;

    public function __construct()
    {
        parent::__construct();
    }

    public function getEmpleados()
    {
        $sql = "SELECT 
                    e.id_empleado,
                    e.dni,
                    CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo,
                    e.n_celular,
                    e.estado_empleado,
                    e.fecha_registro,
                    s.nombre_sucursal
                FROM 
                    EMPLEADO e
                INNER JOIN 
                    SUCURSAL s ON e.id_sucursal = s.id_sucursal
                ORDER BY 
                    e.id_empleado DESC";
        $data['employees'] = $this->selectAll($sql);
        return $data['employees'];
    }

    public function getSucursales()
    {
        $sql = "SELECT 
                    id_sucursal, 
                    nombre_sucursal
                FROM 
                    SUCURSAL
                WHERE 
                    estado_sucursal = 1";
        $data['sucursales'] = $this->selectAll($sql);
        return $data['sucursales'];
    }

    public function registrarEmpleado(string $dni, string $nombres, string $ape_paterno, string $ape_materno, string $celular, int $id_sucursal_empleado)
    { 
        $this->dni = $dni;
        $this->nombres = $nombres;
        $this->ape_paterno = $ape_paterno;
        $this->ape_materno = $ape_materno;
        $this->celular = $celular;
        $this->id_sucursal_empleado = $id_sucursal_empleado;

        $verificar = "SELECT * FROM EMPLEADO WHERE dni = '$this->dni'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "INSERT INTO EMPLEADO(id_sucursal, dni, nombres, ape_paterno, ape_materno, n_celular) VALUES (?,?,?,?,?,?)";
            $datos['employee'] = array($this->id_sucursal_empleado, $this->dni, $this->nombres, $this->ape_paterno, $this->ape_materno, $this->celular);
            $data = $this->save($sql, $datos['employee']);

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

    public function editarEmpleado(int $id)
    {
        $sql = "SELECT 
                    e.id_empleado, 
                    e.dni,
                    e.nombres, 
                    e.ape_paterno, 
                    e.ape_materno,
                    e.n_celular,
                    s.id_sucursal,
                    s.nombre_sucursal
                FROM 
                    EMPLEADO e 
                LEFT JOIN 
                    SUCURSAL s 
                ON 
                    e.id_sucursal = s.id_sucursal
                WHERE 
                    e.id_empleado = $id";
        $data['edit'] = $this->select($sql);
        return $data['edit'];
    }

    public function modificarEmpleado(string $dni, string $nombres, string $ape_paterno, string $ape_materno, string $celular, int $id_sucursal_empleado, int $id_empleado)
    {

        $this->dni = $dni;
        $this->nombres = $nombres;
        $this->ape_paterno = $ape_paterno;
        $this->ape_materno = $ape_materno;
        $this->celular = $celular;
        $this->id_sucursal_empleado = $id_sucursal_empleado;
        $this->id_empleado = $id_empleado;

        $verificar = "SELECT * FROM EMPLEADO WHERE dni = '$this->dni' and id_empleado != '$this->id_empleado'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "UPDATE EMPLEADO SET dni = ?, nombres = ?, ape_paterno = ?, ape_materno = ?, n_celular = ?, id_sucursal = ? WHERE id_empleado = ?";
            $datos['employee'] = array($this->dni, $this->nombres, $this->ape_paterno, $this->ape_materno, $this->celular, $this->id_sucursal_empleado, $this->id_empleado);
            $data = $this->save($sql, $datos['employee']);

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

    public function accionEmpleado(int $estado, int $id)
    {
        $this->id_empleado = $id;
        $this->estado = $estado;

        $sql = "UPDATE EMPLEADO SET estado_empleado = ? WHERE id_empleado = ?";
        $datos['deshabilitar'] = array($this->estado, $this->id_empleado);
        $data = $this->save($sql, $datos['deshabilitar']);
        return $data;
    } 
}
?>