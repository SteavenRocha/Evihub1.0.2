<?php
class Evidence extends Controller
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
        $data['title'] = 'EVIDENCIAS';
        $this->views->getView('Pages', "evidences", ($data));
    }

    public function listarRecent()
    {
        $data['recent'] = $this->model->getRecentArchivos();
        echo json_encode($data['recent'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function upload()
    {
        $archivo = $_FILES['file'];
        $tmp = $archivo['tmp_name'];
        $name = $archivo['name'];
        $tipo = $archivo['type'];
        $size = $archivo['size'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_sucursal = $_SESSION['id_sucursal'];

        // Llamada al modelo para guardar la información del archivo en la base de datos
        $data = $this->model->uploadFile($id_usuario, $id_sucursal, $name, $tipo, $size);

        if ($data == "ok") {
            $destino = 'assets/archivos';

            // Crear carpeta raíz si no existe
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }

            // Crear carpeta específica de la sucursal
            $carpetaSucursal = $destino . '/' . $id_sucursal;
            if (!file_exists($carpetaSucursal)) {
                mkdir($carpetaSucursal, 0777, true);
            }

            // Crear carpeta específica del usuario dentro de la sucursal
            $carpetaUsuario = $carpetaSucursal . '/' . $id_usuario;
            if (!file_exists($carpetaUsuario)) {
                mkdir($carpetaUsuario, 0777, true);
            }

            // Crear carpeta específica de la fecha dentro del usuario
            $fecha = date("Ymd");
            $carpetaFecha = $carpetaUsuario . '/' . $fecha;
            if (!file_exists($carpetaFecha)) {
                mkdir($carpetaFecha, 0777, true);
            }

            // Verificar si el archivo ya existe en la carpeta de destino
            $filePath = $carpetaFecha . '/' . $name;
            $originalName = $name;
            $counter = 1;

            // Si el archivo ya existe, agregar un sufijo incremental al nombre
            while (file_exists($filePath)) {
                // Cambiar el nombre del archivo agregando un sufijo
                $name = pathinfo($originalName, PATHINFO_FILENAME) . '_' . $counter . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
                $filePath = $carpetaFecha . '/' . $name;
                $counter++;
            }

            // Mover el archivo a la carpeta con el nuevo nombre
            move_uploaded_file($tmp, $filePath);

            // Mensaje de éxito
            $msg = "si";
        } else {
            $msg = "error";
        }

        // Enviar la respuesta en formato JSON
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarEmpleados()
    {
        $data['empleados'] = $this->model->getEmpleados();
        echo json_encode($data['empleados'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarSucursales()
    {
        $data['sucursales'] = $this->model->getSucursales();
        echo json_encode($data['sucursales'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function filtrar()
    {
        // Verifica si los datos de los filtros están presentes y asigna valores predeterminados si es necesario
        $sucursal_filtro = isset($_POST['sucursal_filtro']) ? $_POST['sucursal_filtro'] : null;
        $empleado_filtro = isset($_POST['empleado_filtro']) ? $_POST['empleado_filtro'] : null;
        $desde = isset($_POST['desde']) ? $_POST['desde'] : null;
        $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : null;

        // Verifica que los datos no sean NULL o vacíos
        if (is_null($sucursal_filtro) || is_null($empleado_filtro) || is_null($desde) || is_null($hasta)) {
            // Puedes manejar el caso cuando faltan parámetros
            echo json_encode(['error' => 'Faltan parámetros necesarios']);
            return;
        }

        // Llama a la función para obtener los resultados
        $data['filtro'] = $this->model->filtrarArchivo($sucursal_filtro, $empleado_filtro, $desde, $hasta);
        echo json_encode($data['filtro'], JSON_UNESCAPED_UNICODE);
        die();
    }
}
