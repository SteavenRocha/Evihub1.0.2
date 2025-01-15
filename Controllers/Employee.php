<?php
class Employee extends Controller
{
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['activo']) || $_SESSION['activo'] !== true) {
            header("location: " . BASE_URL);
            die();
        }
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'EMPLEADOS';
        $this->views->getView('Pages', "employees", ($data));
    }

    public function listar()
    {
        $data['employees'] = $this->model->getEmpleados();
        echo json_encode($data['employees'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function sucursales()
    {
        $data['sucursales'] = $this->model->getSucursales();
        echo json_encode($data['sucursales'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        $dni = $_POST['dni'];
        $nombres_completos = $_POST['nombres_empleado'];
        $celular = $_POST['celular'];
        $id_sucursal_empleado = $_POST['id_sucursal_empleado'];

        // Eliminar espacios al principio y al final, y dividir por espacios
        $nombres_completos = trim($nombres_completos);

        // Dividir el nombre completo en partes usando una expresión regular para manejar múltiples espacios
        $partes = preg_split('/\s+/', $nombres_completos);
        $nombres = '';
        $ape_paterno = '';
        $ape_materno = '';

        // Verificar la cantidad de partes en el nombre completo
        if (count($partes) == 2) {
            // Si tiene 2 partes, es inválido
            echo json_encode("invalido", JSON_UNESCAPED_UNICODE);
            die(); // Salir inmediatamente si el nombre es inválido
        } elseif (count($partes) == 3) {
            // Caso con 3 partes: [Nombre] [ApellidoPaterno] [ApellidoMaterno]
            $nombres = $partes[0];           // Primer nombre
            $ape_paterno = $partes[1];       // Apellido paterno
            $ape_materno = $partes[2];       // Apellido materno
        } elseif (count($partes) == 4) {
            // Caso con 4 partes: [Nombre1] [Nombre2] [ApellidoPaterno] [ApellidoMaterno]
            $nombres = $partes[0] . ' ' . $partes[1];  // Dos primeros nombres
            $ape_paterno = $partes[2];               // Apellido paterno
            $ape_materno = $partes[3];               // Apellido materno
        } else {
            echo json_encode("invalido", JSON_UNESCAPED_UNICODE);
            die(); // Salir inmediatamente si el nombre no es válido
        }

        if (empty($dni) || empty($nombres) || empty($ape_paterno) || empty($ape_materno) || empty($celular) || empty($id_sucursal_empleado)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            $data['employee'] = $this->model->registrarEmpleado($dni, $nombres, $ape_paterno, $ape_materno, $celular, $id_sucursal_empleado);
            if ($data['employee'] == "ok") {
                $msg = "si";
            } else if ($data['employee'] == "existe") {
                $msg = "existe";
            } else {
                $msg = "Error al registrar el empleado";
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data['edit'] = $this->model->editarEmpleado($id);
        echo json_encode($data['edit'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function modificar()
    {

        $dni = $_POST['dni'];
        $nombres_completos = $_POST['nombres_empleado'];
        $celular = $_POST['celular'];
        $id_sucursal_empleado = $_POST['id_sucursal_empleado'];
        $id_empleado = $_POST['id_empleado'];

        // Dividir el nombre completo en partes
        $partes = explode(' ', $nombres_completos);
        $nombres = '';
        $ape_paterno = '';
        $ape_materno = '';
        // Manejar casos de 3 o 4 partes en el nombre
        if (count($partes) == 3) {
            // Caso con 3 partes: [Nombre1] [ApellidoPaterno] [ApellidoMaterno]
            $nombres = $partes[0];           // Primer nombre
            $ape_paterno = $partes[1];       // Apellido paterno
            $ape_materno = $partes[2];       // Apellido materno
        } elseif (count($partes) >= 4) {
            // Caso con 4 o más partes: [Nombre1] [Nombre2] [ApellidoPaterno] [ApellidoMaterno]
            $nombres = $partes[0] . ' ' . $partes[1]; // Dos primeros nombres
            $ape_paterno = $partes[2];               // Apellido paterno
            $ape_materno = $partes[3];               // Apellido materno
        } else {
            echo json_encode("invalido", JSON_UNESCAPED_UNICODE);
            die(); // Salir inmediatamente si los nombres no son válidos
        }

        if (empty($dni) || empty($nombres) || empty($ape_paterno) || empty($ape_materno) || empty($celular) || empty($id_sucursal_empleado)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            $data['employee'] = $this->model->modificarEmpleado($dni, $nombres, $ape_paterno, $ape_materno, $celular, $id_sucursal_empleado, $id_empleado);
            if ($data['employee'] == "modificado") {
                $msg = "modificado";
            } else if ($data['employee'] == "existe") {
                $msg = "El empleado ya existe";
            } else {
                $msg = "Error al registrar el empleado";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deshabilitar(int $id)
    {

        $data['deshabilitar'] = $this->model->accionEmpleado(0, $id);
        if ($data['deshabilitar'] == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al deshabilitar empleado";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function habilitar(int $id)
    {

        $data['deshabilitar'] = $this->model->accionEmpleado(1, $id);
        if ($data['deshabilitar'] == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al deshabilitar empleado";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
