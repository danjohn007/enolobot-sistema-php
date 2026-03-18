# INSTRUCCIONES PARA CORREGIR EL PROBLEMA EN CPANEL

## PASO 1: Editar .htaccess en la raíz (public_html/sistema/.htaccess)

Reemplaza TODO el contenido con:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /sistema/
    
    # Allow direct access to PHP test files in root
    RewriteRule ^(test_.*\.php|debug\.php)$ - [L]
    
    # Deny access to config and sensitive directories
    RewriteRule ^(config|core|app)/ - [F,L]
    
    # Redirect to public folder
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## PASO 2: Editar .htaccess en public (public_html/sistema/public/.htaccess)

Reemplaza TODO el contenido con:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /sistema/public/
    
    # Don't rewrite files or directories
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Rewrite everything else to index.php
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

## PASO 3: Sube el archivo test_simple.php a la raíz

Sube el archivo test_simple.php que acabo de crear a:
public_html/sistema/

## PASO 4: Verificar archivos en el servidor

Asegúrate que estos archivos existan y estén subidos:
- config/config.php
- core/Router.php
- core/Controller.php
- core/Database.php
- app/controllers/AuthController.php
- app/views/auth/login.php
- app/views/layouts/header.php
- public/index.php

## PASO 5: Probar

1. Accede a: http://enolobot.digital/sistema/test_simple.php
   - Esto te mostrará exactamente qué está fallando

2. Copia TODO el error que aparezca y envíamelo

3. Luego intenta: http://enolobot.digital/sistema/auth/login

## Cambios clave:
- Agregué `RewriteBase /sistema/` para que Apache sepa que el proyecto está en un subdirectorio
- Permití acceso directo a archivos test_*.php para diagnóstico
- RewriteBase correcto en public/.htaccess (/sistema/public/)
