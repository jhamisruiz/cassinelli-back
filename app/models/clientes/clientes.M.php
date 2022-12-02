<?php

include_once(dirname(__FILE__) . './../conexPDO.php');
class ModelClientesList
{
    static public function SELECTALL($start, $length, $search)
    {
        try {
            $stmt = Conexion::conectar()
                ->prepare("select * from 
                        (SELECT c.eliminar, c.id,c.date_create,c.id_documento_type as document_tipe,
                        c.names,c.last_name,c.id_documento_type,c.document_number,c.email,c.phone,c.direccion,c.referencia,
                        d.description,u.id_ubigeo,u.Departamento,u.Provincia,u.Distrito,
                        json_object('direccion',c.direccion,'referencia',c.referencia,'departamento',u.Departamento,'provincia',u.Provincia,'distrito',u.Distrito) AS fulladdress                
                        FROM clientes c                    
                        INNER JOIN documents_type d on d.id= c.id_documento_type                    
                        INNER JOIN ubigeo u on u.id_ubigeo=c.id_ubigeo
                    ) n WHERE n.eliminar=0 ORDER BY n.id DESC
                ");
            $stmt->bindParam("num", $length, PDO::PARAM_INT);
            if ($stmt->execute()) {

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $th) {
            $throw = $th->getMessage();
            return $throw;
        }
    }

    static public function EXPORTFILE($desde, $hasta)
    {
        try {
            $stmt = Conexion::conectar()
                ->prepare("select n.id,n.names,n.last_name,n.email,n.phone,n.description,n.document_number,
                    n.date_create,n.Departamento,n.Provincia,n.Distrito,n.direccion,n.referencia,n.eliminar
                     from 
                        (SELECT c.eliminar, c.id,c.date_create,c.id_documento_type as document_tipe,
                        c.names,c.last_name,c.id_documento_type,c.document_number,c.email,c.phone,c.direccion,c.referencia,                    d.description,u.id_ubigeo,u.Departamento,u.Provincia,u.Distrito                
                        FROM clientes c                    
                        INNER JOIN documents_type d on d.id= c.id_documento_type                    
                        INNER JOIN ubigeo u on u.id_ubigeo=c.id_ubigeo                   
                        WHERE c.date_create BETWEEN '$desde' AND '$hasta'
                    ) n WHERE n.eliminar=0 ORDER BY n.id asc
                ");
            if ($stmt->execute()) {

                return $stmt->fetchAll();
            }
        } catch (\Throwable $th) {
            $throw = $th->getMessage();
            return $throw;
        }
    }
}