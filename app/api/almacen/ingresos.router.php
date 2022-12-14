<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
IMPORT::CONTROLERS('controllers', 'almacen');

class RouterIngresos
{
    /*=============================================
        LIST
    =============================================*/
    public $listarData;
    public function ListarClientes()
    {
        $data = $this->listarData;
        $response = IngresosController::SELECTALL($data);
    }

    public function ORDENCOMPRAID()
    {
        $id = $_GET['idordencompra'];
        $response = ControllerOrden::BUSCARBYID($id);
    }
}



//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (
    strtoupper($_SERVER['REQUEST_METHOD']) === 'GET'
) {
    if (isset($_GET["idordencompra"])) {
        $or = new RouterIngresos();
        $or->ORDENCOMPRAID();
    }

    // listar todos los usuarios
    if (
        isset($_GET["start"]) && isset($_GET["length"]) &&
        isset($_GET["search"]) &&  isset($_GET["order"])
    ) {
        $list = new RouterIngresos();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->ListarClientes();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    // code...
}
