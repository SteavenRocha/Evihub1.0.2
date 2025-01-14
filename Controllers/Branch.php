<?php
class Branch extends Controller
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
        $data['title'] = 'SUCURSALES';
        $this->views->getView('Pages', "branchs",($data));
    }
}
?>