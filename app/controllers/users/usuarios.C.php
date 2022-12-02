<?php
class ControllerUsuarios
{
    static public function SELECTALL($data)
    {
        $table = 'usuarios'; #nombre de la tabla o vista

        $columns = "id, names, last_name, email, user_name, estado, phone"; #culumnas de la tabla para mostrar

        $params = ['id', 'names', 'last_name', 'email', 'user_name']; #columnas por las que se realizara la busqueda

        $response = ModelQueryes::SELECT_NV($table, $columns, $params, $data); #funcion para traer la data
        REQUEST::RESPONDER($response, 200);
    }

    //Crea un usuario
    static public function CREAR($data)
    {

        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());
        if (
            $data["names"] &&
            $data["last_name"] &&
            $data["user_name"] &&
            $data["password"] &&
            $data["rep_password"]
        ) {
            if ($data["password"] != $data["rep_password"]) {
                Errors::__Log('Las contraseñas no coinciden.', 200);
            }
            //encripta password
            $data["password"]  = password_hash($data["password"], PASSWORD_DEFAULT);

            $data['fecha_registro'] = $fecha;

            $lastid = ModelUsuarios::CREAR($data);

            //busco el usuario insertado para devolver a la vista
            $usuario = ModelUsuarios::GETUSUARIOID($lastid);
            REQUEST::RESPONDER($usuario, 201);
        } else {
            Errors::__Log('Llena todos los campos obligatorios (*)', 200);
        }
    }

    static public function GETUSUARIOID($data)
    {
        $response = ModelUsuarios::GETUSUARIOID($data);

        REQUEST::RESPONDER($response, 200);
    }
    static public function ACTUALIZAR($data, $id)
    {
        if (
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $data->{"names"}) &&
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $data->{"last_name"}) &&
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $data->{"user_name"})
        ) {
            $response = ModelUsuarios::ACTUALIZAR((array)$data, $id);

            //busco el usuario insertado para devolver a la vista
            $usuario = ModelUsuarios::GETUSUARIOID($id);
            REQUEST::RESPONDER($usuario, 200);
        } else {
            Errors::__Log('Llena todos los campos obligatorios (*)', 202);
        }
    }
    static public function RESETPASSWORD($data, $id)
    {
        if (
            $data["password"] &&
            $data["rep_password"]
        ) {
            if ($data['password'] != $data['rep_password']) {
                Errors::__Log('Las contraseñas no coinciden.', 200);
            }

            $update = [
                "table" => "usuarios", #nombre de tabla
                "`password`" => password_hash($data['password'], PASSWORD_DEFAULT), #nombre de columna y valor
                #"columna"=>"valor",#nombre de columna y valor
            ];
            $where = [
                "id" => $id, #condifion columna y valor
            ];
            $response =  ModelQueryes::UPDATE($update, $where);
            //return $response;
            REQUEST::RESPONDER($response, 200);
        } else {
            Errors::__Log('Llena todos los campos obligatorios (*)', 200);
        }
    }
    static public function PERMISOS($data)
    {
        ////FIXME:falta implementar

        // $permisos = $data['permisos'];

        // $values = '';
        // for ($i = 0; $i < count($permisos); $i++) {
        //     $coma = ',';
        //     if ($i == (count($permisos) - 1)) {
        //         $coma = '';
        //     }
        //     $values .= '(' . $data['id'] . ',' . $permisos[$i] . ')' . $coma;
        // }
        // $response = ModelUsuarios::PERMISOS($values, $data['id']);
        // return $response;
    }
    static public function HABILITARDESHABILITAR($id, $data)
    {
        $update = [
            "table" => "usuarios", #nombre de tabla 
            "estado" => $data,
        ];
        $where = [
            "id" => $id, #condifion columna y valor );
        ];
        $response = ModelQueryes::UPDATE($update, $where);
        if ($response) {
            REQUEST::RESPONDER(($data) ? 'Habilitado' : 'Deshabilitado', 200);
        } else {
            Errors::__Log('No se actualizo el estado.', 202);
        }
    }
    static public function ELIMINAR($data)
    {
        $delete = [
            "table" => "usuarios",
            "id" => $data,
        ];
        $response = ModelQueryes::DELETE($delete);
        if (!$response) {
            REQUEST::RESPONDER(1, 200);
        } else {
            Errors::__Log('No se elimino el Usuario.', 202);
        }
    }

    //FIXME: FALTA IMPLEMENTAR
    static public function FILES($data)
    {
        $data['desde'] = date('Y-m-d', strtotime($data['desde']));
        $data['hasta'] = date('Y-m-d', strtotime($data['hasta']));
        $response = ModelUsuarios::FILES($data);
        return $response;
    }
}
