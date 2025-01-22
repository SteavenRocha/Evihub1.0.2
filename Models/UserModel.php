<?php
class UserModel extends Query
{

    private $id_empleado, $id_rol, $usuario, $contraseña, $id, $estado;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios()
    {

        $sql = "SELECT 
                    u.id_usuario,
                    CONCAT(e.nombres, ' ', e.ape_paterno, ' ', e.ape_materno) AS nombre_completo,
                    u.id_rol,
                    r.nombre_rol,
                    u.usuario,
                    u.estado_usuario,
                    u.fecha_registro
                FROM 
                    usuario u
                INNER JOIN 
                    empleado e ON u.id_empleado = e.id_empleado
                INNER JOIN 
                    rol r ON u.id_rol = r.id_rol
                ORDER BY 
                    u.id_rol ASC, u.id_usuario DESC;
                ";
        $data['users'] = $this->selectAll($sql);
        return $data['users'];
    }

    public function getEmpleados()
    { //consulta que selecciona a mis empelados que no estan registrados como usuarios
        $sql = "SELECT 
                    e.id_empleado, 
                    e.nombres, 
                    e.ape_paterno, 
                    e.ape_materno
                FROM 
                    EMPLEADO e 
                LEFT JOIN 
                    usuario u 
                ON 
                    e.id_empleado = u.id_empleado
                WHERE 
                    u.id_empleado IS NULL
                AND 
                    e.estado_empleado = 1";
        $data['employees'] = $this->selectAll($sql);
        return $data['employees'];
    }

    public function getRoles()
    {
        $sql = "SELECT * FROM ROL WHERE estado_rol = 1";
        $data['roles'] = $this->selectAll($sql);
        return $data['roles'];
    }

    public function registrarUsuario(string $id_empleado, string $id_rol, string $usuario, string $contraseña)
    {

        $this->id_empleado = $id_empleado;
        $this->id_rol = $id_rol;
        $this->usuario = $usuario;
        $this->contraseña = $contraseña;

        $verificar = "SELECT * FROM USUARIO WHERE usuario = '$this->usuario'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "INSERT INTO USUARIO(id_empleado, id_rol, usuario, contraseña) VALUES (?,?,?,?)";
            $datos['user'] = array($this->id_empleado, $this->id_rol, $this->usuario, $this->contraseña);
            $data = $this->save($sql, $datos['user']);

            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    public function editarUser(int $id)
    {
        $sql = "SELECT 
                    e.id_empleado, 
                    e.nombres, 
                    e.ape_paterno, 
                    e.ape_materno,
                    u.id_rol,
                    u.id_usuario,
                    u.usuario
                FROM 
                    EMPLEADO e 
                LEFT JOIN 
                    usuario u 
                ON 
                    e.id_empleado = u.id_empleado
                WHERE 
                    u.id_usuario = $id";
        $data['edit'] = $this->select($sql);
        return $data['edit'];
    }

    public function modificarUsuario(string $id_rol, string $usuario, int $id)
    {

        $this->id_rol = $id_rol;
        $this->usuario = $usuario;
        $this->id = $id;

        $verificar = "SELECT * FROM USUARIO WHERE usuario = '$this->usuario' AND id_usuario != $this->id";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "UPDATE USUARIO SET id_rol = ?, usuario = ? WHERE id_usuario = ?";
            $datos['user'] = array($this->id_rol, $this->usuario, $this->id);
            $data = $this->save($sql, $datos['user']);

            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }

        return $res;
    }

    public function accionUser(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;

        $sql = "UPDATE USUARIO SET estado_usuario = ? WHERE id_usuario = ?";
        $datos['deshabilitar'] = array($this->estado, $this->id);
        $data = $this->save($sql, $datos['deshabilitar']);
        return $data;
    }
}
