<?php
class ControllerProveedores
{
    static public function SELECTALL($data)
    {
        $table = 'nv_proveedor'; #nombre de la tabla o vista

        $columns = "id, nombre, id_documento, nombre_documento, numero_documento, telefono, email, direccion, fecha_registro, estado"; #culumnas de la tabla para mostrar

        $params = ['id', 'nombre', 'telefono', 'email']; #columnas por las que se realizara la busqueda

        $response = ModelQueryes::SELECT_NV($table, $columns, $params, $data); #funcion para traer la data

        if ($response) {
            for ($i = 0; $i < count($response); $i++) {
                $cuentas = ControllerQueryes::SELECT(
                    ['*' => '*'],
                    ["cuentas" => ""],
                    ["id_proveedor" => '=' . $response[$i]['id']]
                );
                $insumos = ControllerQueryes::SELECT(
                    ['*' => '*'],
                    ["insumos_proveedor" => ""],
                    ["id_proveedor" => '=' . $response[$i]['id']]
                );
                $response[$i]['cuentas'] = $cuentas;
                $response[$i]['insumos'] = $insumos;
            }
        }
        REQUEST::RESPONDER($response, 200);
    }

    //Crea un PROVEEDOR
    static public function CREAR($data)
    {

        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());
        if (
            isset($data["nombre"]) &&
            isset($data["numero_documento"]) &&
            isset($data["email"])
        ) {
            $data['fecha_registro'] = $fecha;

            $insert = [
                "table" => "proveedor", #tabla proveedor
                "nombre" => $data['nombre'],
                "id_documento" => $data['id_documento'],
                "numero_documento" => $data['numero_documento'],
                "telefono" => $data['telefono'],
                "email" => $data['email'],
                "direccion" => $data['direccion'],
                "fecha_registro" => $fecha,
                "LASTID" => "YES"
            ];
            $lastid = ControllerQueryes::INSERT($insert); //registra proveedor
            $cuentas = $data['cuentas']['data'];
            $insumos = $data['insumos']['data'];
            //CUENTAS
            if ($cuentas) {
                for ($c = 0; $c < count($cuentas); $c++) {
                    $insert = [
                        "table" => "cuentas",
                        "id_proveedor" => $lastid,
                        "numero" => $cuentas[$c]["numero"],
                        "tipo" => $cuentas[$c]["tipo"],
                        "banco" => strtoupper($cuentas[$c]["banco"])
                    ];
                    $res = ControllerQueryes::INSERT($insert);
                }
            }
            //INSUMOS
            if ($insumos) {
                for ($i = 0; $i < count($insumos); $i++) {
                    $val = $insumos[$i]['precio'];
                    $precio = str_replace(',', '', $val);
                    $insert = [
                        "table" => "insumos_proveedor",
                        "id_proveedor" => $lastid,
                        "codigo" => $insumos[$i]['codigo'],
                        "nombre" => $insumos[$i]['nombre'],
                        "descripcion" => $insumos[$i]['descripcion'],
                        "id_unidad_medida" => $insumos[$i]['id_unidad_medida'],
                        "precio" => $precio,
                        "fecha_registro" => $fecha,
                    ];
                    $res = ControllerQueryes::INSERT($insert);
                }
            }

            //busco el usuario insertado para devolver a la vista
            //$usuario = ModelProveedores::GETPROVEEID($lastid);
            REQUEST::RESPONDER(1, 201);
        } else {
            Errors::__Log('Llena todos los campos obligatorios (*)', 200);
        }
    }

    static public function GETPROVEEID($data)
    {
        $cuentas = ControllerQueryes::SELECT(
            ['*' => '*'],
            ["cuentas" => ""],
            ["id_proveedor" => '=' . $data]
        );
        if ($cuentas) {
            for ($i = 0; $i < count($cuentas); $i++) {
                # code...
            }
        }

        $insumos = ControllerQueryes::SELECT(
            ['*' => '*'],
            ["insumos_proveedor" => ""],
            ["id_proveedor" => '=' . $data]
        );

        $response = ModelProveedores::GETPROVEEID($data);
        if ($response) {
            $response['cuentas'] = $cuentas;
            $response['insumos'] = $insumos;

            REQUEST::RESPONDER($response, 200);
        }
    }

    static public function ACTUALIZAR($data, $id)
    {
        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());
        if (
            isset($data["nombre"]) &&
            isset($data["numero_documento"]) &&
            isset($data["email"])
        ) {
            $update = array(
                "table" => "proveedor", #nombre de tabla 
                "nombre" => $data["nombre"], #nombre de columna y valor 
                "id_documento" => $data["id_documento"],
                "numero_documento" => $data["numero_documento"],
                "telefono" => $data["telefono"],
                "email" => $data["email"],
                "direccion" => $data["direccion"],
            );
            $where = array(
                "id" => $data["id"], #condifion columna y valor 
            );
            $updt = ControllerQueryes::UPDATE($update, $where);

            $del_c = $data["cuentas"]['delete'];
            $cuentas = $data["cuentas"]['data'];
            $del_i = $data["insumos"]['delete'];
            $ins = $data["insumos"]['data'];
            //ELIMINA CUENTAS
            if ($del_c) {
                for ($e = 0; $e < count($del_c); $e++) {
                    $delete = array("table" => "cuentas", "id" => $del_c[$e]['id']);
                    $del = ControllerQueryes::DELETE($delete);
                }
            }
            //ACTUALIZA - INSERTA CUENTAS
            if ($cuentas) {
                for ($c = 0; $c < count($cuentas); $c++) {
                    if ($cuentas[$c]["id"]) {
                        $update = [
                            "table" => "cuentas",
                            "numero" => $cuentas[$c]["numero"],
                            "tipo" => $cuentas[$c]["tipo"],
                            "banco" => strtoupper($cuentas[$c]["banco"])
                        ];
                        $where = array("id" => $cuentas[$c]["id"],);
                        $updte = ControllerQueryes::UPDATE($update, $where);
                    } else {
                        $insert = [
                            "table" => "cuentas",
                            "id_proveedor" => $data["id"],
                            "numero" => $cuentas[$c]["numero"],
                            "tipo" => $cuentas[$c]["tipo"],
                            "banco" => strtoupper($cuentas[$c]["banco"])
                        ];
                        $res = ControllerQueryes::INSERT($insert);
                    }
                }
            }
            //INSUMOS
            if ($del_i) {
                for ($i = 0; $i < count($del_i); $i++) {
                    $delete = array("table" => "insumos_proveedor", "id" => $del_i[$i]['id']);
                    $del = ControllerQueryes::DELETE($delete);
                }
            }
            if ($ins) {
                for ($i = 0; $i < count($ins); $i++) {
                    if ($ins[$i]["id"]) {
                        $update = array(
                            "table" => "insumos_proveedor",
                            "codigo" => $ins[$i]['codigo'],
                            "nombre" => $ins[$i]['nombre'],
                            "descripcion" => $ins[$i]['descripcion'],
                            "id_unidad_medida" => $ins[$i]['id_unidad_medida'],
                            "precio" => str_replace(',', '', $ins[$i]['precio']),
                        );
                        $where = array("id" => $ins[$i]["id"],);
                        $updt = ControllerQueryes::UPDATE($update, $where);
                    } else {
                        $insert = [
                            "table" => "insumos_proveedor",
                            "id_proveedor" => $data["id"],
                            "codigo" => $ins[$i]['codigo'],
                            "nombre" => $ins[$i]['nombre'],
                            "descripcion" => $ins[$i]['descripcion'],
                            "id_unidad_medida" => $ins[$i]['id_unidad_medida'],
                            "precio" => str_replace(',', '', $ins[$i]['precio']),
                            "fecha_registro" => $fecha,
                        ];
                        $res = ControllerQueryes::INSERT($insert);
                    }
                }
            }
            REQUEST::RESPONDER(1, 200);
        } else {
            Errors::__Log('Llena todos los campos obligatorios (*)', 202);
        }
    }

    static public function HABILITARDESHABILITAR($id, $data)
    {
        $update = [
            "table" => "proveedor", #nombre de tabla 
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

        //cuentas
        $delete = [
            "table" => "cuentas",
            "id_proveedor" => $data,
        ];
        $response = ControllerQueryes::DELETE($delete);
        //insumos
        $delete = [
            "table" => "insumos_proveedor",
            "id_proveedor" => $data,
        ];
        $response = ControllerQueryes::DELETE($delete);

        //proveedor
        $delete = [
            "table" => "proveedor", "id" => $data,
        ];
        $response = ModelQueryes::DELETE($delete);
        if (!$response) {
            REQUEST::RESPONDER(1, 200);
        } else {
            Errors::__Log('No se elimino el Proveedor.', 202);
        }
    }

    //FIXME: FALTA IMPLEMENTAR
    static public function FILES($data)
    {
        $data['desde'] = date('Y-m-d', strtotime($data['desde']));
        $data['hasta'] = date('Y-m-d', strtotime($data['hasta']));
        $response = ModelProveedores::FILES($data);
        return $response;
    }
}
