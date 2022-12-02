<?php
class CuponesController
{
    static public function LISTAR($data)
    {

        $table = 'cupones'; #nombre de la tabla o vista

        $columns = "id, codigo, monto_min, monto_max, monto, porcentaje, fecha_registro, fecha_fin, titulo_mensaje, mensaje, bg_color, estado"; #culumnas de la tabla para mostrar

        $params = ['id', 'codigo']; #columnas por las que se realizara la busqueda

        $response = ModelQueryes::SELECT_NV($table, $columns, $params, $data); #funcion para traer la data
        $cupones = [];
        if (isset($response[0])) {
            for ($i = 0; $i < count($response); $i++) {
                $response[$i]['estado'] = ($response[$i]['estado'] == 1) ? 1 : 0;
                if ($response[$i]['estado']) {
                    $cupones[] = $response[$i];
                }
            }
            REQUEST::RESPONDER($response, 200);
        }
        REQUEST::RESPONDER([], 200);
    }
    static public function GUARDAR($data)
    {
        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());

        $data['fecha_fin'] = date('Y-m-d', strtotime($data['fecha_fin']));
        // $data['monto']=($data['monto'])? $data['monto'] : null;
        // $data['porcentaje'] = ($data['monto']) ? null : $data['porcentaje'];

        if (
            isset($data['codigo']) &&
            isset($data['monto_min']) && isset($data['monto_max'])
            && isset($data['fecha_fin']) && isset($data['titulo_mensaje'])
            && isset($data['mensaje']) && isset($data['bg_color'])
        ) {
            $insert = [
                "table" => "cupones",
                "codigo" => strtoupper($data['codigo']),
                "monto_min" => $data['monto_min'],
                "monto_max" => $data['monto_max'],
                "monto" => $data['monto'],
                "porcentaje" => $data['porcentaje'],
                "fecha_registro" => $fecha,
                "fecha_fin" => $data['fecha_fin'],
                "titulo_mensaje" => $data['titulo_mensaje'],
                "mensaje" => $data['mensaje'],
                "bg_color" => $data['bg_color']
            ];
            $response = ModelQueryes::INSERT($insert);

            if ($response == 'OK') {
                REQUEST::RESPONDER(1, 201);
            }
            Errors::__Log('Error al crear cupon.', 202);
        }
        Errors::__Log('Llena todos los campos obligatorios (*)', 202);
    }
    static public function ACTUALZIAR($data)
    {
        ini_set('date.timezone', 'America/Lima');

        $data['fecha_fin'] = date('Y-m-d', strtotime($data['fecha_fin']));
        // $data['monto']=($data['monto'])? $data['monto'] : null;
        // $data['porcentaje'] = ($data['monto']) ? null : $data['porcentaje'];

        if (
            isset($data['codigo']) &&
            isset($data['monto_min']) && isset($data['monto_max'])
            && isset($data['fecha_fin']) && isset($data['titulo_mensaje'])
            && isset($data['mensaje']) && isset($data['bg_color'])
        ) {
            $update = [
                "table" => "cupones",
                "codigo" => strtoupper($data['codigo']),
                "monto_min" => $data['monto_min'],
                "monto_max" => $data['monto_max'],
                "monto" => $data['monto'],
                "porcentaje" => $data['porcentaje'],
                "fecha_fin" => $data['fecha_fin'],
                "titulo_mensaje" => $data['titulo_mensaje'],
                "mensaje" => $data['mensaje'],
                "bg_color" => $data['bg_color'],
            ];
            $where = array("id" => $data["id"],);
            $response = ModelQueryes::UPDATE($update, $where);
            REQUEST::RESPONDER($response, 200);
        } else {
            Errors::__Log('Llena todos los campos obligatorios (*)', 202);
        }
    }
    static public function ELIMINAR($data)
    {
    }
}
