<?php

/**
 * rutas..... => 
 *@param "REQUEST_URI"=>"FILE_NAME"
 */
$router = [
    //////////////configuraciones de menu submenu
    [
        "endpoint" => "/config-sidebar", //crea
        "method" => "GET",
        "folder_name" => "config",
        "file_name" => "sider",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/config-sidebar"
    ],
    [
        "endpoint" => "/configuracion-empresa", //crea
        "method" => "POST",
        "folder_name" => "config",
        "file_name" => "config",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-empresa"
    ],
    [
        "endpoint" => "/configuracion-empresa", //crea
        "method" => "GET",
        "folder_name" => "config",
        "file_name" => "config",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-empresa"
    ],
    [
        "endpoint" => "/configuracion-empresa",
        "method" => "PUT",
        "folder_name" => "config",
        "file_name" => "config",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-empresa"
    ],
    [
        "endpoint" => "/configuracion-impuestos",
        "method" => "GET",
        "folder_name" => "config",
        "file_name" => "impuestos",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-impuestos"
    ],
    [
        "endpoint" => "/configuracion-impuestos",
        "method" => "PUT",
        "folder_name" => "config",
        "file_name" => "impuestos",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-impuestos"
    ],
    [
        "endpoint" => "/configuracion-umedida",
        "method" => "GET",
        "folder_name" => "config",
        "file_name" => "umedida",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-umedida"
    ],
    [
        "endpoint" => "/configuracion-umedida",
        "method" => "PUT",
        "folder_name" => "config",
        "file_name" => "umedida",
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/configuracion-umedida"
    ],

    //////////////endpoint usuarios -
    [
        "endpoint" => "/usuarios", //crea
        "method" => "POST",
        "folder_name" => "users",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/usuarios"
    ],
    [
        "endpoint" => "/usuarios", //lista todos los usuarios
        "method" => "GET",
        "folder_name" => "users",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/usuarios"
    ],
    [
        "endpoint" => "/usuarios/{id}", //busca por ID
        "method" => "GET",
        "folder_name" => "users",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/usuarios"
    ], [
        "endpoint" => "/usuarios/{id}", //actualiza por id
        "method" => "PUT",
        "folder_name" => "users",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/usuarios"
    ],
    [
        "endpoint" => "/usuarios/{id}/{habilitar}", //actualiza por id
        "method" => "PATCH",
        "folder_name" => "users",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/usuarios"
    ],
    [
        "endpoint" => "/usuarios/{iduser}", //elimina por id
        "method" => "DELETE",
        "folder_name" => "users",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/usuarios"
    ],

    // endpoint PROVEEDORES
    [
        "endpoint" => "/proveedores", //crea
        "method" => "POST",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores"
    ],
    [
        "endpoint" => "/proveedores", //lista todos
        "method" => "GET",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores"
    ],
    [
        "endpoint" => "/proveedores/{idprov}", //busca por ID
        "method" => "GET",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores"
    ], [
        "endpoint" => "/proveedores/{id}", //actualiza por id
        "method" => "PUT",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores"
    ],
    [
        "endpoint" => "/proveedores/{id}/{habilitar}", //actualiza por id
        "method" => "PATCH",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores"
    ],
    [
        "endpoint" => "/proveedores/{idprov}", //elimina por id
        "method" => "DELETE",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores"
    ],
    [
        "endpoint" => "/proveedores-insumos/{idproveedor}", //lista todos
        "method" => "GET",
        "folder_name" => "proveedores",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/proveedores-insumos"
    ],
    //ORDEN DE COMPRA
    [
        "endpoint" => "/orden-compras-codigos/{codigo}", //busca codigo
        "method" => "GET",
        "folder_name" => "ordencompra",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/orden-compras-codigos"
    ],
    [
        "endpoint" => "/orden-compras", //crea
        "method" => "POST",
        "folder_name" => "ordencompra",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/orden-compras"
    ],
    [
        "endpoint" => "/orden-compras/{idordencompras}", //busca por ID
        "method" => "GET",
        "folder_name" => "ordencompra",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/orden-compras"
    ],
    [
        "endpoint" => "/orden-compras", //lista todos
        "method" => "GET",
        "folder_name" => "ordencompra",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/orden-compras"
    ],
    [
        "endpoint" => "/orden-compras/{idorden}", //actualiza por id
        "method" => "PUT",
        "folder_name" => "ordencompra",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/orden-compras"
    ],
    //NOTE: extra....
    //////////////endpoint MERCADOPAGO -
    [
        "endpoint" => "/mercadopago-pagos", //realzia pago
        "method" => "POST",
        "folder_name" => "mercadopago",
        "file_name" => 'mercadopago.router.php',
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/mercadopago-pagos"
    ],
    [
        "endpoint" => "/mercadopago-success", //actualzia pago en pedidos
        "method" => "GET",
        "folder_name" => "mercadopago",
        "file_name" => 'success.router.php',
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/mercadopago-success"
    ],

    //////////////endpoint ubugeo -
    [
        "endpoint" => "/ubigeo-departamento/{departamento}",   # Ruta | {id parametro}
        "method" => "GET",         # Metodo de la solicitud
        "folder_name" => "ubigeo", # Nombre de la carpeta
        "file_name" => null,       # Nombre del script php que recibe
        "querystring_params" => [], # Parametros adicionales en la ruta
        "headers_to_pass" => null, # Obliga a pasar por la validacion del token
        "url_pattern" => "/ubigeo-departamento" # Ruta sin paratmetro
    ],
    [
        "endpoint" => "/ubigeo-provincia/{provincia}",   # Ruta | {id parametro}
        "method" => "GET",         # Metodo de la solicitud
        "folder_name" => "ubigeo", # Nombre de la carpeta
        "file_name" => null,       # Nombre del script php que recibe
        "querystring_params" => [], # Parametros adicionales en la ruta
        "headers_to_pass" => null, # Obliga a pasar por la validacion del token
        "url_pattern" => "/ubigeo-provincia" # Ruta sin paratmetro
    ],
    [
        "endpoint" => "/ubigeo-distrito/{distrito}",   # Ruta | {id parametro}
        "method" => "GET",         # Metodo de la solicitud
        "folder_name" => "ubigeo", # Nombre de la carpeta
        "file_name" => null,       # Nombre del script php que recibe
        "querystring_params" => [], # Parametros adicionales en la ruta
        "headers_to_pass" => null, # Obliga a pasar por la validacion del token
        "url_pattern" => "/ubigeo-distrito" # Ruta sin paratmetro
    ],
    //////////////endpoint login -
    [
        "endpoint" => "/login",
        "method" => "POST",
        "folder_name" => "auth",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" => null,
        "url_pattern" => "/login"
    ],
    [
        "endpoint" => "/logout",
        "method" => "PUT",
        "folder_name" => "auth",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/logout"
    ],
    //////////////endpoint CLIENTES
    [
        "endpoint" => "/clientes", //lista todos los clientes
        "method" => "GET",
        "folder_name" => "clientes",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/clientes"
    ],
    //////////////endpoint pedidos
    [
        "endpoint" => "/pedidos", //crea
        "method" => "POST",
        "folder_name" => "pedidos",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/pedidos"
    ],
    [
        "endpoint" => "/pedidos", //lista todos los pedidos
        "method" => "GET",
        "folder_name" => "pedidos",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/pedidos"
    ],
    [
        "endpoint" => "/pedidos/{idpedido}", //busca por ID
        "method" => "GET",
        "folder_name" => "pedidos",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/pedidos"
    ], [
        "endpoint" => "/pedidos/{id}", //actualiza por id
        "method" => "PUT",
        "folder_name" => "pedidos",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/pedidos"
    ],
    [
        "endpoint" => "/pedidos/{idpedido}", //elimina por id
        "method" => "DELETE",
        "folder_name" => "pedidos",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/pedidos"
    ],
    ///////////////CUPONES
    [
        "endpoint" => "/cupones", //crea
        "method" => "POST",
        "folder_name" => "cupones",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/cupones"
    ],
    [
        "endpoint" => "/cupones", //lista todos los pedidos
        "method" => "GET",
        "folder_name" => "cupones",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/cupones"
    ],
    [
        "endpoint" => "/cupones/{id}", //actualiza por id
        "method" => "PUT",
        "folder_name" => "cupones",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/cupones"
    ],
    [
        "endpoint" => "/cupones/{id}", //elimina por id
        "method" => "DELETE",
        "folder_name" => "cupones",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/cupones"
    ],
    [
        "endpoint" => "/cupones/{id}/{habilitar}", //actualiza por id
        "method" => "PATCH",
        "folder_name" => "cupones",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/cupones"
    ],
    /// clientes
    [
        "endpoint" => "/clientes", //crea
        "method" => "POST",
        "folder_name" => "clientes",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/clientes"
    ],
    [
        "endpoint" => "/clientes", //lista todos los pedidos
        "method" => "GET",
        "folder_name" => "clientes",
        "file_name" => null,
        "querystring_params" => ['start', 'length', 'search', 'order'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/clientes"
    ],
    [
        "endpoint" => "/clientes/{id}", //actualiza por id
        "method" => "PUT",
        "folder_name" => "clientes",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/clientes"
    ],
    [
        "endpoint" => "/clientes/{id}", //elimina por id
        "method" => "DELETE",
        "folder_name" => "clientes",
        "file_name" => null,
        "querystring_params" => [],
        "headers_to_pass" =>  null,
        "url_pattern" => "/clientes"
    ],
    //delete imagen
    [
        "endpoint" => "/imgpedidos/{idimagen}", //elimina por id
        "method" => "DELETE",
        "folder_name" => "pedidos",
        "file_name" => null,
        "querystring_params" => ['file'],
        "headers_to_pass" =>  null,
        "url_pattern" => "/imgpedidos"
    ],
];
