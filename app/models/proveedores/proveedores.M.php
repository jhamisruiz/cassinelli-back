<?php

include_once(dirname(__FILE__) . './../conexPDO.php');
class ModelProveedores
{

    static public function CREAR($data)
    {
        $conex = Conexion::conectar();
        try {
            $stmt = $conex->prepare("INSERT INTO usuarios(names,last_name,email,user_name,password,phone,fecha_registro)
                    values(:names,:last_name,:email,:user_name,:password,:phone,:fecha_registro)
                ");
            $stmt->bindParam(":names", $data["names"], PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $data["last_name"], PDO::PARAM_STR);
            $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
            $stmt->bindParam(":user_name", $data["user_name"], PDO::PARAM_STR);
            $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
            $stmt->bindParam(":phone", $data["phone"], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_registro", $data["fecha_registro"], PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $conex->lastInsertId();
            } else {
                Errors::__Log('Error al registrar Usuario.', 204);
            }
        } catch (\Throwable $th) {
            Errors::__Log('El usuario o email ya esta registrado.', 204);
        }
    }

    static public function GETPROVEEID($data)
    {
        $stmt = Conexion::conectar()
            ->prepare("SELECT *
                    FROM proveedor
                    WHERE id=:id
                ");
        $stmt->bindParam(":id", $data, PDO::PARAM_INT);
        if ($stmt->execute()) {

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    static public function ACTUALIZAR($data, $id)
    {
        try {
            $stmt = Conexion::conectar()
                ->prepare("UPDATE usuarios 
                SET names=:names,last_name=:last_name,email=:email,user_name=:user_name,phone=:phone
                WHERE id =:id
            ");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":names", $data["names"], PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $data["last_name"], PDO::PARAM_STR);
            $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
            $stmt->bindParam(":user_name", $data["user_name"], PDO::PARAM_STR);
            $stmt->bindParam(":phone", $data["phone"], PDO::PARAM_STR);
            if ($stmt->execute()) {
                return;
            } else {
                Errors::__Log('Error al actualizar Usuario.', 202);
            }
        } catch (\Throwable $th) {
            Errors::__Log('El usuario o email ya esta registrado.', 202);
        }
    }
    static public function PERMISOS($values, $id)
    {
        // try {
        //     $stmt = Conexion::conectar()
        //         ->prepare("DELETE FROM detalle_permisos WHERE id_usuario = $id");
        //     $stmt->execute();

        //     if ($values != '') {
        //         $stmt = null;
        //         $stmt = Conexion::conectar()
        //             ->prepare("INSERT INTO detalle_permisos (`id_usuario`, `id_permiso`) VALUES $values
        //         ");
        //         $stmt->execute();
        //     }
        //     return "Permisos Modificados";
        // } catch (\Throwable $th) {
        //     $throw = $th->getMessage();
        //     return $throw;
        // }
    }

    static public function FILES($data)
    {
        // $desde = $data['desde'];
        // $hasta = $data['hasta'];
        // try {
        //     $stmt = Conexion::conectar()
        //         ->prepare(
        //             "SELECT id,names,last_name,email,user_name,phone,fecha_registro,
        //                 REPLACE(REPLACE(estado,0,\"DESHABILITADO\"),1,\"HABILITADO\") as estado\n"
        //                 . "FROM usuarios
        //             WHERE fecha_registro BETWEEN '$desde' AND '$hasta'
        //             ORDER BY id ASC
        //         "
        //         );
        //     if ($stmt->execute()) {

        //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     }
        // } catch (\Throwable $th) {
        //     $throw = $th->getMessage();
        //     return $throw;
        // }
    }
}
