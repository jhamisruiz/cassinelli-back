<?php
include_once(dirname(__FILE__) . './../config/config.php');
class Conexion
{

    static public function validar()
    {

        try {
            $resp = Conexion::conectar();

            return  $resp;
        } catch (Throwable $th) {

            $sms = $th->getMessage();
            $error = array(
                "error" => "error",
                "sms" => $sms,
            );
            return $error;
        }
    }
    static public function conectar()
    {

        $link = new PDO(SGBD, DB_USER, DB_PASS); //Servidor,Usuario,Contraseña
        $link->setAttribute(PDO::ATTR_EMULATE_PREPARES, ATTR_EMULATE_PREPARES); //ATTR_EMULATE_PREPARES=true|false
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->exec("set names utf8");

        return  $link;
    }

    static public function tryConex()
    {
        $resp = Conexion::validar();
        try {
            if ($resp["error"] == "error") {
                return $resp;
            }
        } catch (Throwable $th) {
            $throw = $th->getMessage();
            return array(
                "con" => "ok",
                "error" => "",
                "smd" => "",
            );
        }
    }
}
