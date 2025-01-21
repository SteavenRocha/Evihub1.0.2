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

    public function listarRecentEmpleado(int $id)
    {
        $data['recent'] = $this->model->getRecentArchivosEmpleado($id);
        echo json_encode($data['recent'], JSON_UNESCAPED_UNICODE);
        die();
    }

    // Función para manejar la subida del archivo
    public function upload()
    {
        $archivo = $_FILES['file'];
        $tmp = $archivo['tmp_name'];
        $name = $archivo['name'];
        $tipo = $archivo['type'];
        $size = $archivo['size'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_sucursal = $_SESSION['id_sucursal'];

        // Paso 1: Subir el archivo al servidor

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
            $name = pathinfo($originalName, PATHINFO_FILENAME) . '_' . $counter . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
            $filePath = $carpetaFecha . '/' . $name;
            $counter++;
        }

        // Mover el archivo a la carpeta con el nuevo nombre
        if (move_uploaded_file($tmp, $filePath)) {
            // Paso 2: Registrar la ruta en la base de datos

            // Ruta del archivo relativa para la base de datos
            $filePathRelative = str_replace('assets/', '', $filePath);

            // Llamada al modelo para guardar la información en la base de datos
            $data = $this->model->uploadFile($id_usuario, $id_sucursal, $name, $tipo, $size, $filePathRelative);

            if ($data == "ok") {
                $msg = "si"; // Éxito en la subida y registro
            } else {
                $msg = "error"; // Error al guardar la información en la base de datos
            }
        } else {
            $msg = "error"; // Error al mover el archivo
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
        $sucursal_filtro = !empty($_POST['sucursal_filtro']) ? (int)$_POST['sucursal_filtro'] : null;
        $empleado_filtro = !empty($_POST['empleado_filtro']) ? (int)$_POST['empleado_filtro'] : null;
        $desde = !empty($_POST['desde']) ? $_POST['desde'] : null;
        $hasta = !empty($_POST['hasta']) ? $_POST['hasta'] : ($desde ?? null);
        $rol = $_SESSION['id_rol'];
        /*  // Verifica que los datos no sean NULL o vacíos
        if (is_null($sucursal_filtro) || is_null($empleado_filtro) || is_null($desde) || is_null($hasta)) {
            // Puedes manejar el caso cuando faltan parámetros
            echo json_encode(['error' => 'Faltan parámetros necesarios']);
            return;
        }
        */
        // Llama a la función para obtener los resultados
        $data['filtro'] = $this->model->filtrarArchivo($sucursal_filtro, $empleado_filtro, $desde, $hasta, $rol);

        if ($data['filtro'] == "datos vacios") {
            echo json_encode(['error' => 'No se aplicaron filtros'], JSON_UNESCAPED_UNICODE);
            die();
        } else if ($data['filtro'] == "Debe especificar las fechas.") {
            echo json_encode(['error' => 'No se aplicaron fechas'], JSON_UNESCAPED_UNICODE);
            die();
        }
        echo json_encode($data['filtro'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function filtrarEmpleado(int $id)
    {
        /*  $sucursal_filtro = !empty($_POST['sucursal_filtro']) ? (int)$_POST['sucursal_filtro'] : null;
        $empleado_filtro = !empty($_POST['empleado_filtro']) ? (int)$_POST['empleado_filtro'] : null; */
        $desde = !empty($_POST['desde']) ? $_POST['desde'] : null;
        $hasta = !empty($_POST['hasta']) ? $_POST['hasta'] : ($desde ?? null);
        /*  // Verifica que los datos no sean NULL o vacíos
        if (is_null($sucursal_filtro) || is_null($empleado_filtro) || is_null($desde) || is_null($hasta)) {
            // Puedes manejar el caso cuando faltan parámetros
            echo json_encode(['error' => 'Faltan parámetros necesarios']);
            return;
        }
        */
        // Llama a la función para obtener los resultados
        $data['filtro'] = $this->model->filtrarArchivoEmpleado($desde, $hasta, $id);

        if ($data['filtro'] == "datos vacios") {
            echo json_encode(['error' => 'No se aplicaron filtros'], JSON_UNESCAPED_UNICODE);
            die();
        } else if ($data['filtro'] == "Debe especificar las fechas.") {
            echo json_encode(['error' => 'No se aplicaron fechas'], JSON_UNESCAPED_UNICODE);
            die();
        }
        echo json_encode($data['filtro'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function detalles(int $id)
    {
        $data['details'] = $this->model->detallesArchivo($id);
        echo json_encode($data['details'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function zip()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer datos enviados desde el frontend
            $rawData = file_get_contents('php://input');
            $data = json_decode($rawData, true);

            // Validar si los datos fueron correctamente decodificados
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Error al decodificar los datos JSON.']);
                exit;
            }

            // Crear un nombre para el archivo ZIP
            $zipFileName = 'archivos_seleccionados_' . date('Ymd_His') . '.zip';

            // Ruta completa donde se guardará temporalmente el ZIP
            $zipFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName;

            // Crear el archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                http_response_code(500);
                echo json_encode(['error' => 'No se pudo crear el archivo ZIP.']);
                exit;
            }

            // Definir la base de la ruta del sistema
            $baseFilePath = $_SERVER['DOCUMENT_ROOT'] . '/EviHub1.0.2/assets/';

            // Agregar los archivos al ZIP
            foreach ($data as $file) {
                if (isset($file['ruta'])) {
                    // Crear la ruta absoluta del archivo
                    $filePath = $baseFilePath . $file['ruta'];

                    // Verificar si el archivo existe
                    if (file_exists($filePath)) {
                        // Agregar el archivo al ZIP usando el nombre original
                        $zip->addFile($filePath, basename($filePath));
                    } else {
                        // Registrar un error si el archivo no existe
                        error_log("El archivo no existe: " . $filePath);
                    }
                } else {
                    error_log("Ruta no definida en los datos recibidos.");
                }
            }

            // Cerrar el archivo ZIP
            $zip->close();

            // Forzar la descarga del ZIP al cliente
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: ' . filesize($zipFilePath));

            // Leer y enviar el archivo ZIP
            readfile($zipFilePath);

            // Eliminar el archivo temporal
            unlink($zipFilePath);

            exit;
        } else {
            http_response_code(405);
            echo "Método no permitido.";
            exit;
        }
    }

/*     public function zip()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer datos enviados desde el frontend
            $rawData = file_get_contents('php://input');
            $data = json_decode($rawData, true);

            // Validar si los datos fueron correctamente decodificados
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Error al decodificar los datos JSON.']);
                exit;
            }

            // Crear un nombre para el archivo ZIP
            $zipFileName = 'archivos_seleccionados_' . date('Ymd_His') . '.zip';

            // Ruta completa donde se guardará temporalmente el ZIP
            $zipFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName;

            // Crear el archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                http_response_code(500);
                echo json_encode(['error' => 'No se pudo crear el archivo ZIP.']);
                exit;
            }

            // Definir la base de la ruta del sistema
            $baseFilePath = $_SERVER['DOCUMENT_ROOT'] . '/EviHub1.0.2/assets/';

            // Agregar los archivos al ZIP, manteniendo la estructura de carpetas
            foreach ($data as $file) {
                if (isset($file['ruta'])) {
                    // Crear la ruta absoluta del archivo
                    $filePath = $baseFilePath . $file['ruta'];

                    // Verificar si el archivo existe
                    if (file_exists($filePath)) {
                        // Obtener la ruta relativa para el archivo dentro del ZIP
                        // Esto mantiene las carpetas originales dentro del ZIP
                        $relativePath = str_replace($baseFilePath, '', $filePath);

                        // Agregar el archivo al ZIP usando la ruta relativa
                        $zip->addFile($filePath, $relativePath);
                    } else {
                        // Registrar un error si el archivo no existe
                        error_log("El archivo no existe: " . $filePath);
                    }
                } else {
                    error_log("Ruta no definida en los datos recibidos.");
                }
            }

            // Cerrar el archivo ZIP
            $zip->close();

            // Forzar la descarga del ZIP al cliente
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: ' . filesize($zipFilePath));

            // Leer y enviar el archivo ZIP
            readfile($zipFilePath);

            // Eliminar el archivo temporal
            unlink($zipFilePath);

            exit;
        } else {
            http_response_code(405);
            echo "Método no permitido.";
            exit;
        }
    } */
}
