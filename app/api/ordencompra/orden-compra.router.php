<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
IMPORT::CONTROLERS('controllers', 'ordencompra');

class RouterOrdenCompra
{
    public function CODIGO()
    {
        $code = getCodigoPedido(COD_ORDEN, 'ordenes_compra');
        REQUEST::RESPONDER($code, 200);
    }

    /*=============================================
        LIST OrdenCompra
    =============================================*/
    public $listarData;
    public function ORDENCOMPRA_LIST()
    {
        $data = $this->listarData;
        $response = ControllerOrden::SELECTALL($data);
    }

    public function BUSCARBYID()
    {
        $id = $_GET['idordencompras'];
        $response = ControllerOrden::BUSCARBYID($id);
    }

    public function CREAR()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];

        $response = ControllerOrden::CREAR($data);
    }

    public function EDITAR()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];

        $response = ControllerOrden::EDITAR($data);
    }

    public function DELETE()
    {
        $id = $_GET['idorden'];
        $response = ControllerOrden::DELETE($id);
    }
}



//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    // code...
    if (isset($_GET["codigo"])) {
        $or = new RouterOrdenCompra();
        $or->CODIGO();
    }

    // listar todos
    if (
        isset($_GET["start"]) && isset($_GET["length"]) &&
        isset($_GET["search"]) &&  isset($_GET["order"])
    ) {
        $list = new RouterOrdenCompra();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->ORDENCOMPRA_LIST();
    }

    // busca por id
    if (isset($_GET["idordencompras"])) {
        $or = new RouterOrdenCompra();
        $or->BUSCARBYID();
    }
}

/* guardar ORDEN COMPRAS */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid'])
        && $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'ORDENCOMPRAS-ADD'
    ) {
        $crear = new RouterOrdenCompra();
        $crear->CREAR();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT') {
    //echo 'llego';
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'ORDENCOMPRAS-EDIT'
    ) {
        $put = new RouterOrdenCompra();
        $put->EDITAR();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
    if ($_GET['idorden']) {
        $del = new RouterOrdenCompra();
        $del->DELETE();
    }
}
