<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
IMPORT::CONTROLERS('controllers', 'cupones');

class RouterCupones
{

    /*=============================================
        LIST CUPONES
    =============================================*/
    public $listarData;
    public function ListarCupones()
    {
        $data = $this->listarData;
        $response = CuponesController::LISTAR($data);
    }

    /*=============================================
        GUARDAR CUPONES
    =============================================*/
    public function guardarCupones()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $response = CuponesController::GUARDAR($data);
    }

    public function editarCupones()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $response = CuponesController::ACTUALZIAR($data);
    }

    /*=============================================
        ELIMINAR CUPONES
    =============================================*/
    public function EliminarCupones()
    {
        $delete = array(
            "table" => "cupones", "id" => $_GET['id']
        );
        $response = ControllerQueryes::DELETE($delete);

        if (!$response) {
            REQUEST::RESPONDER(1, 200);
        } else {
            Errors::__Log('No se elimino el Usuario.', 202);
        }
    }


    /*=============================================
        ACTIVAR DESACTIVAR  USUARIOSS
    =============================================*/
    public function Habilitar()
    {
        $data = $_GET['habilitar'];
        $update = array(
            "table" => "cupones", #nombre de tabla
            "estado" => $data, #nombre de columna y valor
            #"columna"=>"valor",#nombre de columna y valor
        );
        $where = array(
            "id" => $_GET['id'], #condifion columna y valor
        );

        $response = ControllerQueryes::UPDATE($update, $where);
        if ($response) {
            REQUEST::RESPONDER(($data) ? 'Habilitado' : 'Deshabilitado', 200);
        } else {
            Errors::__Log('No se actualizo el estado.', 202);
        }
    }
}

//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    // listar todos los usuarios
    if (
        isset($_GET["start"]) && isset($_GET["length"]) &&
        isset($_GET["search"]) &&  isset($_GET["order"])
    ) {
        $list = new RouterCupones();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->ListarCupones();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid']== 'CUPONES-ADD'
    ) {
        $post = new RouterCupones();
        $post->guardarCupones();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {

    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid']== 'CUPONES-EDIT'
    ) {
        $post = new RouterCupones();
        $post->editarCupones();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
    $post = new RouterCupones();
    $post->EliminarCupones();
}

/* habilitar y deshabilitar usuarios */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PATCH') {
    if (isset($_GET['habilitar'])) {
        $habilitar = new RouterCupones();
        $habilitar->Habilitar();
    }
}
