<?php

// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
IMPORT::CONTROLERS('controllers', 'pedidos');

class RouterPedidos
{
    //static functions...
    public $listarData;
    public function Listar()
    {
        $data = $this->listarData;
        $response = ControllerPedidoList::SELECTALL($data);
    }

    public function Crear()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $response = ControllerPedidoList::CREAR($data);
        // PHP::EMAIL([
        //     'names' => 'jhamis' . ' ' . 'ruiz',
        //     'email' => 'jhamsel.raec@gmail.com',
        //     'codigo' => '$codigo',
        //     'cuadros' => '$codigo',
        //     'document_number' => '$codigo',
        //     'direccion' => '$codigo',
        //     'tipo_envio' => '$codigo',
        // ]);
    }

    /*=============================================
            GET BY ID PEDIDOS
    =============================================*/
    public function GetPedidosId()
    {
        $data = $_GET['idpedido'];
        $response = ControllerPedidoList::GETPEDIDO($data);
    }
    /*=============================================
            EDITAR PEDIDOS
    =============================================*/
    public function UpdatePedidos()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        echo json_encode($data);
        exit;
        $response = ControllerPedidoList::UPDATE($data);
    }

    /*=============================================
            DELETE PEDIDOS
    =============================================*/
    public function Delete()
    {
        $idpedido = $_GET['idpedido'];
        $response = ControllerPedidoList::DELETE($idpedido);
    }

    /*=============================================
            GET BY ID DELETE
    =============================================*/
    public function DeletePedidoImagen()
    {
        $response = ControllerPedidoList::DELETEIMAGES($_GET['idimagen'], $_GET['file']);
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
        $list = new RouterPedidos();
        $list->listarData = array(
            "start" => $_GET["start"],
            "length" => $_GET["length"],
            "search" => $_GET["search"],
            "order" => $_GET["order"],
        );
        $list->Listar();
    }

    if (isset($_GET['idpedido']) && !empty($_GET['idpedido'])) {
        $getbyid = new RouterPedidos();
        $getbyid->GetPedidosId();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid'])
        && $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'PREVIEW'
    ) {
        $crear = new RouterPedidos();
        $crear->Crear();
    }

    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid'])
        && $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'PEDIDOS-ADD'
    ) {
        $crear = new RouterPedidos();
        $crear->Crear();
    }
}
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'DELETE') {
    if (
        isset($_GET['idpedido']) &&
        $_GET['idpedido']
    ) {
        $crear = new RouterPedidos();
        $crear->Delete();
    }

    //eliminar imagenes
    if (
        isset($_GET["idimagen"]) &&
        $_GET['idimagen']
    ) {
        $up = new RouterPedidos();
        $up->DeletePedidoImagen();
    }
}

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT') {

    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'PEDIDOS-EDIT'
    ) {
        $put = new RouterPedidos();
        $put->UpdatePedidos();
    }
}












// // --|MAIN CONTROLLER  INTERFAZ
// require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

// //IMPORT CONTROLLER
// #importa los controladores que estan dentro de la carpeta
// IMPORT::CONTROLERS('controllers', 'pedidos');

// class RouterPedidos
// {
// /*=============================================
//         LIST PEDIDOS
// =============================================*/
//     public $listarData;
//     public function ListarPedidos()
//     {
//         $data = $this->listarData;
//         $response = ControllerPedidoList::SELECTALL($data);

//     }
//     /*=============================================
//         GET BY ID PEDIDOS
// =============================================*/
//     public $idPedido;
//     public function GetPedidosId()
//     {
//         $data = $this->idPedido;
//         $response = ControllerPedidoList::GETPEDIDO($data);
//     }
//     /*=============================================
//         GET BY ID ESTADOS UPODATE
// =============================================*/
//     public $estado;
//     public function ajaxPedidoEstado()
//     {
//         ini_set('date.timezone', 'America/Lima');
//         $fecha = date('Y-m-d H:i:s', time());

//         $data = $this->estado;
        
//         if($data["estado"]==5){
//             $update = array(
//                 "table" => "pedidos", #nombre de tabla
//                 "id_estado" => $data["estado"], #nombre de columna y valor
//                 "fecha_entrega"=> $fecha,#nombre de columna y valor
//             );
//         }else{
//             $update = array(
//                 "table" => "pedidos", #nombre de tabla
//                 "id_estado" => $data["estado"], #nombre de columna y valor
//                 #"columna"=>"valor",#nombre de columna y valor
//             );
//         }

//         $where = array(
//             "id" => $data["id"], #condifion columna y valor
//         );
//         $response = ControllerQueryes::UPDATE($update, $where);


//         if (
//             isset($response[0]) && $response[0] == 'S'
//         ) {
//             header("HTTP/1.1 500 ERROR");
//         }
//         echo json_encode($response);
//     }
//     /*=============================================
//         GET BY ID DELETE
// =============================================*/
//     public $imagenDel;
//     public function ajaxDeletePedidoImagen()
//     {
//         $data = $this->imagenDel;

//         $response = ControllerPedidoList::DELETEIMAGES($data);
//         if (
//             isset($response[0]) && $response[0] == 'S'
//         ) {
//             header("HTTP/1.1 500 ERROR");
//         }
//         echo json_encode($response);
//     }
// /*=============================================
//         POST PEDIDOS
// =============================================*/
//     public $postPedido;
//     public function ajaxPostPedidos()
//     {
//         $data = $this->postPedido;
//         $response = ControllerPedidoList::GUARDAR($data);
//         echo json_encode($response);
//     }
// /*=============================================
//         PUT PEDIDOS
// =============================================*/
//     public $putPedido;
//     public function ajaxUpdatePedidos()
//     {
//         $data = $this->putPedido;
//         $response = ControllerPedidoList::UPDATE($data);
//         if ($response['sms'] == 'OK') {
//             header("HTTP/1.1 200 OK");
//         } else {
//             header("HTTP/1.1 500 ERROR-MSQL");
//         }
//         echo json_encode($response);
//     }
//     /*=============================================
//         DELETE PEDIDOS
// =============================================*/
//     public $delPedido;
//     public function ajaxDeletePedidos()
//     {
//         $data = $this->delPedido;
//         $response = ControllerPedidoList::DELETE($data);
//         if ($response == 'OK') {
//             header("HTTP/1.1 200 OK");
//         } else {
//             header("HTTP/1.1 500 ERROR-MSQL");
//         }
//         echo json_encode($response);
//     }
//     /* ===============data print=================== */
//     public $printFile;
//     public function ajaxFilesPedidos()
//     {
//         $data = $this->printFile;
//         $response = ControllerPedidoList::FILES($data);
//         if (isset($response[0]) && $response[0] == 'S') {
//             header("HTTP/1.1 500 ERROR");
//         }
//         echo json_encode($response);
//     }
// }


// /* ==== API REQUEST===   */
// $request = json_decode(file_get_contents('php://input'), true);

// /*=============================================
// ---LIST PEDIDOS
// =============================================*/
// if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET')  {
//     // listar todos los Pedidos
//     if (
//         isset($_GET["start"]) && isset($_GET["length"]) &&
//         isset($_GET["search"]) &&  isset($_GET["order"])
//     ) {
//         $list = new RouterPedidos();
//         $list->listarData = array(
//             "start" => $_GET["start"],
//             "length" => $_GET["length"],
//             "search" => $_GET["search"],
//             "order" => $_GET["order"],
//         );
//         $list->ListarPedidos();
//     }

//     // $_GET['id'] caputar el id de la peticion
//     /* obtener Pedidos por id */
//     if (isset($_GET['id']) && !empty($_GET['id'])) {
//         $getbyid = new RouterPedidos();
//         $getbyid->idPedido = $_GET["id"];
//         $getbyid->GetPedidosId();
//     }
//     // // list
//     // if (isset($_GET["length"]) && isset($_GET["search"])) {
//     //     $listPedido = new RouterPedidos();
//     //     $listPedido->lsPedido = array(
//     //         "start" => $_GET["start"],
//     //         "length" => $_GET["length"],
//     //         "search" => $_GET["search"],
//     //     );
//     //     $listPedido->ajaxListPedidos();
//     // }
//     // //get by id
//     // if (isset($_GET["start"]) && $_GET["start"]) {
//     //     $getbyid = new RouterPedidos();
//     //     $getbyid->getPedido = $_GET["start"];
//     //     $getbyid->ajaxGetPedidos();
//     // }
//     // // ACUTALIZAR ESTADO
//     // if (isset($_GET["estado"])) {
//     //     $up = new RouterPedidos();
//     //     $up->estado = array(
//     //         'id' => $_GET["idpedido"],
//     //         'estado' => $_GET["estado"],
//     //     );
//     //     $up->ajaxPedidoEstado();
//     // }
//     // //eliminar imagenes
//     // if (isset($_GET["idimagen"])) {
//     //     $up = new RouterPedidos();
//     //     $up->imagenDel = array(
//     //         'id' => $_GET["idimagen"],
//     //         'url' => $_GET["urlimagen"],
//     //     );
//     //     $up->ajaxDeletePedidoImagen();
//     // }
//     // //print files
//     // if (isset($_GET["desde"]) && $_GET["hasta"]) {
//     //     $print = new RouterPedidos();
//     //     $print->printFile = array(
//     //         "desde" => $_GET["desde"],
//     //         "hasta" => $_GET["hasta"]
//     //     );
//     //     $print->ajaxFilesPedidos();
//     // }
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // GUARDA
//     if ($_REQUEST['formulario']=='POST') {
//         $post = new RouterPedidos();
//         $post->postPedido = $_REQUEST;
//         $post->ajaxPostPedidos();
//     }

//     /// ACTUALZIAR
//     if ($_REQUEST['formulario'] == 'PUT') {
//         $put = new RouterPedidos();
//         $put->putPedido = $_REQUEST;
//         $put->ajaxUpdatePedidos();
//     }
// }
// if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
//     // $put = new RouterPedidos();
//     // $put->putPedido = $request;
//     // $put->ajaxUpdatePedidos();
// }
// if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

//     if ($request) {
//         $del = new RouterPedidos();
//         $del->delPedido = $request;
//         $del->ajaxDeletePedidos();
//     }
// }
