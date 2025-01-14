<?php
class Views
{

    public function getView($ruta, $vista, $data = "")
    {
        if ($ruta == "login") {
            $vista = "Views/" . $vista . ".php";
        } else {
            $vista = "Views/" . $ruta . "/" . $vista . ".php";
        }
        require $vista;
    }
}
?>