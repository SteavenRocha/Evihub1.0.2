<?php
class LoginModel extends Query{
 
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuario(string $usuario, string $clave) {
        $sql = "SELECT 
                U.id_usuario,
                U.usuario,
                E.id_empleado,
                CONCAT(E.nombres, ' ', E.ape_paterno, ' ', E.ape_materno) AS nombre_completo,
                S.id_sucursal,
                S.nombre_sucursal,
                R.id_rol,
                R.nombre_rol,
                U.estado_usuario
                FROM 
                    USUARIO U
                INNER JOIN 
                    EMPLEADO E ON U.id_empleado = E.id_empleado
                INNER JOIN 
                    SUCURSAL S ON E.id_sucursal = S.id_sucursal
                INNER JOIN 
                    ROL R ON U.id_rol = R.id_rol
                WHERE U.usuario = '$usuario' 
                AND U.contraseña = '$clave'";
        $data = $this->select($sql);
        return $data;
    }
    
}
 
?>