<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
IMPORT::CONTROLERS('controllers', 'auth');


class RouterAuth
{
    ///////////////LOGIN/////////
    public $adminlogin;
    public function AdminLogin()
    {
        $data = $this->adminlogin;
        $response = AuthController::LOGIN($data);
        exit;
    }
}

//*********************ROUTERS********
///****************VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    $request = $_REQUEST['REQUEST_ARRAY_DATA'];
    if (isset($request['user_email'])) {
        $login = new RouterAuth();
        $login->adminlogin = $request;
        $login->AdminLogin();
    }
}
