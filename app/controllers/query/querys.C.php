<?php
class ControllerQueryes{
    /* ================================================================
    QUERY   ROWCOUNT
    ================================================================= */
    /**
     *@param string $table = "table_name"; nombre de la tabla

     * @param string $where = "id=10" string query
     * @return array<mixed> { "row": 1}
     **/
    static public function ROWCOUNT($table, $where)
    {
        $row=0;
        $respuesta = ModelQueryes::ROWCOUNT($table, $where);
        if (isset($respuesta['row'])){
            $row = $respuesta['row'];
        }
        return $row;
    }
/* ================================================================
    QUERY SELECT
================================================================= */
    static public function SELECT($select, $tables, $where){

        $respuesta= ModelQueryes::SELECT($select, $tables, $where);
        return $respuesta;
    }
    /* ================================================================
    QUERY INSERT
================================================================= */
    /**
     *@param array[] 
     * $insert =[
     *     "table"=>"table_name", 
     *     "row_name" => "value",
     *      "LASTID" => "YES"
     * ];
     *@return string<"OK">  retorna ok si no se pide el LASTID
     *@return int<0|1> if add "LASTID" => "YES" retorna el ultimo id insertado,last_insert_id 
     **/
    static public function INSERT($insert){

        $respuesta = ModelQueryes::INSERT($insert);
        return $respuesta;
    }
    /* ================================================================
    QUERY UPDATE
================================================================= */
    /**
     * @param $update = array(
     *    "table" => "usuarios", #nombre de tabla
     *    "valor" => $data["valor"], #nombre de columna y valor
     *    #"columna"=>"valor",#nombre de columna y valor
     *);
     *@param $where = array(
     *    "id" => $data["id"], #condifion columna y valor
     *);
     */
    static public function UPDATE($update,$where)
    {
        //TODO:controllar datos      
        $respuesta = ModelQueryes::UPDATE($update,$where);
        return $respuesta;
    }
    /* ================================================================
    QUERY DELETE
================================================================= */
    /**
     * @param $delete=array(
     *               "table"=>"personas",
     *               "id" => $val
     *); */
    static public function DELETE($delete)
    {

        $respuesta = ModelQueryes::DELETE($delete);
        return $respuesta;
    }
}