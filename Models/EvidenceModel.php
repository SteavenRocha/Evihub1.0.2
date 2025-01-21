<?php
class EvidenceModel extends Query
{
    private $id_usuario, $id_sucursal, $name, $tipo, $size, $filePathRelative;
    public function __construct()
    {
        parent::__construct();
    }

    public function getRecentArchivos()
    {
        $sqlRecent = "SELECT 
                    id_archivo,
                    nombre_archivo,
                    tipo_archivo,
                    fecha_subida,
                    ruta,
                    size
                FROM 
                    ARCHIVO
                WHERE 
                    estado_archivo = 1
                ORDER BY 
                    fecha_subida DESC
                LIMIT 50";

        $sqlCount = "SELECT 
                    COUNT(*) AS total_archivos
                FROM 
                    ARCHIVO
                WHERE 
                    estado_archivo = 1";

        $recentFiles = $this->selectAll($sqlRecent);
        $totalFiles = $this->select($sqlCount);

        return [
            'archivos' => $recentFiles,
            'total_archivos' => $totalFiles['total_archivos'],
        ];
    }

    public function getRecentArchivosEmpleado(?int $id_empleado)
    {
        $sql = "SELECT 
                    a.id_archivo,
                    a.nombre_archivo,
                    a.tipo_archivo,
                    a.fecha_subida,
                    a.ruta,
                    a.size,
                    CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo
                FROM 
                    ARCHIVO a
                JOIN 
                    USUARIO u ON a.id_usuario = u.id_usuario
                JOIN
                    EMPLEADO e ON u.id_empleado = e.id_empleado
                WHERE 
                    estado_archivo = 1
                AND 
                    e.id_empleado = $id_empleado
                ORDER BY 
                    fecha_subida DESC
                LIMIT 50";

        $sqlCount = "SELECT 
                    COUNT(*) AS total_archivos
                FROM 
                    ARCHIVO a
                JOIN 
                    USUARIO u ON a.id_usuario = u.id_usuario
                WHERE 
                    a.estado_archivo = 1
                AND
                    u.id_empleado = $id_empleado";

        $recentFiles = $this->selectAll($sql);
        $totalFiles = $this->select($sqlCount);

        return [
            'archivos' => $recentFiles,
            'total_archivos' => $totalFiles['total_archivos'],
        ];
    }

    public function uploadFile(int $id_usuario, int $id_sucursal, string $name, string $tipo, string $size, string $filePathRelative)
    {
        // Asignar valores a las propiedades
        $this->id_usuario = $id_usuario;
        $this->id_sucursal = $id_sucursal;
        $this->name = $name;
        $this->tipo = $tipo;
        $this->size = $size;
        $this->filePathRelative = $filePathRelative;

        // Preparar y ejecutar la consulta
        $sql = "INSERT INTO ARCHIVO (id_usuario, id_sucursal, nombre_archivo, tipo_archivo, size, ruta) VALUES (?, ?, ?, ?, ?, ?)";
        $datos = array($this->id_usuario, $this->id_sucursal, $this->name, $this->tipo, $this->size, $this->filePathRelative);

        $data = $this->save($sql, $datos);

        // Retornar el resultado
        return $data == 1 ? "ok" : "error";
    }

    public function getEmpleados()
    {
        $sql = "SELECT 
                    id_empleado,
                    estado_empleado,
                    CONCAT(nombres, ' ', ape_paterno, ' ', ape_materno) AS nombre_completo,
                    id_sucursal
                    FROM EMPLEADO";
        $data['empleados'] = $this->selectAll($sql);
        return $data['empleados'];
    }

    public function getSucursales()
    {
        $sql = "SELECT * FROM SUCURSAL";
        $data['sucursal'] = $this->selectAll($sql);
        return $data['sucursal'];
    }

    public function filtrarArchivo(?int $sucursal_filtro, ?int $empleado_filtro, ?string $desde, ?string $hasta, ?int $rol)
    {
        $sql = "SELECT 
                a.id_archivo,
                a.nombre_archivo,
                a.tipo_archivo,
                a.fecha_subida,
                a.estado_archivo,
                a.ruta,
                a.size,
                e.id_empleado,
                e.dni,
                CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo,
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
            WHERE 1=1";

        $filtrosAplicados = [];

        if ($rol === 2) { 
            if (empty($desde) || empty($hasta)) {
                return "Debe especificar las fechas.";
            }
            $sql .= " AND a.fecha_subida BETWEEN '$desde' AND '$hasta'";
            $filtrosAplicados[] = "Desde $desde hasta $hasta";
        } else if ($rol === 1) { 
            if (!empty($sucursal_filtro)) {
                $sql .= " AND s.id_sucursal = $sucursal_filtro";
                $filtrosAplicados[] = "Sucursal: $sucursal_filtro";
            }

            if (!empty($empleado_filtro)) {
                $sql .= " AND e.id_empleado = $empleado_filtro";
                $filtrosAplicados[] = "Empleado: $empleado_filtro";
            }

            if (!empty($desde) && !empty($hasta)) {
                $sql .= " AND a.fecha_subida BETWEEN '$desde' AND '$hasta'";
                $filtrosAplicados[] = "Desde $desde hasta $hasta";
            }
        } else {
            return "Rol no válido.";
        }

        if ($rol === 1 && empty($sucursal_filtro) && empty($empleado_filtro) && empty($desde) && empty($hasta)) {
            return "Datos vacíos";
        }

        $sql .= " ORDER BY a.fecha_subida DESC";

        $data['archivos'] = $this->selectAll($sql);

        $countSql = "SELECT COUNT(*) AS total_archivos FROM ARCHIVO a
                 JOIN SUCURSAL s ON a.id_sucursal = s.id_sucursal
                 JOIN USUARIO u ON a.id_usuario = u.id_usuario
                 JOIN EMPLEADO e ON u.id_empleado = e.id_empleado
                 WHERE 1=1";

        if ($rol === 2) {
            if (!empty($desde) && !empty($hasta)) {
                $countSql .= " AND a.fecha_subida BETWEEN '$desde' AND '$hasta'";
            }
        } else if ($rol === 1) {
            if (!empty($sucursal_filtro)) {
                $countSql .= " AND s.id_sucursal = $sucursal_filtro";
            }
            if (!empty($empleado_filtro)) {
                $countSql .= " AND e.id_empleado = $empleado_filtro";
            }
            if (!empty($desde) && !empty($hasta)) {
                $countSql .= " AND a.fecha_subida BETWEEN '$desde' AND '$hasta'";
            }
        }

        $data['total_archivos'] = $this->select($countSql)['total_archivos'];
        $data['filtros_aplicados'] = $filtrosAplicados;

        return $data;
    }

    public function filtrarArchivoEmpleado(?string $desde, ?string $hasta, ?int $id)
    {
        $sql = "SELECT 
                a.id_archivo,
                a.nombre_archivo,
                a.tipo_archivo,
                a.fecha_subida,
                a.estado_archivo,
                a.size,
                a.ruta,
                e.id_empleado,
                e.dni,
                CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo,
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
            WHERE u.id_empleado = $id";

        $filtrosAplicados = [];

        if (!empty($desde) && !empty($hasta)) {
            $sql .= " AND a.fecha_subida BETWEEN '$desde' AND '$hasta'";
            $filtrosAplicados[] = "Desde $desde hasta $hasta";
        }

        if (empty($desde) && empty($hasta)) {
            return "Datos vacíos";
        }

        $sql .= " ORDER BY a.fecha_subida DESC";

        $data['filtro'] = $this->selectAll($sql);

        // Agregar la consulta de conteo de archivos
        $countSql = "SELECT COUNT(*) AS total_archivos FROM ARCHIVO a
                 JOIN SUCURSAL s ON a.id_sucursal = s.id_sucursal
                 JOIN USUARIO u ON a.id_usuario = u.id_usuario
                 JOIN EMPLEADO e ON u.id_empleado = e.id_empleado
                 WHERE u.id_empleado = $id";

        if (!empty($desde) && !empty($hasta)) {
            $countSql .= " AND a.fecha_subida BETWEEN '$desde' AND '$hasta'";
        }

        // Obtener el número de archivos que cumplen las condiciones
        $data['total_archivos'] = $this->select($countSql)['total_archivos'];
        $data['filtros_aplicados'] = $filtrosAplicados;

        return $data;
    }

    /*     public function filtrarArchivo(?int $sucursal_filtro, ?int $empleado_filtro, ?string $desde, ?string $hasta)
    {
        if (empty($sucursal_filtro) && empty($empleado_filtro) && empty($desde) && empty($hasta)) {
            return "datos vacios";
        } else if (!empty($sucursal_filtro) && empty($empleado_filtro) && empty($desde) && empty($hasta)) {
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
                    s.id_sucursal = $sucursal_filtro";
        } else if (empty($sucursal_filtro) && !empty($empleado_filtro) && empty($desde) && empty($hasta)) {
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
                e.id_empleado = $empleado_filtro";
        } else if (empty($sucursal_filtro) && empty($empleado_filtro) && !empty($desde) && !empty($hasta)) {
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
                        DATE(a.fecha_subida) BETWEEN '$desde' AND '$hasta';";
        } else if (!empty($sucursal_filtro) && !empty($empleado_filtro) && empty($desde) && empty($hasta)) {
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
                s.id_sucursal = $sucursal_filtro
            AND 
                e.id_empleado = $empleado_filtro;";

        } else if (!empty($sucursal_filtro) && empty($empleado_filtro) && !empty($desde) && !empty($hasta)) {
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
                        s.id_sucursal = $sucursal_filtro
                    AND 
                        DATE(a.fecha_subida) BETWEEN '$desde' AND '$hasta';";
        } else if (empty($sucursal_filtro) && !empty($empleado_filtro) && !empty($desde) && !empty($hasta)) {
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
                        DATE(a.fecha_subida) BETWEEN '$desde' AND '$hasta';";
        } else if (!empty($sucursal_filtro) && !empty($empleado_filtro) && !empty($desde) && !empty($hasta)) {
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
                        s.id_sucursal = $sucursal_filtro
                    AND 
                        e.id_empleado = $empleado_filtro
                    AND 
                        DATE(a.fecha_subida) BETWEEN '$desde' AND '$hasta';";
        }
        $data['filtro'] = $this->selectAll($sql);
        return $data['filtro'];
    } */

    public function detallesArchivo(int $id)
    {
        $sql = "SELECT 
                a.id_archivo,
                a.nombre_archivo,
                a.tipo_archivo,
                a.fecha_subida,
                a.estado_archivo,
                a.size,
                a.ruta,
                e.id_empleado,
                e.dni,
                CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo,
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
                    a.id_archivo = $id";

        $data['details'] = $this->selectAll($sql);
        return $data['details'];
    }
}
