<?php
class ControllerAdministracion
{

    static public function CtrListarBuscarProveedores($data)
    {

        $length = $data['length'];
        $search = $data['search'];
        $response = ModelAdministracion::MdlListarBuscarProveedores($length, $search);
        if (isset($response) && $response) {
            for ($i = 0; $i < count($response); $i++) {
                $id = $response[$i]['id'];
                //lista cuentas
                $select = array(
                    "*" => "*",
                );
                $tables = array("cuentas" => "",);
                $where = array("id_proveedor" => "=" . $id,);
                $cuenta = ModelQueryes::SELECT($select, $tables, $where);
                $response[$i]['cuentas'] = $cuenta;
                $response[$i]['del_cuenta'] = [];
                //lista insumos
                $select = [
                    "I.id AS id" => "",
                    "I.id_proveedor" => "",
                    "I.codigo" => "",
                    "I.nombre" => "",
                    "I.descripcion" => "",
                    "I.id_unidad_medida" => "",
                    "I.cantidad" => "",
                    "0 as total_impuesto" => "",
                    "I.precio" => "",
                    "U.nombre" => "um_nombre",
                    "U.descripcion" => "um_descripcion",
                    "U.valor" => "",
                    "I.id" => "id_insumo",
                ];
                $tables = [
                    "insumos_proveedor I" => "unidad_medida U",
                    "I.id_unidad_medida" => "U.id", #1-1
                ];
                $where = [
                    "I.id_proveedor" => "=" . $id,
                ];
                $res = ModelQueryes::SELECT($select, $tables, $where);
                $response[$i]['insumos'] = $res;
                $response[$i]['del_insumo'] = [];
            }
        }
        return $response;
    }
    ///


}
