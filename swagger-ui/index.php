<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="<?= APP_URL ?>/swagger-ui/dist/swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="<?= APP_URL ?>/swagger-ui/dist/index.css" />
    <link rel="icon" type="image/png" href="<?= APP_URL ?>/swagger-ui/dist/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= APP_URL ?>/swagger-ui/dist/favicon-16x16.png" sizes="16x16" />
    <link href="https://static1.smartbear.co/swagger/media/assets/swagger_fav.png" type="image/png" rel="shortcut icon" />
    <link href="https://static1.smartbear.co/swagger/media/assets/swagger_fav.png" type="image/png" rel="icon" />
</head>

<body>
    <div id="swagger-ui"></div>
    <script src="<?= APP_URL ?>/swagger-ui/dist/swagger-ui-bundle.js" charset="UTF-8"> </script>
    <script src="<?= APP_URL ?>/swagger-ui/dist/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
    <?php
    $RR_URI = explode("/", $_SERVER['REQUEST_URI']);
    ?>
    <script charset="UTF-8">
        window.onload = function() {
            //<editor-fold desc="Changeable Configuration Block">

            // the following lines will be replaced by docker/configurator, when it runs in a docker-container
            window.ui = SwaggerUIBundle({
                url: "http://<?= $_SERVER['SERVER_NAME'] ?>:<?= $_SERVER['SERVER_PORT'] ?>/swagger-ui/documentation/<?= $RR_URI[2] ?>.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });

            //</editor-fold>
        };
    </script>
</body>

</html>