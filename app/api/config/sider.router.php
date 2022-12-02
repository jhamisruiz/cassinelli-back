<?php
// --|MAIN CONTROLLER  INTERFAZ
require_once(dirname(__FILE__) . './../../controllers/main.Controller.php');

//IMPORT CONTROLLER
#importa los controladores que estan dentro de la carpeta
//IMPORT::CONTROLERS('controllers', 'auth');

class RouterSide
{
    //static functions...
    public function SIDEBAR()
    {
        $select = ['*' => '*'];

        $tables = ["menu" => "",];

        $where = ['estado' => '=1 ORDER BY orden ASC'];

        $menu = ModelQueryes::SELECT($select, $tables, $where);

        for ($i = 0; $i < COUNT($menu); $i++) {
            $select = ['*' => '*'];

            $tables = ["submenu" => "",];

            $where = ['id_menu' => '=' . $menu[$i]['id'] . ' and estado=1 ORDER BY orden ASC'];

            $submenu = ModelQueryes::SELECT($select, $tables, $where);

            if (isset($submenu) && COUNT($submenu)) {
                $menu[$i]['submenu'] = $submenu;
            }
        }
        REQUEST::RESPONDER($menu, 200);
        exit;
    }
}

//*********************ROUTERS********
///****************VALIDA METODOS

// $_REQUEST['REQUEST_ARRAY_DATA'] -> recibe la data de la vista como un ARREGLO
// $_REQUEST['REQUEST_OBJECT_DATA'] -> recibe la data de la vista como un OBJETO

///VALIDA METODOS
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
    // code...
    $list = new RouterSide();
    $list->SIDEBAR();
}
