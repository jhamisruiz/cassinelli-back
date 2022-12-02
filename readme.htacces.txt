# follow symlinks & disallow directory listing
Options +FollowSymlinks -Indexes
DirectorySlash Off

# file etags (used when comparing local cached file to server file)
FileETag MTime Size

<IfModule mod_rewrite.c>
	# nice urls
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    ###### configs Requests
    RewriteRule ^v1/config/?$ app/api/config/config.ng.php? [L,QSA]

    ###login
    RewriteRule ^v1/auth/admin app/api/auth/login.ng.php? [L,QSA]

	# handle urls
	RewriteCond %{REQUEST_URI} !^$
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	#RewriteRule . index.php [NC,L]
	
    ErrorDocument 500 "Sorry, our url not found" 
    ErrorDocument 500 /resources/error/500.php 
</IfModule>

# file caching in browser
<IfModule mod_expires.c>
	ExpiresActive On
	<FilesMatch "\.(?i:ico|gif|jpe?g|png|svg|svgz|js|css|swf|ttf|otf|woff|eot)$">
		ExpiresDefault "access plus 1 month"
	</FilesMatch>
</IfModule>

# gzip on Apache 2
<IfModule mod_deflate.c>
	AddOutputFilterByType DEFLATE text/html text/plain text/xml application/xml text/javascript text/css application/x-javascript application/xhtml+xml application/javascript application/json image/svg+xml

	# these browsers do not support deflate
	BrowserMatch ^Mozilla/4 gzip-only-text/html
	BrowserMatch ^Mozilla/4.0[678] no-gzip
	BrowserMatch bMSIE !no-gzip !gzip-only-text/html

	SetEnvIf User-Agent ".*MSIE.*" nokeepalive ssl-unclean-shutdown downgrade-1.0 force
</IfModule>

# gzip on Apache 1
<IfModule mod_gzip.c>
	mod_gzip_on Yes

	mod_gzip_item_include mime ^application/javascript$
	mod_gzip_item_include mime ^application/x-javascript$
	mod_gzip_item_include mime ^application/json$
	mod_gzip_item_include mime ^application/xhtml+xml$
	mod_gzip_item_include mime ^application/xml$
	mod_gzip_item_include mime ^text/css$
	mod_gzip_item_include mime ^text/html$
	mod_gzip_item_include mime ^text/javascript$
	mod_gzip_item_include mime ^text/plain$
	mod_gzip_item_include mime ^text/xml$
	mod_gzip_item_exclude mime ^image/

	# browser issues
	mod_gzip_item_exclude reqheader "User-agent: Mozilla/4.0[678]"
</IfModule>
-----------------------------------------------------------------------------------------------
Options +FollowSymLinks
#Options All -Indexes

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]

RewriteRule ^([-a-zA-Z0-9-]+)$ index.php?ruta=$1

##RewriteRule ^/?$ index.php?page=index [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
###### configs Requests
RewriteRule ^v1/config/?$ app/api/config/config.ng.php? [L,QSA]

###login
RewriteRule ^v1/auth/admin app/api/auth/login.ng.php? [L,QSA]




#####PEIDOS
###get list
RewriteRule ^v1/pedidos/([0-9]+)/?$ app/src/v1/pedidos/pedidos.ng.php?start=$1&length=$2$search=$3 [L,QSA]
###post
RewriteRule ^v1/pedidos/?$ app/src/v1/pedidos/pedidos.ng.php? [L,QSA]
#####  ADMINISTRACION
###post
RewriteRule ^v1/proveedores/?$ app/src/v1/administracion/administracion.ng.php? [L,QSA]
###get, put, delete list
RewriteRule ^v1/proveedores/([0-9]+)/?$ app/src/v1/administracion/administracion.ng.php?start=$1&length=$2$search=$3 [L,QSA]
#####  ADMINISTRACION-configuraciones
###post
RewriteRule ^v1/configuraciones/?$ app/src/v1/administracion/configuraciones.ng.php? [L,QSA]
###get, put, delete list
RewriteRule ^v1/configuraciones/([0-9]+)/?$ app/src/v1/administracion/configuraciones.ng.php?start=$1&length=$2$search=$3 [L,QSA]
#####  ADMIN EMPRESA
###post
RewriteRule ^v1/configuraciones-empresa/?$ app/src/v1/administracion/empresa.ng.php? [L,QSA]
###get, put, delete list
RewriteRule ^v1/configuraciones-empresa/([0-9]+)/?$ app/src/v1/administracion/empresa.ng.php?start=$1&length=$2$search=$3 [L,QSA]

#####  ORDENES
###post
RewriteRule ^v1/ordenes/?$ app/src/v1/ordenes/ordenes.ng.php? [L,QSA]
###get, put, delete list
RewriteRule ^v1/ordenes/([0-9]+)/?$ app/src/v1/ordenes/ordenes.ng.php?start=$1&length=$2$search=$3 [L,QSA]

#####  USUARIOS
###get list
RewriteRule ^v1/usuarios/([0-9]+)/?$ app/src/v1/users/usuarios.ng.php?start=$1&length=$2$search=$3 [L,QSA]
###post
RewriteRule ^v1/usuarios/?$ app/src/v1/users/usuarios.ng.php? [L,QSA]

#####  CLIENTES
###get list
RewriteRule ^v1/clientes/([0-9]+)/?$ app/src/v1/clientes/clientes.ng.php?start=$1&length=$2$search=$3 [L,QSA]
###post
RewriteRule ^v1/clientes/?$ app/src/v1/clientes/clientes.ng.php? [L,QSA]

RewriteRule ^v1/developer/?$ app/controllers/pedidos/categorias.C.php?
######************************************
######************************************
# RewriteRule ^movimientos/detalle-movmimiento-exel/([0-9]+)/?$ app/src/ajax/files/exel.php?idruta=$1&nam=$2&idmov=$3 [L,QSA]
# RewriteRule ^movimientos/detalle-movmimiento-pdf/([0-9]+)/?$ app/src/ajax/files/pdf.php?idruta=$1&nam=$2&idmov=$3 [L,QSA]

# RewriteRule ^movimientos/detalle-movmimiento-exel/([-a-zA-Z]+)/?$ resources/error/404.php?idruta=$1&nam=$2&idmov=$3 [L,QSA]
# RewriteRule ^movimientos/detalle-movmimiento-pdf/([-a-zA-Z]+)/?$ resources/error/404.php?idruta=$1&nam=$2&idmov=$3 [L,QSA]

ErrorDocument 500 "Sorry, our url not found" 
ErrorDocument 500 /resources/error/500.php 
#ErrorDocument 404 /error/404.php
ErrorDocument 404 "404 page not found"
ErrorDocument 401 /resources/error/401.php 

------
if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        }
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');








		///
		db: sistemaweb_core
		user: sistemaweb_api
		pwss: e21DcrAa6&4i
		-----
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=dbbmdgztfirwhs
DB_USERNAME=u67q7n9pytt2c
DB_PASSWORD=g2l{$~u^6#;1

MP_PUBLIC_KEY=TEST-6430a0fa-c045-451d-9b57-4423ab4379ad
MP_ACCESS_TOKEN=TEST-7385302330675374-082522-e59248283e76f0bb91a93f7ac3f5c7ed-173414187