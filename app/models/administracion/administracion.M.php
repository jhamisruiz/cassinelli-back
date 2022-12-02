<?php
include_once(dirname(__FILE__) . './../conexPDO.php');//INCLUYE CONCECION DE BASE DE DATOS

class ModelAdministracion
{

    static public function MdlListarBuscarProveedores($length, $search)
    {
 
        try {
            $stmt = Conexion::conectar()
                ->prepare(" 
                    SELECT 
                        p.id,p.nombre,p.id_documento,t.nombre as nombre_documento,p.numero_documento,p.telefono,
                        p.email,p.direccion,p.fecha_registro,p.estado
                    FROM proveedor  p
                    INNER JOIN tipo_documento t
                    ON t.id=p.id_documento
                    WHERE p.nombre LIKE '%$search%' OR
                        t.nombre LIKE '%$search%' OR
                        p.telefono LIKE '%$search%' OR
                        p.numero_documento  LIKE '%$search%' OR
                        p.email LIKE '%$search%'
                    ORDER BY p.id DESC
                    LIMIT :num
                ");

            $stmt->bindParam("num", $length, PDO::PARAM_INT);//validar y formatear a numero

            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

        } catch (\Throwable $th) {
            $throw = $th->getMessage();
            return $throw;
        }
        
    }
}
