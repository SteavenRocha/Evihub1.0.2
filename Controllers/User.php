<?php
class User extends Controller
{
    public function __construct() {
        session_start();
        if (!isset($_SESSION['activo']) || $_SESSION['activo'] !== true) {
            header("location: " . BASE_URL);
            die();
        }
        parent::__construct();
    }

    public function index() {
        $data['title'] = 'USUARIOS';
        $this->views->getView('Pages', "users", ($data));
    }

    public function listar() {
        $data['users'] = $this->model->getUsuarios();
        echo json_encode($data['users'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function empleados() {
        $data['employees'] = $this->model->getEmpleados();
        echo json_encode($data['employees'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function roles() {
        $data['roles'] = $this->model->getRoles();
        echo json_encode($data['roles'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar() {
        $id_empleado = $_POST['id_empleado'];
        $id_rol = $_POST['id_rol'];
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        $confirmar = $_POST['confirmar'];
        $hash = hash("SHA256", $contraseña);
        
        if (empty($id_empleado) || empty($id_rol) || empty($usuario) || empty($contraseña) || empty($confirmar)) {
            $msg = "Todos los campos son obligatorios";

        } else if ($contraseña != $confirmar) {
            $msg = "Las contraseñas no coinciden";

        } else {
            $data['user'] = $this->model->registrarUsuario($id_empleado, $id_rol, $usuario, $hash);
            if($data['user'] == "ok") {
                $msg = "si";
            } else if($data['user'] == "existe") {
                $msg = "El usuario ya existe";
            } else {
                $msg = "Error al registrar el usuario";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function modificar() {
        $id = $_POST['id'];
        $id_rol = $_POST['id_rol'];
        $usuario = $_POST['usuario'];
        
        if (empty($id_rol) || empty($usuario)) {
            $msg = "Todos los campos son obligatorios";

        } else {
            $data['user'] = $this->model->modificarUsuario($id_rol, $usuario, $id);
            if($data['user'] == "modificado") {
                $msg = "modificado";
            } /* else if($data['user'] == "existe") {
                $msg = "El usuario ya existe";
            } */ else {
                $msg = "Error al registrar el usuario";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id) {
        $data['edit'] = $this->model->editarUser($id);
        echo json_encode($data['edit'], JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deshabilitar(int $id) {

        $data['deshabilitar'] = $this->model->accionUser(0, $id);
        if($data['deshabilitar'] == 1) {
            $msg = "ok";
        }else {
            $msg = "Error al deshabilitar usuario";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function habilitar(int $id) {

        $data['deshabilitar'] = $this->model->accionUser(1, $id);
        if($data['deshabilitar'] == 1) {
            $msg = "ok";
        }else {
            $msg = "Error al deshabilitar usuario";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>