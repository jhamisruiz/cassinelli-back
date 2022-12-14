<?php
class StockController
{

    static public function SELECTALL($data)
    {
        $table = 'insumos_proveedor'; #nombre de la tabla o vista

        $columns = "id,id_proveedor,codigo,nombre,descripcion,id_unidad_medida,cantidad,precio,estado"; #culumnas de la tabla para mostrar

        $params = ['id']; #columnas por las que se realizara la busqueda

        $stock = ModelQueryes::SELECT_NV(
            $table,
            $columns,
            $params,
            $data,
        ); #funcion para traer la data

        for ($i=0; $i < count($stock); $i++) {
            $stock[$i]['cantidad']=rand(0, 1000);
        }

        REQUEST::RESPONDER($stock, 200);
    }
}
