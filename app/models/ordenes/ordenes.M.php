<?php

include_once(dirname(__FILE__) . './../conexPDO.php');
class ModelOrdenes
{
    static public function SELECTALL($id, $length, $search)
    {
        $sql=($search)? ' O.id_estado = 7 or O.id_estado=8 AND ': '';
        try {
            $stmt = Conexion::conectar()
                ->prepare("SELECT O.id,O.codigo,O.descripcion,O.fecha_registro,O.fecha_update,O.id_cuenta,O.moneda,O.igv,O.percepcion,O.total_impuesto,O.subtotal,O.total,
                        O.id_empresa,E.razon_social,E.ruc,E.direccion,E.telefono,E.email,
                        O.id_proveedor,P.nombre as proveedor,T.nombre as documento_proveedor, P.numero_documento,P.telefono as telefono_proveedor,
                        P.email as email_proveedor,P.direccion as direccion_proveedor,
                        O.id_estado,S.nombre as nombre_estado,
                        (select C.numero from cuentas as C where C.id=O.id_cuenta) as numero_cuenta,
                        (select CA.tipo from cuentas as CA where CA.id=O.id_cuenta) as tipo_cuenta,
                        (select CB.banco from cuentas as CB where CB.id=O.id_cuenta) as banco
                    FROM ordenes_compra O
                    INNER JOIN empresa E on E.id=O.id_empresa
                    INNER JOIN proveedor P on P.id=O.id_proveedor
                    INNER JOIN tipo_documento T on T.id=P.id_documento
                    INNER JOIN estados S on S.id=O.id_estado
                    WHERE $sql O.id like '%$id%' ORDER BY O.id_estado ASC
                ");
            if ($stmt->execute()) {

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $th) {
            $throw = $th->getMessage();
            return $throw;
        }
    }
}
// codigo LIKE '%$search%' OR
//                         id_proveedor LIKE '%$search%' OR
//                         id_empresa  LIKE '%$search%' OR
//                         moneda LIKE '%$search%'