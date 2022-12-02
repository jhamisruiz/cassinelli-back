<?php
// |MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
IMPORT::CONTROLERS('controllers', 'ubigeo');

// code...
class RouterUbigeo
{
    /*=============================================
        UBIGEO
    =============================================*/
    public $ubigeo;
    public function Ubigeo()
    {
        $data = $this->ubigeo;
        $response = ControllerUbigeo::UBIGEO($data);
        exit;
    }
}

//*********************ROUTERS********
///****************VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    
    // lista departamento
    if (isset($_GET["departamento"])) {
        $list = new RouterUbigeo();
        $list->ubigeo = ["Departamento" => $_GET["departamento"]];
        $list->Ubigeo();
    }

    // lista provincia
    if (isset($_GET["provincia"])) {
        $list = new RouterUbigeo();
        $list->ubigeo = ["Provincia" => $_GET["provincia"]];
        $list->Ubigeo();
    }

    // lista distrito
    if (isset($_GET["distrito"])) {
        $list = new RouterUbigeo();
        $list->ubigeo = ["Distrito" => $_GET["distrito"]];
        $list->Ubigeo();
    }
}
