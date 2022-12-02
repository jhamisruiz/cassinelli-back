<?php
include_once(dirname(__FILE__) . '../../controllers/query/querys.C.php');
include_once(dirname(__FILE__) . '../../models/query/querys.M.php');
require_once __DIR__ . "../../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ReallySimpleJWT\Token;

//// EMAIL
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHP
{
  static public function EMAIL($user)
  {
    $mail = new PHPMailer(true);
    try {
      //Server settings
      $mail->SMTPDebug = 0;                    //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = EMAIL_HOST;                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = APP_EMAIL;                     //SMTP username
      $mail->Password   = EMAIL_PASSWORD;                               //SMTP password
      $mail->SMTPSecure = SMTPSecure;            //Enable implicit TLS encryption
      $mail->Port       = EMAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom(APP_EMAIL, 'Pedido ' . $user['codigo']);
      $mail->addAddress($user['email'], $user['names']);     //Add a recipient

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Tienes un pedido desde WOLPICK - Tipo de envio'. $user['tipo_envio'];
      $mail->Body    = meil::html($user);

      $mail->send();
      //REQUEST::RESPONDER('enviado...', 201);
    } catch (\Exception $e) {
      //REQUEST::RESPONDER($e, 201);
    }
  }
}
class Functions
{
  static public function HEADERS()
  {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    }
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 86400");
    header("Content-Type: application/json; charset=UTF-8");
    header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With,Origin, Accept");
    header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, PATCH");
  }

  static public function generaToken($data)
  {
    ///
    ini_set('date.timezone', 'America/Lima');
    $time = time();
    $_data = [
      "iat" => $time,
      "exp" => $time + (60 * 60 * HR_KEY_EXP * DD_KEY_EXP),
      "data" => [
        "id" => $data['id'],
        "email" => $data['email']
      ]
    ];
    $jwt = JWT::encode($_data, APP_KEY, 'HS512');

    return ["token" => $jwt, "data" => $_data, "sid" => APP_KEY];
  }

  static public function validaToken($request)
  {
    ini_set('date.timezone', 'America/Lima');
    $time = time();
    $_token = null;
    if (isset($_SERVER['Authorization'])) {
      $_token = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
      $_token = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['Authorization'])) {
        $_token = trim($requestHeaders['Authorization']);
      }
    }
    //echo 'ok';
    if (!empty($_token)) {
      if (preg_match('/Bearer\s(\S+)/', $_token, $matches)) {
        $_token = $matches[1];
        $data = JWT::decode($_token, new key(APP_KEY, 'HS512'));
        if (isset($data->{'error'})) {
          Functions::ERROR401();
        }
        $select = ['*' => '*'];
        $tables = ['token' => ''];
        $where = ['id_usuario=' => $data->{"data"}->{'id'}];
        $resp = ControllerQueryes::SELECT($select, $tables, $where);

        //SI NO HAY TOKEN
        if (!isset($resp[0])) {
          Functions::ERROR401();
        }
        if (isset($resp[0])) {
          if ($resp[0]['time_exp'] <= $time || $resp[0]['token'] != $_token) {
            Functions::ERROR403();
          }
          $_token = [
            "isValid" => true,
            "_data" => $data,
          ];
        }
      }
    } else {
      Functions::ERROR401();
    }
    return $_token;
  }

  static public function Alertify($alertify)
  {

    return '<script>
        alertify.' . $alertify['color'] . '("' . $alertify['sms'] . '");
        </script>';
  }

  static public function SwiftAlert($swift)
  {
    $form = "";
    if ($swift["rForm"] != "") {
      $form = "$('#" . $swift["rForm"] . "')[0].reset();";
    }
    return "<script>
        Swal.fire({
            position: 'center',
            icon: '" . $swift["icon"] . "',
            title: '" . $swift["sms"] . "',
            showConfirmButton: false,
            timer: 1500
        });" . $form . "
        </script>";
  }

  /**
   * NS: Missing token.
   * */
  static public function ERROR401()
  {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode("NS: Missing token");
    exit;
  }

  /**
   * NS: Token is expired / Forbidden authentication.
   * */
  static public function ERROR403()
  {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode("NS: Token is expired / Forbidden authentication");
    exit;
  }

  /**
   * Y-m-d H:i:s.
   * */
  static public function FECHA()
  {
    ini_set('date.timezone', 'America/Lima');
    return date('Y-m-d H:i:s', time());
  }
}
class Date
{
  /**
   * Y-m-d H:i:s.
   * */
  static public function Now()
  {
    ini_set('date.timezone', 'America/Lima');
    return date('Y-m-d H:i:s', time());
  }
}
class Deliver
{
  static public function ROUTER()
  {

    $REQUEST_URI = explode("?", $_SERVER['REQUEST_URI']);
    //--- /v1/usuarios/10
    $PATH_INFO = explode("/", $REQUEST_URI[0]);
    //echo json_encode($PATH_INFO);
    //parametros en la ruta maximo 5
    if (count($PATH_INFO) > 5) {

      Response::HttpResponse(400, false);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH' && count($PATH_INFO) < 5) {
      Response::HttpResponse(400, false);
    }
    //valida cuarta posicion habilita y deshabilita
    // if (isset($PATH_INFO[4]) && $_SERVER['REQUEST_METHOD'] == 'PATCH') {
    //     if (empty($PATH_INFO[4]) && $PATH_INFO[4] != 'habilitar' && $PATH_INFO[4] != 'deshabilitar') {
    //         Response::HttpResponse(400, false);
    //     }
    //     if ($PATH_INFO[4] == 'habilitar' || $PATH_INFO[4] == 'deshabilitar') {
    //         $_GET['habilitar'] = ($PATH_INFO[4] == 'habilitar') ? 1 : 0;
    //     }
    // }
    // solo letras en la ruta
    if (preg_match("/^[a-zA-Z-_-]+$/", $PATH_INFO[2]) == 0) {
      Response::HttpResponse(400, false);
    }
    $params = [];
    //extrae parametros adicionales
    if (isset($REQUEST_URI[1])) {
      $params = explode("&", $REQUEST_URI[1]);
      foreach ($params as $key => $value) {
        //$first = strtok($value, '=');
        $params[$key] = strtok($value, '=');
      }
    }
    if (isset($PATH_INFO[3]) && empty($PATH_INFO[3])) {
      //responde 
      Response::HttpResponse(400, false);
      exit;
    }
    $router = router;
    $arrMeth = [];
    $arrurl = [];
    $err = false;
    foreach ($router as $value) {
      array_push($arrurl, $value["url_pattern"]);
      // valida la ruta igual a la declarada en server
      if ('/' . $PATH_INFO[2] === $value['url_pattern']) {
        array_push($arrMeth, $value["method"]);
        if ($_SERVER['REQUEST_METHOD'] === $value["method"]) {

          //validar los parametros adicionales
          if (
            count(array_diff($params, $value['querystring_params'])) == 0 &&
            count(array_diff($value['querystring_params'], $params)) == 0
          ) {
            /// agrega el id al GET
            if (isset($PATH_INFO[3]) && !empty($PATH_INFO[3])) {
              $getids = explode("/", $value['endpoint']);
              //id 1
              preg_match_all("/{([A-Za-z]+?)}/", $getids[2], $id);
              if (isset($id[1][0])) {
                //echo json_encode($id);
                $_GET[$id[1][0]] = $PATH_INFO[3];
              }
              //id 2
              if (isset($PATH_INFO[4])) {
                preg_match_all("/{([A-Za-z]+?)}/", $getids[3], $id2);
                if (isset($id2[1][0])) {
                  $_GET[$id2[1][0]] = $PATH_INFO[4];
                }
              }
            }

            /* ==== API REQUEST===   */
            $res = json_decode(file_get_contents('php://input'), true);
            $_REQUEST['REQUEST_ARRAY_DATA'] = $res;
            $_REQUEST['REQUEST_OBJECT_DATA'] = json_decode(file_get_contents('php://input'));
            $obj = file_get_contents('php://input');

            if (!empty($obj)) {
              if (!is_array($res)) {
                Response::HttpResponse(406, false);
                exit;
              }
            }

            return (object)$value;
          } else {
            $err = true;
            next($router);
          }
        }
      }
    }
    return (object)["rutas" => $arrurl, "metodos" => $arrMeth, "ruta" => $PATH_INFO[2], 'err' => $err];
  }
}

class Response
{
  /**
   *@param number  $code codigo de error 200 | 400 | 500
   *@param bool  $iserror > true | false
   *@false si no es control de rutas
   */
  static public function HttpResponse($code, $iserror = true, $err = null)
  {

    //echo json_encode(Deliver::ROUTER()->{'method'});
    //echo json_encode(in_array(strtoupper($_SERVER['REQUEST_METHOD']), Deliver::ROUTER()->{'method'}));
    if ($code) {
      switch ($code) {
        case 100:
          $text = 'Continue';
          break;
        case 101:
          $text = 'Switching Protocols';
          break;
        case 200:
          $text = 'OK';
          break;
        case 201:
          $text = 'Created';
          break;
        case 202:
          $text = 'Accepted';
          break;
        case 203:
          $text = 'Non-Authoritative Information';
          break;
        case 204:
          $text = 'No Content';
          break;
        case 205:
          $text = 'Reset Content';
          break;
        case 206:
          $text = 'Partial Content';
          break;
        case 300:
          $text = 'Multiple Choices';
          break;
        case 301:
          $text = 'Moved Permanently';
          break;
        case 302:
          $text = 'Moved Temporarily';
          break;
        case 303:
          $text = 'See Other';
          break;
        case 304:
          $text = 'Not Modified';
          break;
        case 305:
          $text = 'Use Proxy';
          break;
        case 400:
          $text = 'Bad Request';
          break;
        case 401:
          $text = 'Unauthorized';
          break;
        case 402:
          $text = 'Payment Required';
          break;
        case 403:
          $text = 'Forbidden';
          break;
        case 404:
          $text = 'Not Found';
          break;
        case 405:
          $text = 'Method Not Allowed';
          break;
        case 406:
          $text = 'Not Acceptable';
          break;
        case 407:
          $text = 'Proxy Authentication Required';
          break;
        case 408:
          $text = 'Request Time-out';
          break;
        case 409:
          $text = 'Conflict';
          break;
        case 410:
          $text = 'Gone';
          break;
        case 411:
          $text = 'Length Required';
          break;
        case 412:
          $text = 'Precondition Failed';
          break;
        case 413:
          $text = 'Request Entity Too Large';
          break;
        case 414:
          $text = 'Request-URI Too Large';
          break;
        case 415:
          $text = 'Unsupported Media Type';
          break;
        case 500:
          $text = 'Internal Server Error';
          break;
        case 501:
          $text = 'Not Implemented';
          break;
        case 502:
          $text = 'Bad Gateway';
          break;
        case 503:
          $text = 'Service Unavailable';
          break;
        case 504:
          $text = 'Gateway Time-out';
          break;
        case 505:
          $text = 'HTTP Version not supported';
          break;
        default:
          exit('Unknown http status code "' . htmlentities('' . $code) . '"');
          break;
      }

      $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

      if ($code >= 300 && $iserror) {
        header($protocol . ' ' . $code . ' ' . $text);

        echo json_encode($text);
        exit;
      }

      if (!$iserror) {
        $nsms = $text;
        if ($err) {
          $nsms = $err;
        }
        header($protocol . ' ' . $code . ' ' . $text);
        echo json_encode($nsms);
        exit;
      }
      exit;
    }
  }
}

class Errors
{
  /**
   * @param string  mesanje
   * 
   * @return array []
   * 
   * @info
   * Code 100: 'Continue'
   *|
   * Code 101:
   *'Switching Protocols'
   *|
   * Code 200:
   *'OK'
   *|
   * Code 201:
   *'Created'
   *|
   * Code 202:
   *'Accepted'
   *|
   * Code 203:
   *'Non-Authoritative Information'
   *|
   * Code 204:
   *'No Content'
   *|
   * Code 205:
   *'Reset Content'
   *|
   * Code 206:
   *'Partial Content'
   *|
   * Code 300:
   *'Multiple Choices'
   *|
   * Code 301:
   *'Moved Permanently'
   *|
   * Code 302:
   *'Moved Temporarily'
   *|
   * Code 303:
   *'See Other'
   *|
   * Code 304:
   *'Not Modified'
   *|
   * Code 305:
   *'Use Proxy'
   *|
   * Code 400:
   *'Bad Request'
   *|
   * Code 401:
   *'Unauthorized'
   *|
   * Code 402:
   *'Payment Required'
   *|
   * Code 403:
   *'Forbidden'
   *|
   * Code 404:
   *'Not Found'
   *|
   * Code 405:
   *'Method Not Allowed'
   *|
   * Code 406:
   *'Not Acceptable'
   *|
   * Code 407:
   *'Proxy Authentication Required'
   *|
   * Code 408:
   *'Request Time-out'
   *|
   * Code 409:
   *'Conflict'
   *|
   * Code 410:
   *'Gone'
   *|
   * Code 411:
   *'Length Required'
   *|
   * Code 412:
   *'Precondition Failed'
   *|
   * Code 413:
   *'Request Entity Too Large'
   *|
   * Code 414:
   *'Request-URI Too Large'
   *|
   * Code 415:
   *'Unsupported Media Type'
   *|
   * Code 500:
   *'Internal Server Error'
   *|
   * Code 501:
   *'Not Implemented'
   *|
   * Code 502:
   *'Bad Gateway'
   *|
   * Code 503:
   *'Service Unavailable'
   *|
   * Code 504:
   *'Gateway Time-out'
   *|
   * Code 505:'HTTP Version not supported'
   */
  static public function BabRequest($err)
  {
    $REQUEST_URI = explode("?", $_SERVER['REQUEST_URI']);
    return [
      "error" => "Bad Request",
      "code" => 2001,
      "message" => "(#2001)" . $err,
      "statusCode" => 400,
      "timestamp" => Functions::FECHA(),
      "path" => $REQUEST_URI[0],
      "debug" => "(#2001)" . $err
    ];
  }
  /**
   * @param string  mesanje
   * @param number  codigo de error 100.. | 200... | 300... | 400... | 500...
   * 
   * @return void|null
   * 
   */
  static public function __Log($sms, $code = null)
  {
    $REQUEST_URI = explode("?", $_SERVER['REQUEST_URI']);
    $arrr = [
      "error" => "Bad Request",
      "code" => 2001,
      "message" => "(#2001)" . $sms,
      "statusCode" => 400,
      "timestamp" => Functions::FECHA(),
      "path" => $REQUEST_URI[0],
      "debug" => "(#2001)" . $sms
    ];
    $cod = 400;
    if ($code) {
      $cod = $code;
    }
    Response::HttpResponse($cod, false, $arrr);
    exit;
  }
}

class REQUEST
{
  /**
   * Envia la respuesta obtenida a la solicitud
   * @param any $data array | string | null 
   * @param number code 200 | 201 | 202 | null  -> defaul code 200
   * @return void
   */
  static public function RESPONDER($data = null, $code = null)
  {
    if (!$data) {
      echo 'null';
    }
    if ($code > 200 && $code < 203) {
      switch ($code) {
        case 201:
          $text = 'Created';
          break;
        case 202:
          $text = 'Accepted';
          break;
        default:
          exit('error');
          break;
      }
    }

    if (!$code || $code == 200) {
      header("HTTP/1.1 200 OK");
      if ($data == null) {
        exit;
      }
      echo json_encode($data);
      exit;
    }
    header('HTTP/1.1' . ' ' . $code . ' ' . $text);
    Functions::HEADERS();
    echo json_encode($data);
    exit;
  }
}

class IMPORT
{
  /**
   * @param string $base controlers | models
   * @param string $folder nombre de la carpeta del modulo
   */
  static public function CONTROLERS($base, $folder, $import = 'require_once', $tipe = 'php')
  {
    $folder = '../../../app/' . $base . '/' . $folder;
    $tipe_file = $tipe;
    // Se comprueba que realmente sea la ruta de un directorio
    if (is_dir($folder)) {
      // Abre un gestor de directorios para la ruta indicada
      $gestor = opendir($folder);

      // Recorre todos los elementos del directorio
      while (($archivo = readdir($gestor)) !== false) {

        $ruta_completa = $folder . "/" . $archivo;

        // Se muestran todos los archivos y carpetas excepto "." y ".."
        if ($archivo != "." && $archivo != "..") {
          // Si es un directorio se recorre recursivamente
          if (is_dir($ruta_completa)) {
            // echo "<li>" . $archivo . "</li>";
            //echo "<li> Cuando muestra esta mrd</li>";//muestra el resto de archivos de las carpetas siguientes o hijas
            IMPORT::CONTROLERS($ruta_completa, $import, $tipe_file);
          } else {
            if ($tipe == 'php') {
              switch ($import) {
                case 'require':
                  require $ruta_completa;
                  break;
                case 'require_once':
                  require_once $ruta_completa;
                  break;
                case 'include':
                  include $ruta_completa;
                  break;
                case 'include_once':
                  include_once $ruta_completa;
                  break;
                default:
                  echo 'no incluye archivos';
                  break;
              }
            }
          }
        }
      }

      // Cierra el gestor de directorios
      closedir($gestor);
    }
  }
}

class meil
{
  static public function html($user)
  {
    return '
        <html>
<head>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Wolpic - gracias por tu pago</title>
  <style type="text/css">
    #outlook a {
      padding: 0;
    }
    body {
      width: 100% !important;
      min-width: 100%;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      margin: 0 auto;
      padding: 0;
      background-color: #ffffff;
      -webkit-font-smoothing: antialiased;
    }
    table {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }
    table.pagebody {
      height: auto;
      width: 100%;
    }
    table[class="liquid"],
    td[class="liquid"] {
      width: 100% !important;
      clear: both !important;
      min-width: 100% !important;
    }
    table.container table.row {
      display: block;
    }
    td.wrapper {
      padding: 10px 0px 0px 0px;
      position: relative;
    }
    table.columns {
      margin: 0 auto;
    }
    table.docerow {
      width: 600px;
    }
    .panel {
      background: #f2f2f2 !important;
      border: 1px solid #d9d9d9 !important;
      padding: 10px !important;
    }
    div,
    p,
    a,
    i,
    td {
      -webkit-text-size-adjust: none;
      margin: 0;
      padding: 0;
    }

    .img-inline {
      display: inline-block !important;
      float: none !important;
    }

    .ExternalClass table[class="ecxpagebody"] .ecxcontainer {
      width: 600px !important;
    }

    .ExternalClass table.ecxfixie9 {
      width: 600px !important;
      height: auto;
      margin: 0 auto !important;
      margin: 0 auto !important;
    }

    @media screen and (max-width: 480px) {
      table.fullwidth {
        width: 100% !important;
        float: none !important;
        table-layout: fixed;
      }

      td.spacercinco {
        width: 5% !important;
      }

      table.fullwidth .tablas-responsive {
        width: 80% !important;
      }

      table.fullwidth .tablas-responsive table,
      table.fullwidth .tablas-responsive table td {
        width: 75% !important;
      }

      table.fullwidth .tablas-responsive table td .img-responsive {
        width: 100% !important;
      }

      table.fullwidth .tablas-responsive table td .btn-responsive {
        width: 60% !important;
      }

      table.fullwidth .tablas-responsive table td p.letter {
        font-size: 1.2em;
        line-height: 20px;
      }

      table.columns .containermid td p.letter-letter-big {
        font-size: 16px;
        line-height: 22px;
      }

      table.columns .containermid td p.letter-small {
        font-size: 14px;
        line-height: 20px;
      }
    }
  </style>
</head>
<body>
  <table class="pagebody" width="100%">
    <tr>
      <td class="centermobile fixie9" align="center" valign="top" width="100%" style="margin: 0 auto !important;">
        <center>
          <table class="container" width="600" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto !important;background-color:white;">
            <tr>
              <td width="600" valign="top" class="fullwidth">
                <img src="' . APP_URL . '/email/header-wolpic.png" width="90%" style="padding-left: 32px; padding-right: 32px;" alt="">
              </td>
            </tr> <!-- Begin body -->
            <tr>
              <td align="center" valign="top" style="padding-left: 32px; padding-right: 32px;">
                <table width="100%" class="" align="center" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="600" valign="top" class="fullwidth">
                      <img src="' . APP_URL . '/email/welcome-wolpic.png" width="100%" alt="">
                    </td>
                  </tr>
                  <tr>
                    <td align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 24px; color: #67666b; padding-top: 34px; padding-left: 32px; padding-right: 32px; padding-bottom: 22px;">
                      Hola ' . $user['names'] . ',</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table class="container" width="550" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto !important;background-color:white;">
                  <tr>
                    <td align="justify" valign="top" style=" padding-top: 5px; padding-left: 15px; padding-right: 15px; padding-bottom: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 20px; color: #5b5b5f; text-align: justify;">
                      Queremos informarte que tu pedido N° ' . $user['codigo'] . ' fue confirmado satisfactoriamente. Realiza el
                      seguimiento de tu pedido a través de nuestro canal de atención haciendo clic <a target="_blank" href="https://wa.link/oe2iuf"> aquí </a></td>
                  </tr>
                  <tr>
                  <tr>
                    <td width="600" valign="top" class="fullwidth">
                      <img src="' . APP_URL . '/email/tracking-wolpic.png" width="600" alt="">
                    </td>
                  </tr>
            </tr>
          </table>
      </td>
    </tr>
    <tr>
      <td>
        <table class="container" width="550" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto !important;background-color:white;">
          <tr>
            <td style="display: flex; justify-content: center; align-items: center; margin: 2rem 0;">
              <a style=" background-color: #006ADB; color: #fff; display: inline-block; padding:1rem; border-radius: 8px; text-decoration: none; font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 20px; text-align: justify;" target="_blank" href="https://wa.link/oe2iuf">Seguimiento</a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top" style="padding-top: 0px; padding-left: 37px; padding-right: 37px; padding-bottom: 20px; background-color: #ffffff;">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #006ADB;">
          <tr>
            <td align="center" valign="top" style="background-color: #006ADB;padding-right:15px;padding-left:15px;padding-top:15px;padding-bottom:15px;">
              <table>
                <tr>
                  <td><img src="https://cdn.agilitycms.com/scotiabank-peru/Profuturo/mailing/2022/nuevo-retiro/arrow.png" border="0" style="vertical-align: top;padding-right:15px;"></td>
                  <td>
                    <p style="font-family: Arial, Helvetica, sans-serif;font-weight:600;font-size: 14px; color: white;line-height:20px;text-transform: uppercase;">
                      DETALLE DE SU PEDIDO REGISTRADO EN WOLPIC </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table width="60%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: flex; margin-top: 2rem; margin-bottom: 0.8rem; font-family: Arial, Helvetica, sans-serif; font-weight: 600; ">
                  <td style="width: 50%;">
                    <p>CÓDIGO PEDIDO: </p>
                  </td>
                  <td style="width: 50%;">
                    <p style="color: #5b5b5f"> ' . $user['codigo'] . '</p>
                  </td>
                </tr>
                <tr style="display: flex; margin-bottom: 0.8rem; font-family: Arial, Helvetica, sans-serif; font-weight: 600; ">
                  <td style="width: 50%;">
                    <p>N° CUADROS: </p>
                  </td>
                  <td style="width: 50%;">
                    <p style="color: #5b5b5f"> ' . $user['cuadros'] . '</p>
                  </td>
                </tr>
                <tr style="display: flex; margin-bottom: 0.8rem; font-family: Arial, Helvetica, sans-serif; font-weight: 600; ">
                  <td style="width: 50%;">
                    <p>DOCUMENTO: </p>
                  </td>
                  <td style="width: 50%;">
                    <p style="color: #5b5b5f"> ' . $user['document_number'] . '</p>
                  </td>
                </tr>
                <tr style="display: flex; margin-bottom: 0.8rem; font-family: Arial, Helvetica, sans-serif; font-weight: 600; ">
                  <td style="width: 50%;">
                    <p>DIRECCIÓN: </p>
                  </td>
                  <td style="width: 50%;">
                    <p style="color: #5b5b5f"> ' . $user['direccion'] . '</p>
                  </td>
                </tr>
                <tr style="display: flex; margin-bottom: 0.8rem; font-family: Arial, Helvetica, sans-serif; font-weight: 600; ">
                  <td style="width: 50%;">
                    <p>DELIVERY: </p>
                  </td>
                  <td style="width: 50%;">
                    <p style="color: #5b5b5f"> ' . $user['tipo_envio'] . '</p>
                  </td>
                </tr>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  </td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr> <!-- End Body mailing -->
  <!-- Begin Footer -->
  <tr>
    <td>
      <table width="100%" class="row" border="0" cellspacing="0" cellpadding="0" bgcolor="#006ADB">
        <tr>
          <td align="center" width="600">
            <table width="490" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="490" class="fullwidth">
                  <table width="490" class="fullwidth" border="0" cellspacing="0" cellpadding="0" style=" width:100%!important; ">
                    <tr>
                      <td>
                        <img src="' . APP_URL . '/email/header-wolpic.png" alt="">
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  </table>
  </center>
  </td>
  </tr>
  </table>
</body>
</html>
        ';
  }
}
