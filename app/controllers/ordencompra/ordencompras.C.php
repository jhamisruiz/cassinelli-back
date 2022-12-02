<?php
class ControllerOrden
{
    static public function SELECTALL($data)
    {
        //$id =  ($data['start']) ? $data['start'] : '';
        $table = 'nv_orden_compra'; #nombre de la tabla o vista

        $columns = "banco, codigo,descripcion,direccion,direccion_proveedor,documento_proveedor,email,email_proveedor,
        fecha_registro,id,id_cuenta,id_empresa,id_estado,id_proveedor,igv,moneda,nombre_estado,flujo,
        numero_documento,percepcion,proveedor,razon_social,ruc,subtotal,telefono,telefono_proveedor,tipo_cuenta,total,total_impuesto
        "; #culumnas de la tabla para mostrar

        $params = ['id', 'codigo', 'fecha_registro', 'proveedor', 'moneda']; #columnas por las que se realizara la busqueda

        $orden = ModelQueryes::SELECT_NV($table, $columns, $params, $data); #funcion para traer la data
        if ($orden) {
            for ($i = 0; $i < count($orden); $i++) {
                $orden[$i]['flujo'] = json_decode($orden[$i]['flujo']);
                $idorden = $orden[$i]['id'];
                $select = [
                    "D.id" => "",
                    "D.idorden_compra" => "",
                    "D.id_insumo" => "",
                    "D.cantidad" => "",
                    "D.impuesto" => "",
                    "D.total_impuesto" => "",
                    "D.precio" => "",
                    "D.total" => "",
                    "I.codigo" => "",
                    "I.nombre" => "",
                    "I.descripcion" => "",
                    "I.id_unidad_medida" => "",
                    "U.nombre" => "nombre_um",
                    "U.	descripcion" => "descripcion_um",
                    "U.	valor" => "valor_um",
                ];
                $tables = [
                    "orden_detalle D" => "insumos_proveedor I",
                    "D.id_insumo" => "I.id",
                    "unidad_medida U" => "",
                    "U.id" => "I.id_unidad_medida",
                ];
                $where = [
                    "idorden_compra =" => $idorden,
                ];
                $detalle = ModelQueryes::SELECT($select, $tables, $where);
                if ($detalle) {
                    for ($p = 0; $p < count($detalle); $p++) {
                        $idimpu = $detalle[$p]['id'];
                        $select = [
                            "*" => "*",
                        ];
                        $tables = [
                            "orden_detalle_impuesto" => "",
                        ];
                        $where = [
                            "idorden_detalle =" => $idimpu,
                        ];
                        $i_detalle = ModelQueryes::SELECT($select, $tables, $where);
                        //$detalle[$p]['index'] = $idimpu;
                        $detalle[$p]['detalle_impuesto'] = $i_detalle;
                        $orden[$i]['id_ins'] = $detalle[$p]['id_insumo'];
                    }
                    $orden[$i]['orden_detalle'] = $detalle;
                }
            }
        }
        REQUEST::RESPONDER($orden, 200);
    }
    static public function BUSCARBYID($id)
    {
        $select = [
            "*" => "*",
        ];
        $tables = [
            "ordenes_compra" => "" #1-1
        ];
        $where = [
            "id" => "=" . $id,
        ];
        $response = ModelQueryes::SELECT($select, $tables, $where);
        if (isset($response[0])) {
            $response = (object)$response[0];
            $date = new DateTime($response->{'fecha_registro'});
            $response->{'componentid'} = "ORDENCOMPRAS-EDIT";
            $response->{'fecha_registro'} = $date->format('Y-m-d');

            $select = [
                "D.id" => '',
                "D.id_insumo" => "",
                "D.cantidad" => "",
                "D.precio" => "",
                "D.impuesto" => "",
                "D.total_impuesto" => "",
                "D.subtotal" => "",
                "D.total" => "",
                "I.codigo" => "",
                "I.nombre" => "",
                "I.descripcion" => "",
                "I.id_unidad_medida" => "",
                "U.nombre" => "unidadmedida",
                "U.descripcion" => "unidadmedida_desc",
            ];
            $tables = [
                "orden_detalle D" => "insumos_proveedor I",
                "D.id_insumo" => "I.id",
                "unidad_medida U" => "",
                "U.id" => "I.id_unidad_medida",
            ];
            $where = [
                "D.idorden_compra" => "=" . $id,
            ];
            $detalle = ModelQueryes::SELECT($select, $tables, $where);
            //$detalle = $detalle ?? [];
            for ($i = 0; $i < count($detalle); $i++) {
                $detalle[$i]['unidadmedida'] = $detalle[$i]['unidadmedida'] . '-' . $detalle[$i]['unidadmedida_desc'];

                $select = [
                    "I.id" => "",
                    "I.nombre" => "",
                    "I.descripcion" => "",
                    "I.valor" => "",
                    "I.estado" => ""
                ];
                $tables = [
                    "orden_detalle_impuesto OI" => "impuestos I",
                    "OI.id_impuesto" => "I.id",
                ];
                $where = [
                    "idorden_detalle" => "=" . $detalle[$i]['id'],
                ];
                $impuesto = ModelQueryes::SELECT($select, $tables, $where);
                for ($j = 0; $j < count($impuesto); $j++) {
                    $impuesto[$j]['valor'] = (float)$impuesto[$j]['valor'];
                }
                $detalle[$i]['detalle_impuesto'] = $impuesto;
            }


            $response->{'orden_detalle'} = $detalle;
            $response->{'componentid '} = 'ORDENCOMPRAS-EDIT';

            REQUEST::RESPONDER($response, 200);
        }
    }

    static public function CREAR($data)
    {
        $fecha = fechalocal();
        $detalle = $data["orden_detalle"] ?? [];
        if (!$data['id']) {
            $insert = [
                "table" => "ordenes_compra",
                "codigo" => $data['codigo'],
                "id_empresa" => $data['id_empresa'],
                "descripcion" => $data['descripcion'],
                "id_estado" => 1,
                "id_proveedor" => $data['id_proveedor'],
                "fecha_registro" => $fecha,
                "moneda" => $data['moneda'],
                "id_cuenta" => $data['id_cuenta'],
                "igv" => $data['igv'],
                "percepcion" => $data['percepcion'],
                "total_impuesto" => $data['total_impuesto'],
                "subtotal" => $data['subtotal'],
                "total" => $data['total'],
                "LASTID" => "YES"

            ];
            $lsidorden = ModelQueryes::INSERT($insert);
            ///insert detale de orden
            if (isset($lsidorden) && $lsidorden) {
                for ($i = 0; $i < count($detalle); $i++) {
                    ///detalle impuestos insumo
                    $d_imp = $detalle[$i]["detalle_impuesto"] ?? [];

                    $insert = [
                        "table" => "orden_detalle",
                        "idorden_compra" => $lsidorden,
                        "id_insumo" => $detalle[$i]["id_insumo"],
                        "cantidad" => $detalle[$i]["cantidad"],
                        "impuesto" => $detalle[$i]['impuesto'],
                        "total_impuesto" => $detalle[$i]['total_impuesto'],
                        "precio" => $detalle[$i]["precio"],
                        "subtotal" => $detalle[$i]["subtotal"],
                        "total" => $detalle[$i]["total"],
                        "LASTID" => "YES"
                    ];
                    $lsdin = ModelQueryes::INSERT($insert);
                    if (isset($lsdin) && $lsdin) {
                        for ($d = 0; $d < count($d_imp); $d++) {
                            $insert = [
                                "table" => "orden_detalle_impuesto",
                                "idorden_detalle" => $lsdin,
                                "id_impuesto" => $d_imp[$d]["id"],
                                "valor" => $d_imp[$d]["valor"],
                            ];
                            $res = ModelQueryes::INSERT($insert);
                        }
                    }
                }
            }
            REQUEST::RESPONDER(1, 200);;
        }
    }

    static public function EDITAR($data)
    {
        $fecha = fechalocal();
        //REQUEST::RESPONDER($data, 200);
        //EDITANDO DATOS DE ORDEN
        $update = [
            "table" => "ordenes_compra",
            "descripcion" => $data['descripcion'],
            "id_estado" => 1,
            "id_empresa" => $data['id_empresa'],
            "id_proveedor" => $data['id_proveedor'],
            "moneda" => $data['moneda'],
            "id_cuenta" => $data['id_cuenta'],
            "igv" => $data['igv'],
            "percepcion" => $data['percepcion'],
            "total_impuesto" => $data['total_impuesto'],
            "subtotal" => $data['subtotal'],
            "total" => $data['total'],

        ];
        $where = array("id" => $data["id"],);
        $uorden = ControllerQueryes::UPDATE($update, $where);

        ///actualziando detale de orden
        $detalle = $data["orden_detalle"] ? $data["orden_detalle"] : [];
        //elimina columna detalle insumo
        $del_i = $data['deleted'] ? $data['deleted'] : [];
        for ($i = 0; $i < count($del_i); $i++) {
            $res_d = ControllerQueryes::DELETE(["table" => "orden_detalle", "id" => $del_i[$i]['id'],]);
            $res_i = ControllerQueryes::DELETE(["table" => "orden_detalle_impuesto", "idorden_detalle" => $del_i[$i]['id'],]);
        }
        //actualiza primer detalle
        for ($j = 0; $j < count($detalle); $j++) {
            if ($detalle[$j]['id']) {
                $update = [
                    "table" => "orden_detalle",
                    "cantidad" => $detalle[$j]["cantidad"],
                    "impuesto" => $detalle[$j]['impuesto'],
                    "total_impuesto" => $detalle[$j]['total_impuesto'],
                    "precio" => $detalle[$j]["precio"],
                    "subtotal" => $detalle[$i]["subtotal"],
                    "total" => $detalle[$j]["total"],
                ];
                $where = ["id" => $detalle[$j]['id'],];
                $uorden_d = ControllerQueryes::UPDATE($update, $where);
                //TODO:CORREGIR SI ES QUE QUEDA
                if ($uorden_d > 0) {
                    $res_i = ControllerQueryes::DELETE(["table" => "orden_detalle_impuesto", "idorden_detalle" => $detalle[$j]['id'],]);
                    $d_imp = $detalle[$j]["detalle_impuesto"];
                    for ($d = 0; $d < count($d_imp); $d++) {
                        $insert = [
                            "table" => "orden_detalle_impuesto",
                            "idorden_detalle" => $detalle[$j]['id'],
                            "id_insumo" => $d_imp[$d]["id_insumo"],
                            "id_impuesto" => $d_imp[$d]["id_impuesto"],
                            "valor" => $d_imp[$d]["valor"],
                        ];
                        $res = ModelQueryes::INSERT($insert);
                    }
                }
            } else {
                $insert = [
                    "table" => "orden_detalle",
                    "idorden_compra" => $data["id"],
                    "id_insumo" => $detalle[$j]["id_insumo"],
                    "cantidad" => $detalle[$j]["cantidad"],
                    "impuesto" => $detalle[$j]['impuesto'],
                    "total_impuesto" => $detalle[$j]['total_impuesto'],
                    "precio" => $detalle[$j]["precio"],
                    "subtotal" => $detalle[$j]["subtotal"],
                    "total" => $detalle[$j]["total"],
                    "LASTID" => "YES"
                ];
                $lsdin = ModelQueryes::INSERT($insert);
                ///detalle impuestos insumo
                $d_imp = $detalle[$j]["detalle_impuesto"];

                if (isset($lsdin) && $lsdin) {
                    for ($d = 0; $d < count($d_imp); $d++) {
                        $insert = [
                            "table" => "orden_detalle_impuesto",
                            "idorden_detalle" => $lsdin,
                            "id_impuesto" => $d_imp[$d]["id"],
                            "valor" => $d_imp[$d]["valor"],
                        ];
                        $res = ModelQueryes::INSERT($insert);
                    }
                }
            }
        }
        REQUEST::RESPONDER(1, 200);
    }
}
