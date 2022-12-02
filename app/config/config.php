<?php
// include_once(dirname(__FILE__) . "../../../vendor/autoload.php");

// use Firebase\JWT\JWT;

class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;


    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load(): void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
(new DotEnv(__DIR__ . '/../../.env'))->load();

/* ============================================================
`//////////////////////////- config hos_name -////////////
===============================================================*/
$parts = array(
    "www"   =>  getenv('APP_URL'),
    "src"   =>  "",
    "logo"   =>  "",
    "_file" =>  getenv('_FILE'),
);

if (substr($parts["www"], -1) == "/") {
    $host = substr($parts["www"], 0, -1);
} else {
    $host = $parts["www"];
}

$FRONT_URL = getenv('FRONT_URL');

if (substr($FRONT_URL, -1) == "/") {
    $FRONT_URL = substr($FRONT_URL, 0, -1);
} else {
    $FRONT_URL = $FRONT_URL;
}
/* ============================================================
`//////////////////////////- config data base -////////////
===============================================================*/
define("ATTR_EMULATE_PREPARES", getenv('ATTR_EMULATE_PREPARES')); //

$DATABSE = array(
    "HOST"    => getenv('DB_HOST'),
    "DB_NAME" => getenv('DB_DATABASE'),
    "DB_USER" => getenv('DB_USERNAME'),
    "DB_PASS" => getenv('DB_PASSWORD'),
    "PORT"    => getenv('DB_PORT'),
);
/* ////////////////////////////////////////////////////////// */
define("API_V", getenv('API_V')); //

define("APP_KEY", getenv('APP_KEY')); //
define("APP_ID", getenv('APP_ID')); //
//EMAIL
define("SMTPSecure", getenv('SMTPSecure')); //
define("EMAIL_HOST", getenv('EMAIL_HOST')); //
define("APP_EMAIL", getenv('APP_EMAIL')); //
define("EMAIL_PASSWORD", getenv('EMAIL_PASSWORD')); //
define("EMAIL_PORT", getenv('EMAIL_PORT')); //

define("HR_KEY_EXP", getenv('HR_KEY_EXP')); //
define("DD_KEY_EXP", getenv('DD_KEY_EXP')); //
define("MP_ACCESS_TOKEN", getenv('MP_ACCESS_TOKEN')); //
define("MP_PUBLIC_KEY", getenv('MP_PUBLIC_KEY')); //

define("FRONT_URL", $FRONT_URL);
define("APP_URL", $host);
define("FOLDER_URL_IMG_ALMACEN", $parts["_file"]);
define("COD_ORDEN", getenv('COD_ORDEN')); //CODIGO DE ORDEN

/* ////////////////////////////////////////////////////////// */

define("HOST", $DATABSE["HOST"]);
define("DB_NAME", $DATABSE["DB_NAME"]);
define("DB_USER", $DATABSE["DB_USER"]);
define("DB_PASS", $DATABSE["DB_PASS"]);
define("PORT", $DATABSE["PORT"]);

$sgbd = "mysql:host=" . HOST . ";dbname=" . DB_NAME;

define("SGBD", $sgbd);

///
$time = time();
$token = [
    "iat" => $time,
    "exp" => $time + (60 * 60 * 24),
    "data" => [
        "id" => APP_ID,
        "email" => APP_EMAIL
    ]
];
// $jwt = JWT::encode($token, APP_KEY, 'HS256');
// define("APP_TOKEN", $jwt);

include_once(dirname(__FILE__) . "../../../server.php");
define("router", $router);
