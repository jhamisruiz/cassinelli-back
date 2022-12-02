<?php
class ControllerClientesList
{
    static public function SELECTALL($data)
    {
        $start =  $data['start'];
        $length =  $data['length'];
        $search =  $data['search'];
        $response = ModelClientesList::SELECTALL($start, $length, $search);
        if (isset($response[0])) {
            for ($i = 0; $i < count($response); $i++) {
                $response[$i]['fulladdress'] = json_decode($response[$i]['fulladdress']);
            }
            REQUEST::RESPONDER($response, 200);
        }
        REQUEST::RESPONDER([], 200);
    }

    //CREAR CLIENTES
    static public function GUARDAR($data)
    {
        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());

        $insert = array(
            "table" => "clientes", #TABLA
            "id_documento_type" => 1, //$data['id_documento_type'],
            "names" => $data['names'],
            "last_name" => $data['last_name'],
            "document_number" => $data['document_number'],
            "email" => $data['email'],
            "phone" => $data['phone'],
            'date_create' => $fecha,
            'id_ubigeo' => $data['id_ubigeo'],
            'direccion' => $data['direccion'],
            'referencia' => $data['referencia'],
        );
        $response = ModelQueryes::INSERT($insert);
        if ($response == 'OK') {
            REQUEST::RESPONDER(1, 201);
        }
        Errors::__Log('Error al agregar el cliente.', 202);
    }
    //ACTUALIZAR CLIENTES
    static public function UPDATE($data)
    {
        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());

        $update = array(
            "table" => "clientes", #nombre de tabla
            //"id_documento_type" => 1, //$data['id_documento_type'],
            "names" => $data['names'],
            "last_name" => $data['last_name'],
            "document_number" => $data['document_number'],
            "email" => $data['email'],
            "phone" => $data['phone'],
            'id_ubigeo' => $data['id_ubigeo'],
            'direccion' => $data['direccion'],
            'referencia' => $data['referencia'],
        );
        $where = array("id" => $data["id"]); #condifion columna y valor
        $response = ModelQueryes::UPDATE($update, $where);
        REQUEST::RESPONDER($response, 200);
    }
    //ACTUALIZAR CLIENTES
    static public function tempDELETE($data)
    {

        $update = array(
            "table" => "clientes", #nombre de tabla
            'eliminar' => 1,
        );
        $where = array("id" => $data); #condifion columna y valor
        $response = ModelQueryes::UPDATE($update, $where);
        if ($response == 1) {
            REQUEST::RESPONDER(1, 200);
        } else {
            Errors::__Log('No se elimino el Usuario.', 202);
        }
    }
    //ACTUALIZAR CLIENTES
    static public function EXPORTFILE($data)
    {
        $data['desde'] = date('Y-m-d', strtotime($data['desde'])) . ' 01:00:00';
        $data['hasta'] = date('Y-m-d', strtotime($data['hasta'])) . ' 23:00:00';
        $desde = $data['desde'];
        $hasta = $data['hasta'];
        $response = ModelClientesList::EXPORTFILE($desde, $hasta);
        return $response;
    }
}
