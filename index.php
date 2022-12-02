<?php
//header("Content-Type: application/json");
/* ********************* */
if (file_exists('.env')) {
        require_once "app/Core.php";

        APP_DIRS::GET_ALL_APP_DIRS('app/controllers', 'require_once', 'php');
        APP_DIRS::GET_ALL_APP_DIRS('app/models', 'require_once', 'php');
        
        Functions::HEADERS();
        if ($_SERVER['REQUEST_URI'] === '/') {
                Response::HttpResponse(404, false);
        }
        $R_URI = explode("/", $_SERVER['REQUEST_URI']);
        //
        if ($R_URI[1] === 'documentacion') {
                require_once 'swagger-ui/index.php';
                exit;
        }
        //Functions::HEADERS();
        //APP_DIRS::GET_ALL_APP_DIRS('app/php', 'require_once', 'php');

        //echo json_encode($_SERVER);

        $rout = Deliver::ROUTER();

        if (isset($rout->{'headers_to_pass'}) && $rout->{'headers_to_pass'}) {
                //$valid = Functions::validaToken($_SERVER);
        }

        // valida las rutas
        if (!isset($rout->{'rutas'})) {

                //echo json_encode($rout);
                if (!$rout->{'file_name'}) {
                        echo APP_DIRS::GET_ALL_APP_DIRS('app/api/' . $rout->{'folder_name'}, 'require_once', 'php');
                } else {
                        APP_DIRS::get_file('app/api/' . $rout->{'folder_name'} . '/' . $rout->{'file_name'}, 'require');
                }
        } else {

                //valida ruta en las registradas
                if (!in_array('/' . $rout->{'ruta'}, $rout->{'rutas'}) || $rout->{'err'}) {
                        //responde 
                        Response::HttpResponse(404, false);
                }

                //valida metodo en lista de metodos registrados
                if (!in_array($_SERVER['REQUEST_METHOD'], $rout->{'metodos'})) {
                        //responde 
                        if (!isset($_SERVER['HTTP_ORIGIN'])) {
                                Response::HttpResponse(405);
                        }
                }
        }
} else {
        echo 'archivo de configiguracion .evn no EXISTE!';
        exit;
}


///NOTE: sfc /scannow