<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
//IMPORT::CONTROLERS('controllers', 'auth');

class RouterImpuesto
{
    //static functions...
    public function IMPUESTOLIST()
    {
        $select = ["*" => "*"];
        $tables = ["impuestos" => ""];
        $where = '';
        $response = ControllerQueryes::SELECT($select, $tables, $where);
        for ($i = 0; $i < count($response); $i++) {
            $response[$i]['valor'] = (float)$response[$i]['valor'];
        }
        REQUEST::RESPONDER($response, 200);
        exit;
    }

    public function UPDATE()
    {
        $data = $_REQUEST['REQUEST_ARRAY_DATA'];
        $upd = $data['update'];
        for ($i = 0; $i < count($upd); $i++) {
            if ($upd[$i]['id']) {
                $update = array(
                    "table" => "impuestos",
                    "nombre" => strtoupper($upd[$i]['nombre']),
                    "descripcion" => $upd[$i]['descripcion'],
                    "valor" => $upd[$i]['valor'],
                );
                $where = array("id" => $upd[$i]["id"],);
                $updt = ControllerQueryes::UPDATE($update, $where);
            } else {
                $insert = [
                    "table" => "impuestos",
                    "nombre" => strtoupper($upd[$i]['nombre']),
                    "descripcion" => $upd[$i]['descripcion'],
                    "valor" => $upd[$i]['valor'],
                ];
                $res = ControllerQueryes::INSERT($insert);
            }
        }
        $del = $data['delete'];
        for ($i = 0; $i < count($del); $i++) {
            $delete = array("table" => "impuestos", "id" => $del[$i]['id']);
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
    $list = new RouterImpuesto();
    $list->IMPUESTOLIST();
}

if (strtoupper($_SERVER['REQUEST_METHOD']) === 'PUT') {
    // code...
    $list = new RouterImpuesto();
    $list->UPDATE();
}
