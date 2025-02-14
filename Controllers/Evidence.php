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
        $data = $this->model->getRecentArchivos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
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
        $nombre_completo = $_SESSION['nombre_completo'];

        $id_sucursal = $_SESSION['id_sucursal'];
        $nombre_sucursal = $_SESSION['nombre_sucursal'];

        $mesActual = date("m-Y");
        $diaDelMes = date("d-m");

        $destino = 'assets/archivos/archivos_subidos/' . $mesActual . '/' . $nombre_sucursal . '/' . $nombre_completo . '/' . $diaDelMes;

        if (!file_exists($destino)) {
            mkdir($destino, 0777, true);
        }

        $filePath = $destino . '/' . $name;
        $originalName = $name;
        $counter = 1;

        while (file_exists($filePath)) {
            $name = pathinfo($originalName, PATHINFO_FILENAME) . '_' . $counter . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
            $filePath = $destino . '/' . $name;
            $counter++;
        }

        if (move_uploaded_file($tmp, $filePath)) {
            $filePathRelative = str_replace('assets/', '', $filePath);

            $data = $this->model->uploadFile($id_usuario, $id_sucursal, $name, $tipo, $size, $filePathRelative);

            $msg = ($data == "ok") ? "si" : "error";
        } else {
            $msg = "error";
        }

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
        // Si solo se ha enviado la fecha "desde", se asigna la misma fecha a "hasta" con hora 23:59:59
        if (empty($_POST['hasta']) && !empty($_POST['desde'])) {
            // Se asegura de que el formato de "hasta" esté al final del día
            $hasta = date('Y-m-d 23:59:59', strtotime($desde));
        }

        $rol = $_SESSION['id_rol'];

        // Verifica si las fechas están vacías antes de intentar formatearlas
        if (!empty($desde)) {
            $fecha_desde = new DateTime($desde);
            $fechaFormateadaDesde = $fecha_desde->format('Y-m-d H:i:s');
        } else {
            $fechaFormateadaDesde = null;
        }

        if (!empty($hasta)) {
            $fecha_hasta = new DateTime($hasta);
            $fechaFormateadaHasta = $fecha_hasta->format('Y-m-d H:i:s');
        } else {
            $fechaFormateadaHasta = null;
        }

        // Llama a la función para obtener los resultados
        $data['filtro'] = $this->model->filtrarArchivo($sucursal_filtro, $empleado_filtro, $fechaFormateadaDesde, $fechaFormateadaHasta, $rol);

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
        if (empty($_POST['hasta']) && !empty($_POST['desde'])) {
            // Se asegura de que el formato de "hasta" esté al final del día
            $hasta = date('Y-m-d 23:59:59', strtotime($desde));
        }

        // Verifica si las fechas están vacías antes de intentar formatearlas
        if (!empty($desde)) {
            $fecha_desde = new DateTime($desde);
            $fechaFormateadaDesde = $fecha_desde->format('Y-m-d H:i:s');
        } else {
            $fechaFormateadaDesde = null;
        }

        if (!empty($hasta)) {
            $fecha_hasta = new DateTime($hasta);
            $fechaFormateadaHasta = $fecha_hasta->format('Y-m-d H:i:s');
        } else {
            $fechaFormateadaHasta = null;
        }
        /*  // Verifica que los datos no sean NULL o vacíos
        if (is_null($sucursal_filtro) || is_null($empleado_filtro) || is_null($desde) || is_null($hasta)) {
            // Puedes manejar el caso cuando faltan parámetros
            echo json_encode(['error' => 'Faltan parámetros necesarios']);
            return;
        }
        */
        // Llama a la función para obtener los resultados
        $data['filtro'] = $this->model->filtrarArchivoEmpleado($fechaFormateadaDesde, $fechaFormateadaHasta, $id);

        if ($data['filtro'] == "datos vacios") {
            echo json_encode(['error' => 'No se aplicaron filtros'], JSON_UNESCAPED_UNICODE);
            die();
        }
        echo json_encode($data['filtro'], JSON_UNESCAPED_UNICODE);
        /* print_r($data); */
        die();
    }

    public function detalles(int $id)
    {
        $data['details'] = $this->model->detallesArchivo($id);
        echo json_encode($data['details'], JSON_UNESCAPED_UNICODE);
        die();
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

            // Mantener un registro de nombres únicos
            $addedFiles = [];

            // Agregar los archivos al ZIP
            foreach ($data as $file) {
                if (isset($file['ruta'])) {
                    // Crear la ruta absoluta del archivo
                    $filePath = $baseFilePath . $file['ruta'];

                    // Verificar si el archivo existe
                    if (file_exists($filePath)) {
                        // Obtener el nombre base del archivo
                        $baseName = basename($filePath);

                        // Si el archivo ya existe en el ZIP, agregar un sufijo único
                        $uniqueName = $baseName;
                        $counter = 1;
                        while (in_array($uniqueName, $addedFiles)) {
                            $uniqueName = pathinfo($baseName, PATHINFO_FILENAME) . "($counter)." . pathinfo($baseName, PATHINFO_EXTENSION);
                            $counter++;
                        }

                        // Registrar el archivo con el nombre único
                        $addedFiles[] = $uniqueName;

                        // Agregar el archivo al ZIP con el nombre único
                        $zip->addFile($filePath, $uniqueName);
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
            $baseFilePath = $_SERVER['DOCUMENT_ROOT'] . '/Evihub/assets/';

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
    }

    public function eliminar(int $id)
    {
        $ruta = $_GET['ruta'];
        /*  $id_usuario = $_SESSION['id_usuario']; */
        $nombre_completo = $_SESSION['nombre_completo'];

        /* $id_sucursal = $_SESSION['id_sucursal']; */
        $nombre_sucursal = $_SESSION['nombre_sucursal'];

        $mesActual = date("m-Y");
        $diaDelMes = date("d-m");

        $carpetaEliminados = 'assets/archivos/archivos_eliminados/' . $mesActual . '/' . $nombre_sucursal . '/' . $nombre_completo . '/' . $diaDelMes;

        if (!file_exists($carpetaEliminados)) {
            mkdir($carpetaEliminados, 0777, true);
        }

        $carpetaOriginal = 'assets/' . $ruta;

        if (file_exists($carpetaOriginal)) {
            $nuevoNombre = $carpetaEliminados . '/' . basename($carpetaOriginal);
            $originalName = basename($carpetaOriginal);
            $counter = 1;

            while (file_exists($nuevoNombre)) {
                $nuevoNombre = $carpetaEliminados . '/' . pathinfo($originalName, PATHINFO_FILENAME) . '_' . $counter . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
                $counter++;
            }

            rename($carpetaOriginal, $nuevoNombre);

            $data['eliminar'] = $this->model->eliminarArchivo(0, $id);
            if ($data['eliminar'] == 1) {
                $msg = "ok";
            } else {
                $msg = "Error al eliminar archivo";
            }
        } else {
            $msg = "error";
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
