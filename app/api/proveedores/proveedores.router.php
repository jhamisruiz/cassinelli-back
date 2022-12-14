<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//--------IMPORT CONTROLLER----------
IMPORT::CONTROLERS('controllers', 'proveedores');

class RouterProveedores
{
    public function suggestInsumoProveedor()
    {
        $data = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $table = 'insumos_proveedor'; #nombre de la tabla o vista

        $columns = "id, id_proveedor, nombre, codigo, descripcion, id_unidad_medida, precio, estado"; #culumnas de la tabla para mostrar

        $params = ['id', 'nombre', 'codigo', 'descripcion']; #columnas por las que se realizara la busqueda

        $response = ModelQueryes::SELECT_NV($table, $columns, $params, $data, 'id_proveedor=' . $_GET["idproveedor"]); #funcion para traer la data
        if ($response) {
            for ($i = 0; $i < count($response); $i++) {
                $um = ControllerQueryes::SELECT(
                    ['*' => '*'],
                    ["unidad_medida" => ""],
                    ["id" => '=' . $response[$i]['id_unidad_medida']]
                );
                if (isset($um[0])) {
                    $response[$i]['unidadmedida'] = $um[0]['nombre'] . '-' . $um[0]['descripcion'];
                }
                $response[$i]['precio'] = (float)$response[$i]['precio'];
            }
        }
        REQUEST::RESPONDER($response, 200);
    }
    /*=============================================
        LIST PROVEEDORES
    =============================================*/
    public $listarData;
    public function ListarProveedores()
    {
        $data = $this->listarData;
        $response = ControllerProveedores::SELECTALL($data);
    }
    /*=============================================
        POST PROVEEDORES
    =============================================*/
    public function CREAR()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];

        if (
            isset($data["nombre"]) &&
            isset($data["numero_documento"]) &&
            isset($data["email"])
        ) {
            $response = ControllerProveedores::CREAR($data);
            exit;
        }
        //Response::HttpResponse(202, false, 'Faltan Datos');
        Errors::__Log('Faltan datos.', 202);
    }
    /*=============================================
        GET BY ID Usuario
    =============================================*/
    public $idUsuario;
    public function GetProveeId()
    {
        $data = $this->idUsuario;
        $response = ControllerProveedores::GETPROVEEID($data);
    }
    /*=============================================
        PUT PROVEEDORES
    =============================================*/
    public function ACTUALIZAR()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $response = ControllerProveedores::ACTUALIZAR($data, $_GET['id']);
    }
    /*=============================================
        ACTIVAR DESACTIVAR  PROVEEDORES
    =============================================*/
    public function Habilitar()
    {
        $id = $_GET['id'];
        $data = $_GET['habilitar'];
        $response = ControllerProveedores::HABILITARDESHABILITAR($id, $data);
    }

    /*=============================================
        DELETE PROVEEDORES
    =============================================*/
    public function ELIMINAR()
    {
        $data = $_GET['idprov'];
        $response = ControllerProveedores::ELIMINAR($data);
    }
}

//*********************ROUTERS********
// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///****************VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {

    // listar insumos por proveedor
    if (
        isset($_GET["idproveedor"])
    ) {
        $list = new RouterProveedores();
        $list->suggestInsumoProveedor();
    }

    // listar todos los PROVEEDORES
    if (
        isset($_GET["start"]) && isset($_GET["length"]) &&
        isset($_GET["search"]) &&  isset($_GET["order"])
    ) {
        $list = new RouterProveedores();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->ListarProveedores();
    }

    // $_GET['id'] caputar el id de la peticion
    /* obtener proveedor por id */
    if (isset($_GET['idprov']) && !empty($_GET['idprov'])) {
        $getbyid = new RouterProveedores();
        $getbyid->idUsuario = $_GET["idprov"];
        $getbyid->GetProveeId();
    }
}

/* guardar PROVEEDOR */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid'])
        && $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'PROVEEDORES-ADD'
    ) {
        $crear = new RouterProveedores();
        $crear->CREAR();
    }
}
/* actualizar PROVEEDOR por id */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {
    if (isset($_REQUEST['REQUEST_OBJECT_DATA']->{'email'}) && isset($_GET['id'])) {
        $actualizar = new RouterProveedores();
        $actualizar->ACTUALIZAR();
    }
}

/* habilitar y deshabilitar PROVEEDOR */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PATCH') {
    if (isset($_GET['habilitar'])) {
        $habilitar = new RouterProveedores();
        $habilitar->Habilitar();
    }
}
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
    if ($_GET['idprov']) {
        $del = new RouterProveedores();
        $del->ELIMINAR();
    }
}
