<?php

// include_once(dirname(__FILE__) . './../conexPDO.php');
// class SPModelQueryes{

//      static public function SPDetalleMovimiento($id){
//         try {
//             $stmt = Conexion::conectar()->prepare("CALL sp_select_detalle_movimiento(:id)");

//             $stmt->bindParam(":id", $id, PDO::PARAM_INT);

//             if ($stmt->execute()) {
//                 return $stmt->fetchAll();
//             } else {
//                 return 'error';
//             }

//             $stmt = null;
//         } catch (\Throwable $th) {
//             $throw = $th->getMessage();
//             return "inyeccion";
//         }
//      }

// }