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

    public function listarRecent() {
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
        $data = $this->model->uploadFile($id_usuario, $id_sucursal, $name, $tipo, $size);

        if ($data == "ok") {
            $destino = 'assets/archivos';
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            $fecha = date("Ymd");
            $carpeta = $destino . '/' . $id_usuario . '/' . $fecha;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            move_uploaded_file($tmp, $carpeta . '/' . $name);
            $msg = "si";
        } else {
            $msg = "error";
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
