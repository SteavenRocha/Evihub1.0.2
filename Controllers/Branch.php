<?php
class Branch extends Controller
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
        $data['title'] = 'SUCURSAL';
        $this->views->getView('Pages', "branchs", ($data));
    }

    public function listar()
    {
        $data['sucursal'] = $this->model->getSucursales();
        echo json_encode($data['sucursal'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        $nombre_sucursal = $_POST['nombre_sucursal'];

        if (empty($nombre_sucursal)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            $data['sucursal'] = $this->model->registrarSucursal($nombre_sucursal);
            if ($data['sucursal'] == "ok") {
                $msg = "si";
            } else if ($data['sucursal'] == "existe") {
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
        $data['edit'] = $this->model->editarSucursal($id);
        echo json_encode($data['edit'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function modificar()
    {
        $id_sucursal = $_POST['id_sucursal'];
        $nombre_sucursal = $_POST['nombre_sucursal'];

        if (empty($nombre_sucursal)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            $data['sucursal'] = $this->model->modificarSucursal( $nombre_sucursal, $id_sucursal);
            if ($data['sucursal'] == "modificado") {
                $msg = "modificado";
            } else if ($data['sucursal'] == "existe") {
                $msg = "La sucursal ya existe";
            } else {
                $msg = "Error al modificar la sucursal";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deshabilitar(int $id)
    {

        $data['deshabilitar'] = $this->model->accionSucursal(0, $id);
        if ($data['deshabilitar'] == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al deshabilitar sucursal";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function habilitar(int $id)
    {

        $data['deshabilitar'] = $this->model->accionSucursal(1, $id);
        if ($data['deshabilitar'] == 1) {
            $msg = "ok";
        } else {
            $msg = "Error al deshabilitar sucursal";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    } 
}
