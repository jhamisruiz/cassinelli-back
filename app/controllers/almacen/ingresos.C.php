<?php
class IngresosController
{

    static public function SELECTALL($data)
    {
        $table = 'nv_orden_compra'; #nombre de la tabla o vista

        $columns = "banco, codigo,descripcion,direccion,direccion_proveedor,documento_proveedor,email,email_proveedor,
        fecha_registro,id,id_cuenta,id_empresa,id_estado,id_proveedor,igv,moneda,nombre_estado,codigo_estado,flujo,
        numero_documento,percepcion,proveedor,razon_social,ruc,subtotal,telefono,telefono_proveedor,tipo_cuenta,total,total_impuesto
        "; #culumnas de la tabla para mostrar

        $params = ['id', 'codigo', 'fecha_registro', 'proveedor', 'moneda']; #columnas por las que se realizara la busqueda

        $orden = ModelQueryes::SELECT_NV(
            $table,
            $columns,
            $params,
            $data,
            $value = 'id_estado IN (7,8)'
        ); #funcion para traer la data
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
}
