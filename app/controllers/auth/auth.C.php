<?php

class AuthController
{

    static public function LOGIN($data)
    {
        $select = [
            "*" => "*",
        ];
        $tables = ["usuarios" => ""];
        $where = [
            "user_name" => "='" . $data['user_email'] . "' OR email ='" . $data['user_email'] . "'"
        ];
        $response = ModelQueryes::SELECT($select, $tables, $where);
        if (isset($response[0]['estado'])) {
            if ($response[0]['estado'] == 1) {
                if (password_verify($data['password'], $response[0]['password'])) {

                    $jwt = Functions::generaToken(["id" => $response[0]['id'], "email" => $response[0]['email']]);

                    $rows = ControllerQueryes::ROWCOUNT('token', ' id_usuario =' . $response[0]['id']);
                    if ($rows) {
                        // FIXME: validar sessiones token para mas de un login 
                        $update = array(
                            "table" => "token", #nombre de tabla
                            "token" => $jwt['token'], #nombre de columna y valor
                            "time_exp" => $jwt['data']["exp"], #nombre de columna y valor
                            #"columna"=>"valor",#nombre de columna y valor
                        );
                        $where = array(
                            "id_usuario" => $response[0]['id'], #condifion columna y valor
                        );
                        $_query =  ModelQueryes::UPDATE($update, $where);
                    } else {
                        $insert = [
                            "table" => "token",
                            "token" => $jwt['token'],
                            "time_exp" => $jwt['data']["exp"],
                            "id_usuario" => $response[0]['id']
                        ];
                        $_query = ModelQueryes::INSERT($insert);
                    }

                    if (!$_query && $_query != "OK") {
                        Errors::__Log('Error al crear la sesion.');
                        exit();
                    };
                    $response = $response[0];
                    $response['token'] = $jwt['token'];
                    $response['expire'] = $jwt['data']["exp"];
                    $response['sid'] = $jwt['sid'];
                    unset($response['password']);

                    //responde a la peticion con codigo 201 sesion creada
                    REQUEST::RESPONDER($response, 201);
                } else {
                    Errors::__Log('Las contraseñas no coinciden.', 200);
                    exit();
                }
            } else {
                Errors::__Log('Usuario o email esta deshabilitado.', 200);
                exit();
            }
        } else {
            Errors::__Log('Usuario o Email incorrecto!', 200);
            exit();
        }
    }
}
