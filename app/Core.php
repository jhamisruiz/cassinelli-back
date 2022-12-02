<?php
class APP_DIRS
{
    static public  function GET_ALL_APP_DIRS($ruta, $import, $tipe)
    {
        $tipe_file = $tipe;
        // Se comprueba que realmente sea la ruta de un directorio
        if (is_dir($ruta)) {
            // Abre un gestor de directorios para la ruta indicada
            $gestor = opendir($ruta);

            // Recorre todos los elementos del directorio
            while (($archivo = readdir($gestor)) !== false) {

                $ruta_completa = $ruta . "/" . $archivo;

                // Se muestran todos los archivos y carpetas excepto "." y ".."
                if ($archivo != "." && $archivo != "..") {
                    // Si es un directorio se recorre recursivamente
                    if (is_dir($ruta_completa)) {
                        // echo "<li>" . $archivo . "</li>";
                        //echo "<li> Cuando muestra esta mrd</li>";//muestra el resto de archivos de las carpetas siguientes o hijas
                        APP_DIRS::GET_ALL_APP_DIRS($ruta_completa, $import, $tipe_file);
                    } else {
                        //print_r($archivo);
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
                        if ($tipe == 'js') {
                            echo '<script type="text/javascript" src="' . $ruta_completa . '"></script>';
                        }
                        if ($tipe == 'css') {
                            echo '<link rel="stylesheet" href="' . $ruta_completa . '">';
                        }
                    }
                }
            }

            // Cierra el gestor de directorios
            closedir($gestor);
        } else {
            //echo "No es una ruta de directorio valida<br/>";
            echo '<script>
            window.alert("Error: ruta o directorio no valida ' . $ruta . '");
            </script>';
        }
    }
    static public  function get_file($ruta, $import)
    {
        $extra = '.router.php';
        $ruta = $ruta . $extra;
        if (file_exists($ruta)) {
            switch ($import) {
                case 'require':
                    require $ruta;
                    break;
                case 'require_once':
                    require_once $ruta;
                    break;
                case 'include':
                    include $ruta;
                    break;
                case 'include_once':
                    include_once $ruta;
                    break;
                default:
                    echo 'no incluye archivos';
                    break;
            }
        } else {
            echo 'Archivo no encontrado';
        }
    }
}
