<?php

class ControllerUbigeo
{
    static public function UBIGEO($data)
    {
        if (isset($data['Departamento'])) {
            $select = array(
                "id_ubigeo" => "",
                "Departamento" => "",
                "ORDERBY" => ["ASC" => "Departamento"],
            );
            $tables = array(
                "ubigeo" => "", //solo si no hay inner joins..
            );
            $where = array(
                "SUBSTRING(`id_ubigeo`,3,4)" => "='0000'"
            );;

            $response = ModelQueryes::SELECT($select, $tables, $where);
        }
        if (isset($data['Provincia'])) {
            $select = array(
                "id_ubigeo" => "",
                "Provincia" => "",
                "ORDERBY" => ["ASC" => "Provincia"],
            );
            $tables = array(
                "ubigeo" => "", //solo si no hay inner joins..
            );
            $prov = $data["Provincia"];
            $where = array(
                "SUBSTRING(`id_ubigeo`,5,2)" => "='00'",
                "SUBSTRING(`id_ubigeo`,3,4)" => "<>'0000'",
                "SUBSTRING(`id_ubigeo`,1,2)" => "=SUBSTRING('$prov',1,2)"
            );
            $response = ModelQueryes::SELECT($select, $tables, $where);
        }
        if (isset($data['Distrito'])) {
            $select = array(
                "id_ubigeo" => "",
                "Distrito" => "",
                "ORDERBY" => ["ASC" => "Distrito"],
            );
            $tables = array(
                "ubigeo" => "", //solo si no hay inner joins..
            );
            $dist = $data['Distrito'];
            $where = array(
                "SUBSTRING(`id_ubigeo`,5,2)" => "<>'00'",
                "SUBSTRING(`id_ubigeo`,1,4)" => "=SUBSTRING('$dist',1,4)"
            );;

            $response = ModelQueryes::SELECT($select, $tables, $where);
        }

        if ($response[0] != 'S') {
            for ($i = 0; $i < COUNT($response); $i++) {
                unset($response[$i][0]);
                unset($response[$i][1]);
            }
        }
        REQUEST::RESPONDER($response, 200);
    }
}
