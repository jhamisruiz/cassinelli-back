<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
//IMPORT::CONTROLERS('controllers', 'auth');

class RouterConif
{
    //static functions...
    public function CONFIG()
    {
        $select = [
            "id" => "id",
            "razon_social" => "razon_social",
            "ruc" => "ruc",
            "direccion" => "direccion",
            "telefono" => "telefono",
            "email" => "email"
        ];
        $tables = ["empresa" => ""];
        $where = '';
        $response = ControllerQueryes::SELECT($select, $tables, $where);
        if ($response) {
            REQUEST::RESPONDER($response[0], 200);
            exit;
        }
        Errors::__Log('No existen datos', 202);
    }

    public function PUTEMPRESA()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $update = [
            "table" => "empresa",
            "razon_social" => strtoupper($data['razon_social']),
            "ruc" => strtoupper($data['ruc']),
            "direccion" => strtoupper($data['direccion']),
            "telefono" => strtoupper($data['telefono']),
            "email" => strtoupper($data['email']),
            #...
        ];

        $where = array("id" => $data["id"],);
        $response = ControllerQueryes::UPDATE($update, $where);

        if ($response == 1) {
            REQUEST::RESPONDER($response, 200);
        }
        Errors::__Log('los datos son iguales,', 202);
    }
}

//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    // code...
    $list = new RouterConif();
    $list->CONFIG();
}

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    // code...
    $list = new RouterConif();
    $list->CONFIG();
}
///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {
    // code...
    if (
        isset($_REQUEST['REQUEST_ARRAY_DATA']['componentid']) &&
        $_REQUEST['REQUEST_ARRAY_DATA']['componentid'] == 'EMPRESA-EDIT'
    ) {
        $list = new RouterConif();
        $list->PUTEMPRESA();
    }
}
