<?php
class Errors extends Controller
{
    public function __construct()
    {
        session_start();
       /*  if (isset($_SESSION['activo']) && $_SESSION['activo'] === true) {
            header("location: " . BASE_URL . "Evidence");
        } */
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'PAGINA NO ENCONTRADA';
        $this->views->getView('Pages', "errors", $data);
    }
}
