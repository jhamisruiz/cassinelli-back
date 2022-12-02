<?php

class RouterClientes
{
    /*=============================================
        LIST CLIENTES
    =============================================*/
    public $listarData;
    public function ListarClientes()
    {
        $data = $this->listarData;
        $response = ControllerClientesList::SELECTALL($data);
    }

    /*=============================================
        POST CLIENTES
    =============================================*/
    public function GuardarClientes()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $response = ControllerClientesList::GUARDAR($data);
    }

    /*=============================================
        PUT CLIENTES
    //=============================================*/
    public function EditarClientes()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $response = ControllerClientesList::UPDATE($data);
    }
    /*=============================================
        EKIMINAR CLIENTES
    =============================================*/

    public function EliminarCiente()
    {
        $response = ControllerClientesList::tempDELETE($_GET['id']);
    }
    /*=============================================
        EXPORTAR CLIENTES
=============================================*/
    public $exportFiles;
    public function ajaxExportFiles()
    {
        $data = $this->exportFiles;
        $response = ControllerClientesList::EXPORTFILE($data);
        echo json_encode($response);
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
        $list = new RouterClientes();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->ListarClientes();
    }

    // $_GET['id'] caputar el id de la peticion
    /* obtener usuario por id */
    // if (isset($_GET['id']) && !empty($_GET['id'])) {
    //     $getbyid = new RouterUsuarios();
    //     $getbyid->idUsuario = $_GET["id"];
    //     $getbyid->GetUsuarioId();
    // }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'CLIENTES-ADD'
    ) {
        $post = new RouterClientes();
        $post->GuardarClientes();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {

    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'CLIENTES-EDIT'
    ) {
        $post = new RouterClientes();
        $post->EditarClientes();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
    $post = new RouterClientes();
    $post->EliminarCiente();
}

//GET
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     // list
//     if (isset($_GET["length"]) && isset($_GET["search"])) {
//         $list = new ajaxClientes();
//         $list->lsClientes = array(
//             "start" => $_GET["start"],
//             "length" => $_GET["length"],
//             "search" => $_GET["search"],
//         );
//         $list->ajaxListClientes();
//     }
//     // list
//     if (isset($_GET["desde"]) && isset($_GET["hasta"])) {
//         $list = new ajaxClientes();
//         $list->exportFiles = array(
//             "desde" => $_GET["desde"],
//             "hasta" => $_GET["hasta"],
//         );
//         $list->ajaxExportFiles();
//     }
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // GUARDA
//     if ($_REQUEST['formulario'] == 'POST') {
//         $post = new ajaxClientes();
//         $post->postCliente = $_REQUEST;
//         $post->ajaxPostClientes();
//     }

//     /// ACTUALZIAR
//     if ($_REQUEST['formulario'] == 'PUT') {
//         $put = new ajaxClientes();
//         $put->putCliente = $_REQUEST;
//         $put->ajaxPutClientes();
//     }

//     /// ACTUALZIAR
//     if ($_REQUEST['formulario'] == 'DELETE') {
//         $del = new ajaxClientes();
//         $del->delCliente = $_REQUEST['cliente'];
//         $del->ajaxDeleteClientes();
//     }
// }
