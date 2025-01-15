<?php
class EvidenceModel extends Query
{
    private $id_usuario, $id_sucursal, $name, $tipo, $size;
    public function __construct()
    {
        parent::__construct();
    }

    public function getRecentArchivos()
    {
        $sql = "SELECT 
                    a.id_archivo,
                    a.nombre_archivo,
                    a.tipo_archivo,
                    a.fecha_subida,
                    a.size,
                    s.id_sucursal,
                    s.nombre_sucursal,
                    u.id_usuario,
                    u.usuario,
                    e.id_empleado,
                    CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo_empleado
                FROM 
                    ARCHIVO a
                JOIN 
                    SUCURSAL s ON a.id_sucursal = s.id_sucursal
                JOIN 
                    USUARIO u ON a.id_usuario = u.id_usuario
                JOIN 
                    EMPLEADO e ON u.id_empleado = e.id_empleado
                WHERE 
                    a.estado_archivo = 1
                ORDER BY 
                    a.fecha_subida DESC
                LIMIT 10";
        $data['users'] = $this->selectAll($sql);
        return $data['users'];
    }

    public function uploadFile(int $id_usuario, int $id_sucursal, string $name, string $tipo, string $size)
    {
        // Asignar valores a las propiedades
        $this->id_usuario = $id_usuario;
        $this->id_sucursal = $id_sucursal;
        $this->name = $name;
        $this->tipo = $tipo;
        $this->size = $size;

        // Preparar y ejecutar la consulta
        $sql = "INSERT INTO ARCHIVO (id_usuario, id_sucursal, nombre_archivo, tipo_archivo, size) VALUES (?, ?, ?, ?, ?)";
        $datos = array($this->id_usuario, $this->id_sucursal, $this->name, $this->tipo, $this->size);

        $data = $this->save($sql, $datos);

        // Retornar el resultado
        return $data == 1 ? "ok" : "error";
    }

    public function getEmpleados()
    {
        $sql = "SELECT 
                    id_empleado,
                    estado_empleado,
                    CONCAT(nombres, ' ', ape_paterno, ' ', ape_materno) AS nombre_completo FROM EMPLEADO";
        $data['empleados'] = $this->selectAll($sql);
        return $data['empleados'];
    }

    public function getSucursales()
    {
        $sql = "SELECT * FROM SUCURSAL";
        $data['sucursal'] = $this->selectAll($sql);
        return $data['sucursal'];
    }

    public function filtrarArchivo(int $sucursal_filtro, int $empleado_filtro, string $desde, string $hasta)
    {

        $data = array($sucursal_filtro, $empleado_filtro, $desde, $hasta);

        $desde = date('Y-m-d', strtotime($desde));  // Asegura que esté en formato adecuado
        $hasta = date('Y-m-d', strtotime($hasta));  // Asegura que esté en formato adecuado

        print_r($desde);
        print_r($hasta);

        $sql = "SELECT 
                    a.id_archivo,
                    a.nombre_archivo,
                    a.tipo_archivo,
                    a.fecha_subida,
                    a.estado_archivo,
                    e.id_empleado,
                    e.dni,
                    e.nombres,
                    e.ape_paterno,
                    e.ape_materno,
                    s.id_sucursal,
                    s.nombre_sucursal,
                    u.id_usuario,
                    u.usuario
                FROM 
                    ARCHIVO a
                JOIN 
                    SUCURSAL s ON a.id_sucursal = s.id_sucursal
                JOIN 
                    USUARIO u ON a.id_usuario = u.id_usuario
                JOIN
                    EMPLEADO e ON u.id_empleado = e.id_empleado
                WHERE 
                    e.id_empleado = $empleado_filtro
                AND
                    s.id_sucursal = $sucursal_filtro
                AND 
                    DATE(a.fecha_subida) BETWEEN $desde AND $hasta;
                ";

        $data['filtro'] = $this->selectAll($sql);
        return $data['filtro'];
    }
}
