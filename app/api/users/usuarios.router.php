<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//--------IMPORT CONTROLLER----------
IMPORT::CONTROLERS('controllers', 'users');

class RouterUsuarios
{
    /*=============================================
        LIST USUARIOS
    =============================================*/
    public $listarData;
    public function ListarUsuarios()
    {
        $data = $this->listarData;
        $response = ControllerUsuarios::SELECTALL($data);
    }
    /*=============================================
        POST USUARIOS
    =============================================*/
    public $crearUsuario;
    public function CrearUsuarios()
    {
        $data = $this->crearUsuario;

        if (
            isset($data["names"]) &&
            isset($data["last_name"]) &&
            isset($data["user_name"]) &&
            isset($data["email"]) &&
            isset($data["password"]) &&
            isset($data["rep_password"])
        ) {
            $response = ControllerUsuarios::CREAR($data);
            exit;
        }
        //Response::HttpResponse(202, false, 'Faltan Datos');
        Errors::__Log('Faltan datos.', 202);
    }
    /*=============================================
        GET BY ID Usuario
    =============================================*/
    public $idUsuario;
    public function GetUsuarioId()
    {
        $data = $this->idUsuario;
        $response = ControllerUsuarios::GETUSUARIOID($data);
    }
    /*=============================================
        PUT USUARIOSS
    =============================================*/
    public $actualizarUsuario;
    public function ActualizarUsuarios()
    {
        $data = $this->actualizarUsuario;
        $response = ControllerUsuarios::ACTUALIZAR($data, $_GET['id']);
    }
    /*=============================================
        ACTIVAR DESACTIVAR  USUARIOSS
    =============================================*/
    public function Habilitar()
    {
        $id = $_GET['id'];
        $data = $_GET['habilitar'];
        $response = ControllerUsuarios::HABILITARDESHABILITAR($id, $data);
    }
    /*=============================================
        RESSET PASSWORD USUARIOSS
    =============================================*/
    public function ResetPasswords()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        if (
            isset($data["password"]) &&
            isset($data["rep_password"])
        ) {
            $response = ControllerUsuarios::RESETPASSWORD($data, $_GET['id']);
        }
        Errors::__Log('Faltan datos.', 202);
    }
    /*=============================================
        DELETE USUARIOSS
    =============================================*/
    public function EliminarUsuarios()
    {
        $data = $_GET['iduser'];
        $response = ControllerUsuarios::ELIMINAR($data);
    }

    /*=============================================
        POST PERMISOS//TODO:implementar
    =============================================*/
    public $permisos;
    public function PermisosUsuarios()
    {
        ////FIXME:falta implementar
        $data = $this->permisos;
        $response = ControllerUsuarios::PERMISOS($data);
    }
    /* ===============data print=================== */
    public $printFile;
    public function ExportarUsuarios()
    {
        $data = $this->printFile;
        $response = ControllerUsuarios::FILES($data);
    }
}

//*********************ROUTERS********
// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///****************VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {

    // listar todos los usuarios
    if (
        isset($_GET["start"]) && isset($_GET["length"]) &&
        isset($_GET["search"]) &&  isset($_GET["order"])
    ) {
        $list = new RouterUsuarios();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->ListarUsuarios();
    }

    // $_GET['id'] caputar el id de la peticion
    /* obtener usuario por id */
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $getbyid = new RouterUsuarios();
        $getbyid->idUsuario = $_GET["id"];
        $getbyid->GetUsuarioId();
    }
}

/* guardar usuario */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid'])
        && $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'USUARIOS-ADD'
    ) {
        $crear = new RouterUsuarios();
        $crear->crearUsuario = $_REQUEST['REQUEST_ARRAY_DATA'];
        $crear->CrearUsuarios();
    }
}
/* actualizar usuario por id */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {
    if (isset($_REQUEST['REQUEST_OBJECT_DATA']->{'email'}) && isset($_GET['id'])) {
        $actualizar = new RouterUsuarios();
        $actualizar->actualizarUsuario = $_REQUEST['REQUEST_OBJECT_DATA'];
        $actualizar->ActualizarUsuarios();
    }

    if (isset($_GET['id']) && isset($_REQUEST['REQUEST_ARRAY_DATA']['password'])) {
        $actualizar = new RouterUsuarios();
        $actualizar->ResetPasswords();
    }
}

/* habilitar y deshabilitar usuarios */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PATCH') {
    if (isset($_GET['habilitar'])) {
        $habilitar = new RouterUsuarios();
        $habilitar->Habilitar();
    }
}
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
    if ($_GET['iduser']) {
        $del = new RouterUsuarios();
        $del->EliminarUsuarios();
    }
}
