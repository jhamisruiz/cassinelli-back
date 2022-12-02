<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
//IMPORT::CONTROLERS('controllers', 'auth');

class RouterUM
{
    //static functions...
    public function UMEDIDALIST()
    {
        $select = ["*" => "*"];
        $tables = ["unidad_medida" => ""];
        $where = '';
        $response = ControllerQueryes::SELECT($select, $tables, $where);
        REQUEST::RESPONDER($response, 200);
        exit;
    }

    public function UPDATE()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $um = $data['update'];
        for ($i = 0; $i < count($um); $i++) {
            if ($um[$i]["id"]) {
                $update = array(
                    "table" => "unidad_medida",
                    "nombre" => $um[$i]['nombre'],
                    "descripcion" => $um[$i]['descripcion'],
                    "valor" => $um[$i]['valor'],
                );
                $where = array("id" => $um[$i]["id"],);
                $updt = ControllerQueryes::UPDATE($update, $where);
            } else {
                $iinsertUM = [
                    "table" => "unidad_medida",
                    "nombre" => $um[$i]['nombre'],
                    "descripcion" => $um[$i]['descripcion'],
                    "valor" => $um[$i]['valor'],
                ];
                $res = ControllerQueryes::INSERT($iinsertUM);
            }
        }
        $del = $data['delete'];
        for ($i = 0; $i < count($del); $i++) {
            $delete = array("table" => "unidad_medida", "id" => $del[$i]['id']);
            $response = ControllerQueryes::DELETE($delete);
        }

        REQUEST::RESPONDER(1, 200);
    }
}

//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    // code...
    $list = new RouterUM();
    $list->UMEDIDALIST();
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {
    // code...
    $list = new RouterUM();
    $list->UPDATE();
}
