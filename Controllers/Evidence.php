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

            // Crear carpeta si no existe
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }

            $fecha = date("Ymd");
            $carpeta = $destino . '/' . $id_usuario . '/' . $fecha;

            // Crear la carpeta específica del usuario y la fecha si no existe
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            // Verificar si el archivo ya existe en la carpeta de destino
            $filePath = $carpeta . '/' . $name;
            $originalName = $name;
            $counter = 1;

            // Si el archivo ya existe, agregar un sufijo incremental al nombre
            while (file_exists($filePath)) {
                // Cambiar el nombre del archivo agregando un sufijo
                $name = pathinfo($originalName, PATHINFO_FILENAME) . '_' . $counter . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
                $filePath = $carpeta . '/' . $name;
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
}
?>