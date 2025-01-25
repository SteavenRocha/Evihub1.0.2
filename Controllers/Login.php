<?php
class Login extends Controller
{
    public function __construct()
    {
        session_start();
        if (isset($_SESSION['activo']) && $_SESSION['activo'] === true) {
            header("location: " . BASE_URL . "Evidence");
        }
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'INICIAR SESIÓN';
        $this->views->getView('Pages', "login", $data);
    }

    public function validar()
    {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $msg = "Los campos están vacíos";
        } else {
            $usuario = htmlspecialchars($_POST['usuario']); 
            $clave = htmlspecialchars($_POST['clave']); 
            $hash = hash("SHA256", $clave);

            $data['login'] = $this->model->getUsuario($usuario, $hash);

            if ($data['login']) {
                if ($data['login']['estado_usuario'] == 0) {
                    $msg = "Usuario inactivo";
                } else {
                    $_SESSION['id_usuario'] = $data['login']['id_usuario'];
                    $_SESSION['usuario'] = $data['login']['usuario'];
                    $_SESSION['id_empleado'] = $data['login']['id_empleado'];
                    $_SESSION['nombre_completo'] = $data['login']['nombre_completo'];
                    $_SESSION['id_sucursal'] = $data['login']['id_sucursal'];
                    $_SESSION['nombre_sucursal'] = $data['login']['nombre_sucursal'];
                    $_SESSION['id_rol'] = $data['login']['id_rol'];
                    $_SESSION['nombre_rol'] = $data['login']['nombre_rol'];
                    $_SESSION['estado_usuario'] = $data['login']['estado_usuario'];
                    $_SESSION['activo'] = true;

                    $msg = "ok";
                }
            } else {
                $msg = "Usuario o contraseña incorrecta";
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir()
    {
        session_destroy();
        header("location: " . BASE_URL);
    }
}
