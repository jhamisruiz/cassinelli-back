<?php
class ControllerPedidoList
{

    static public function SELECTALL($data)
    {
        $table = 'nv_pedidos'; #nombre de la tabla o vista

        $columns = "id,tipo_envio,cupon,nombre_cuadro,names,last_name,fullname,fulladdress,phone,images,document_number,
        direccion,referencia,metodo_pago,precio,codigo,fecha_registro,
        fecha_entrega,id_estado,estado,Departamento,Provincia,Distrito"; #culumnas de la tabla para mostrar

        $params = ['id', 'names', 'last_name', 'codigo', 'document_number', 'fecha_registro']; #columnas por las que se realizara la busqueda

        $response = ModelQueryes::SELECT_NV($table, $columns, $params, $data); #funcion para traer la data

        //$response = ModelPedidoList::nv_pedidos();
        for ($i = 0; $i < count($response); $i++) {
            $response[$i]['fulladdress'] = json_decode($response[$i]['fulladdress']);
            $response[$i]['metodo_pago'] = json_decode($response[$i]['metodo_pago']);
            //$response[$i]['images'] = json_decode('[' . $response[$i]['images'] . ']');
            $response[$i]['zip'] = APP_URL . "/" . "upload/" . $response[$i]['document_number'] . '/' . $response[$i]['codigo'] . '.zip';
            $images = ModelQueryes::SELECT(
                ["*" => "*"],
                ["imagenes" => ""],
                ["id_pedido" => "=" . $response[$i]['id'] . " AND opcion =0"]
            );
            $response[$i]['images'] = $images;
        }
        REQUEST::RESPONDER($response, 200);
    }
    //GET PEDIDO BY ID
    static public function GETPEDIDO($data)
    {
        $response = ModelPedidoList::GETPEDIDO($data);

        if (isset($response['photos'])) {
            $response['photos'] = json_decode('[' . $response['photos'] . ']');
        }

        if (isset($response['photos'])) {
            $photos = $response['photos'];
            for ($i = 0; $i < count($photos); $i++) {

                $photos[$i]->{'id'} = $photos[$i]->{'id'};
                $photos[$i]->{'file'} = $photos[$i]->{'url'};
                $str = (substr($photos[$i]->{'url'}, 0, 4) == 'http') ? '' : APP_URL . '/';

                $photos[$i]->{'url'} = $str . $photos[$i]->{'url'};
            }
        }
        $response['photos'] = $photos;
        REQUEST::RESPONDER($response, 200);
    }

    ////////////GUARDA PEDIDOS//////////////////////////////
    static public function CREAR($pedido)
    {
        // $data='';
        // echo json_encode($data);
        //return;
        $fecha = Date::Now();
        $imgData = [];
        $imgData = ($pedido['data']) ? $pedido['data'] : [];

        $pago = $pedido['detalle_pago'];
        $pago['descripcion'] = (!isset($pago['descripcion'])) ? null : $pago['descripcion'];
        $pago['payment_id'] = (!isset($pago['payment_id'])) ? 0 : $pago['payment_id'];
        if (
            count($imgData) &&
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["names"]) &&
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["last_name"]) &&
            isset($pedido["total"])
        ) {
            if (
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["phone"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["document_number"])
            ) {
                $additems = [];
                $additems = ($pedido['additems']) ? $pedido['additems'] : [];

                $length = count($imgData);
                // 1INSERTA EL PEDIDO
                $pedido['referencia'] = (!isset($pedido['referencia'])) ? null : $pedido['referencia'];
                $pedido['fecha_registro'] = $fecha;
                $pedido['fecha_entrega'] = $fecha;
                $response = ModelPedidoList::GUARDAR($pedido);
                ///procesa imageness SI SOLO SE GUARDA LOS DATOS DE PEDIDO
                //  2 GUARDA LAS IMAGENES

                if (isset($response['sms']) == 'OK') {
                    // crea las carpetas con numero de documento cliente
                    $path = dirname(__FILE__) . "/../../../upload/" . $pedido["document_number"];
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    // crea una segunda carpetas con nombre del pedido
                    $path_cod = dirname(__FILE__) . "/../../../upload/" . $pedido["document_number"] . "/" . $response['codigo'];
                    if (!file_exists($path_cod)) {
                        mkdir($path_cod, 0777, true);
                    }
                    // **********************array data imagenes**********************
                    $codigo = $response['codigo'];
                    $document_number = $pedido["document_number"];
                    $Folder_Name = 'upload/' . $pedido["document_number"] . "/" . $response['codigo'] . '/';
                    for ($i = 0; $i < $length; $i++) {
                        $Img_Name = "tls" . ($i + 1) . ".png";
                        $Image = $Folder_Name . $Img_Name;

                        /* guarda imagen editada */
                        if (isset($imgData[$i]['Croped']) && $imgData[$i]['Croped'] == true) {
                            $cropedImage = $imgData[$i]['crop'];
                            // Remover la parte de la cadena de texto que no necesitamos (data:image/png;base64,)
                            // y usar base64_decode para obtener la información binaria de la imagen
                            $base64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $cropedImage));

                            // Finalmente guarda la imágen en el directorio especificado y con la informacion dada
                            file_put_contents("./" . $Image, $base64);
                        }
                        if (isset($imgData[$i]['Croped']) && $imgData[$i]['Croped'] == false) {
                            $cropedImage = $imgData[$i]['data'];
                            // Remover la parte de la cadena de texto que no necesitamos (data:image/png;base64,)
                            // y usar base64_decode para obtener la información binaria de la imagen
                            $base64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $cropedImage));

                            // Finalmente guarda la imágen en el directorio especificado y con la informacion dada
                            file_put_contents("./" . $Image, $base64);
                        }
                        $insert = array(
                            "table" => "imagenes", "id_pedido" => $response['id_pedido'],
                            "nombre" => $Img_Name, "url_img" => $Image,
                        );
                        $respuesta = ControllerQueryes::INSERT($insert);
                        $respuesta = ($respuesta == "OK") ? "OK" : "error";
                    }
                    /// ************insert pago imagen***************************
                    if (isset($pago['pago_img'])) {
                        $newName = $response['codigo'] . '.png';
                        $Image = $Folder_Name . $newName;
                        //si viene imegen de comproobante
                        $pagoB64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $pago['pago_img']));
                        file_put_contents("./" . $Image, $pagoB64);
                        $insert = array(
                            "table" => "imagenes", "id_pedido" => $response['id_pedido'],
                            "nombre" => $newName, "url_img" => $Image, "opcion" => 1, "LASTID" => "YES",
                        );
                    } else {
                        //sin imgen de comprobante
                        $insert = array(
                            "table" => "imagenes", "id_pedido" => $response['id_pedido'],
                            "nombre" => 'sin comprobante', "url_img" => 'error/img/no-pay.svg',
                            "opcion" => 1, "LASTID" => "YES",
                        );
                    }
                    $respuesta = ControllerQueryes::INSERT($insert);

                    //NOTE: INSERTA ADICIONALES
                    for ($i = 0; $i < count($additems); $i++) {
                        if ($additems[$i]['quantity']) {
                            $add = array(
                                "table" => "imagenes",
                                "id_pedido" => $response['id_pedido'],
                                "nombre" => $additems[$i]['name'],
                                "cantidad" => $additems[$i]['quantity'],
                                "url_img" => $additems[$i]['img'],
                                "opcion" => 0, "LASTID" => "YES",
                            );
                            ControllerQueryes::INSERT($add);
                        }
                    }
                    //*********************METODO DE PAGO*********************
                    if ($respuesta > 0) {
                        $insert = array(
                            "table" => "metodo_pago",
                            "id_pedido" => $response['id_pedido'],
                            "id_imagen" => $respuesta,
                            "payment_id" => $pago['payment_id'],
                            "tipo" => $pago['tipo'], //FIXME: CAMBIAR CUNADO ESTE METODO DE PAGO
                            "monto" => $pedido['total'],
                            "descripcion" => $pago['descripcion'], //FIXME: CAMBIAR CUNADO ESTE METODO DE PAGO$pago['descripcion']
                        );
                        $respuesta = ControllerQueryes::INSERT($insert);
                        $respuesta = ($respuesta == "OK") ? 1 : 0;
                    }
                } else {
                    Errors::__Log('Algun dato en el formulario es incorrecto', 200);
                }

                //////////////zip//////////////
                $filezip = dirname(__FILE__) . '/../../../upload/' . $pedido["document_number"] . "/" . $response['codigo'] . ".zip";
                if (file_exists($filezip)) {
                    header('Content-Type: application/zip');
                    // delete file
                    unlink($filezip);
                }
                ////////////////// CREAR ZIP
                $pathdir = './upload/' . $pedido["document_number"] . "/" . $response['codigo'] . "/";

                // Enter the name to creating zipped directory
                $zipcreated = './upload/' . $pedido["document_number"] . "/" . $response['codigo'] . ".zip";

                // Create new zip class
                $zip = new ZipArchive;

                if ($zip->open($zipcreated, ZipArchive::CREATE) === TRUE) {
                    // Store the path into the variable
                    $dir = opendir($pathdir);

                    while ($file = readdir($dir)) {
                        if (is_file($pathdir . $file)) {
                            $zip->addFile($pathdir . $file, $file);
                        }
                    }
                    $zip->close();
                }
                //return $response;
                if ($response) {
                    if ($response) {
                        PHP::EMAIL([
                            'names' => $pedido["names"] . ' ' . $pedido["last_name"],
                            'email' => $pedido["email"],
                            'codigo' => $codigo,
                            'cuadros' => $length,
                            'document_number' => $pedido["document_number"],
                            'direccion' => $pedido['iddepartamento']['Departamento'] . ' ' .
                                $pedido['iddeprovincia']['Provincia'] . ' ' .
                                $pedido['iddistrito']['Distrito'] . ' - ' .
                                $pedido["direccion"],
                            'tipo_envio' => ($pedido['tipo_envio'] == 1) ? 'GRATIS' : 'EXPRES',
                        ]);
                    }
                    REQUEST::RESPONDER($response, 201);
                }
                Errors::__Log('No se registro el Pedido', 200);
            } else {
                Errors::__Log('El nro telefono  o nro Documento incorrectos. (*)', 200);
            }
        } else {
            Errors::__Log('Llena todos los campos obligatorios. (*)', 200);
        }
    }

    //******************UPDATE PEDIDOS***************
    static public function UPDATE($data)
    {
        ini_set('date.timezone', 'America/Lima');
        $fecha = date('Y-m-d H:i:s', time());

        ///decodificar array
        $pedido = json_decode($data['pedido'], true);
        $length = json_decode($data['cant_img'], true); // array.length si VIENE VACIO ES 0
        $pago = $pedido['detalle_pago'];
        $pago['descripcion'] = (!isset($pago['descripcion'])) ? null : $pago['descripcion'];
        //return $pedido;
        if (
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["names"]) &&
            preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["last_name"]) &&
            isset($pedido["precio"])
        ) {
            if (
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["phone"]) &&
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $pedido["document_number"])
            ) {
                // ********************1 ACTUALIZA EL PEDIDO

                $pedido['referencia'] = (!isset($pedido['referencia'])) ? null : $pedido['referencia'];
                //ACTUALIZA CIENTE
                $update = array(
                    "table" => "clientes", #nombre de tabla
                    "id_documento_type" => $pedido["document_tipe"], #nombre de columna y valor
                    "names" => $pedido["names"], #nombre de columna y valor
                    "last_name" => $pedido["last_name"], #nombre de columna y valor
                    "document_number" => $pedido["document_number"], #nombre de columna y valor
                    "email" => $pedido["email"], #nombre de columna y valor
                    "phone" => $pedido["phone"], #nombre de columna y valor
                    "id_ubigeo" => $pedido["id_ubigeo"], #nombre de columna y valor
                    "direccion" => $pedido["direccion"], #nombre de columna y valor
                    "referencia" => $pedido["referencia"], #nombre de columna y valor
                );
                $where = array("id" => $pedido["id_cliente"],);
                $response = ModelQueryes::UPDATE($update, $where);
                $response = ($response == 'OK') ? 'OK' : $response;
                //ACTUALIZA PEDIDO
                $update = array("table" => "pedidos", "precio" => $pedido["precio"], "tipo_envio" => $pedido["tipo_envio"]);
                $where = array("id" => $pedido["id"],);
                $response = ModelQueryes::UPDATE($update, $where);
                $response = ($response == 'OK') ? 'OK' : $response;

                // CREA IMAGENES NUEVAS IMAGENES
                # crea las carpetas
                $path = dirname(__FILE__) . "/../../../../upload/" . $pedido["document_number"];
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                // crea una segunda carpetas con nombre del pedido
                $path_cod = dirname(__FILE__) . "/../../../../upload/" . $pedido["document_number"] . "/" . $pedido['codigo'];
                if (!file_exists($path_cod)) {
                    mkdir($path_cod, 0777, true);
                }
                // **********************array data imagenes**********************
                $Folder_Name = 'upload/' . $pedido["document_number"] . "/" . $pedido['codigo'] . '/';
                for ($i = 0; $i < $length; $i++) {
                    $TmpName = $_FILES[$i]['tmp_name'];
                    $Img_Name = "tls" . ($i + 1) . ".png";
                    $Image = $Folder_Name . $Img_Name;
                    if (move_uploaded_file($TmpName, "../../../../../" . $Image)) {
                        $insert = array(
                            "table" => "imagenes", "id_pedido" => $pedido['id'],
                            "nombre" => $Img_Name, "url_img" => $Image,
                        );
                        $respuesta = ControllerQueryes::INSERT($insert);
                        $respuesta = ($respuesta == "OK") ? "OK" : "error";
                    }
                }
                // IMGEN DE COMPROBANTE PEDIDO
                if (isset($_FILES['pago_img']['tmp_name'])) {
                    $TmpName = $_FILES['pago_img']['tmp_name'];
                    $Img_Name = $_FILES['pago_img']['name'];
                    $newName = $pedido['codigo'] . '.png';
                    $Image = $Folder_Name . $newName;
                    /* eliminamos imagen */
                    $del = dirname(__FILE__) . "/../../../../" . $Image;
                    if (file_exists($del)) {
                        chmod($del, 0777);
                        unlink($del);
                    }
                    /* mueve nueva imagen */
                    if (move_uploaded_file($TmpName, "../../../../../" . $Image)) {
                        //si viene imegen de comproobante

                        $update = array("table" => "imagenes", "nombre" => $newName, "url_img" => $Image,);
                        $where = array("id" => $pedido["id_imegen_mp"],);
                        $response = ModelQueryes::UPDATE($update, $where);
                        $response = ($response == 'OK') ? 'OK' : $response;
                    }
                } else {
                    $update = array("table" => "imagenes", "nombre" => 'sin comprobante', "url_img" => 'img/no-pay.svg');
                    $where = array("id" => $pedido["id_imegen_mp"],);
                    $response = ModelQueryes::UPDATE($update, $where);
                    $response = ($response == 'OK') ? 'OK' : $response;
                }

                //ACTUALIZA METODO DE PAGO
                $update = array("table" => "metodo_pago", "monto" => $pedido["monto"], "descripcion" => $pedido['descripcion']);
                $where = array("id" => $pedido["id_metodo_pago"],);
                $response = ModelQueryes::UPDATE($update, $where);
                $response = ($response == 'OK') ? 'OK' : $response;

                //////////////zip//////////////
                $filezip = dirname(__FILE__) . '/../../../../upload/' . $pedido["document_number"] . "/" . $pedido['codigo'] . ".zip";
                if (file_exists($filezip)) {
                    header('Content-Type: application/zip');
                    // delete file
                    unlink($filezip);
                }
                ////////////////// CREAR ZIP
                $pathdir = './../../../../../upload/' . $pedido["document_number"] . "/" . $pedido['codigo'] . "/";

                // Enter the name to creating zipped directory
                $zipcreated = './../../../../../upload/' . $pedido["document_number"] . "/" . $pedido['codigo'] . ".zip";

                // Create new zip class
                $zip = new ZipArchive;

                if ($zip->open($zipcreated, ZipArchive::CREATE) === TRUE) {
                    // Store the path into the variable
                    $dir = opendir($pathdir);

                    while ($file = readdir($dir)) {
                        if (is_file($pathdir . $file)) {
                            $zip->addFile($pathdir . $file, $file);
                        }
                    }
                    $zip->close();
                }

                return ["sms" => "OK"];
            } else {
                return ["sms" => "#Error: El nro telefono  o nro Documento incorrectos. (*)"];
            }
        } else {
            return ["sms" => "#Error: Llena todos los campos obligatorios. (*)"];
        }
    }

    static public function DELETE($data)
    {
        $select = array(
            "C.document_number" => "",
            "P.codigo" => ''
        );
        $tables = array(
            "clientes C" => "pedidos P",
            "C.id" => "P.id_cliente", #1-1
        );
        $where = array(
            "P.id" => "=" . $data,
        );
        $document = ModelQueryes::SELECT($select, $tables, $where);
        $doc = isset($document[0]['document_number']) ? $document[0]['document_number'] : '';
        $codigo = isset($document[0]['codigo']) ? $document[0]['codigo'] : '';
        $file = dirname(__FILE__) . '/../../../upload/' . $doc . '/' . $codigo;

        $delete = array("table" => "imagenes", "id_pedido" => $data,);
        $res = ModelQueryes::DELETE($delete);
        $res = ($res == 'OK') ? 'ok' : 'error';

        if (file_exists($file)) {
            ControllerPedidoList::rmDir_rf($file);
        }

        $response = ModelPedidoList::DELETE($data);
        if ($response == 'OK') {
            REQUEST::RESPONDER(1, 201);
        }
        Errors::__Log('Error al eliminar pedido', 200);
    }

    static public function DELETEIMAGES($id, $f)
    {
        $delete = array("table" => "imagenes", "id" => $id);

        $response = ModelQueryes::DELETE($delete);
        if ($response == "OK") {
            $path = dirname(__FILE__) . "/../../../" . $f;
            chmod($path, 0777);
            if (unlink($path)) {
                REQUEST::RESPONDER(1, 201);
            };
        }

        Errors::__Log('Error al eliminar imagen', 200);
    }

    static public function FILES($data)
    {
        $data['desde'] = date('Y-m-d', strtotime($data['desde'])) . ' 01:00:00';
        $data['hasta'] = date('Y-m-d', strtotime($data['hasta'])) . ' 23:00:00';
        $response = ModelPedidoList::FILES($data);
        for ($i = 0; $i < count($response); $i++) {
            $nd = $response[$i]['fecha_entrega'];
            $nd = substr($nd, 0, -8);
            $response[$i]['fecha_entrega'] = $nd;
            $response[$i]['ubigeo'] = $response[$i]['Departamento'] . '-' . $response[$i]['Provincia'] . '-' . $response[$i]['Distrito'];
            $response[$i]['envio'] = ($response[$i]['tipo_envio'] == 1) ? 'Gratis' : 'Expres';
        }
        return $response;
    }

    static public function rmDir_rf($carpeta)
    {
        if ($carpeta . '.zip') {
            unlink($carpeta . '.zip');
        }

        foreach (glob($carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                ControllerPedidoList::rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }
}
