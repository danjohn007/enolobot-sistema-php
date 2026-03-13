# Guía de Instalación - MajorBot

## Requisitos Previos

Antes de comenzar, asegúrese de tener instalado:

- **Apache 2.4+** con mod_rewrite habilitado
- **PHP 7.0+** con las siguientes extensiones:
  - PDO
  - PDO_MySQL
  - mbstring
  - json
- **MySQL 5.7+** o MariaDB 10.2+

## Instalación Paso a Paso

### 1. Descargar el Sistema

```bash
git clone https://github.com/danjohn007/majorbot.git
cd majorbot
```

O descargue el archivo ZIP y descomprímalo en su servidor.

### 2. Configurar Apache

#### Habilitar mod_rewrite (Ubuntu/Debian)

```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

#### Configurar VirtualHost (Opcional pero recomendado)

Cree un archivo de configuración:

```bash
sudo nano /etc/apache2/sites-available/majorbot.conf
```

Agregue la siguiente configuración:

```apache
<VirtualHost *:80>
    ServerName majorbot.local
    DocumentRoot /var/www/html/majorbot
    
    <Directory /var/www/html/majorbot>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/majorbot_error.log
    CustomLog ${APACHE_LOG_DIR}/majorbot_access.log combined
</VirtualHost>
```

Habilite el sitio:

```bash
sudo a2ensite majorbot.conf
sudo service apache2 reload
```

Agregue a `/etc/hosts`:

```
127.0.0.1    majorbot.local
```

### 3. Configurar Permisos

```bash
cd /var/www/html/majorbot
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
```

### 4. Crear Base de Datos

Acceda a MySQL:

```bash
mysql -u root -p
```

Cree la base de datos y un usuario:

```sql
CREATE DATABASE majorbot_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'majorbot_user'@'localhost' IDENTIFIED BY 'su_password_segura';
GRANT ALL PRIVILEGES ON majorbot_db.* TO 'majorbot_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Importar el Schema

```bash
mysql -u majorbot_user -p majorbot_db < database.sql
```

### 6. Configurar Credenciales

Edite el archivo `config/config.php`:

```bash
nano config/config.php
```

Actualice las credenciales de base de datos:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'majorbot_db');
define('DB_USER', 'majorbot_user');
define('DB_PASS', 'su_password_segura');
```

### 7. Verificar Instalación

Abra su navegador y visite:

```
http://localhost/majorbot/test_connection.php
```

o si configuró un VirtualHost:

```
http://majorbot.local/test_connection.php
```

Debería ver:
- ✅ URL Base detectada correctamente
- ✅ Conexión a base de datos exitosa
- ✅ Configuración del sistema correcta

### 8. Acceder al Sistema

Una vez verificada la instalación, acceda al sistema:

```
http://localhost/majorbot/
```

o

```
http://majorbot.local/
```

## Usuarios de Ejemplo

El sistema incluye los siguientes usuarios de prueba:

| Rol | Email | Contraseña |
|-----|-------|------------|
| Superadmin | superadmin@majorbot.com | password |
| Admin Hotel | admin@granplaza.com | password |
| Gerente Restaurante | restaurant@granplaza.com | password |
| Hostess | hostess@granplaza.com | password |
| Colaborador | staff@granplaza.com | password |
| Huésped | guest@example.com | password |

**IMPORTANTE:** Cambie estas contraseñas en producción.

## Configuración Adicional

### Cambiar Zona Horaria

Edite `config/config.php`:

```php
date_default_timezone_set('America/Mexico_City');
```

### Personalizar Nombre del Sistema

Edite `config/config.php`:

```php
define('SITE_NAME', 'Mi Hotel - Sistema de Gestión');
```

### Configurar Email (Para notificaciones futuras)

Las configuraciones de email se agregarán en futuras versiones.

## Solución de Problemas

### Error: "Database connection failed"

1. Verifique que MySQL esté corriendo:
   ```bash
   sudo service mysql status
   ```

2. Verifique las credenciales en `config/config.php`

3. Verifique que la base de datos exista:
   ```bash
   mysql -u root -p -e "SHOW DATABASES;"
   ```

### Error 404 en todas las páginas

**Nota:** Este problema ha sido corregido en la versión actual. Los archivos `.htaccess` ahora usan rutas relativas que funcionan en cualquier configuración de directorio.

Si aún experimenta errores 404, verifique lo siguiente:

1. Verifique que mod_rewrite esté habilitado:
   ```bash
   apache2ctl -M | grep rewrite
   ```

2. Verifique que existan los archivos `.htaccess`:
   - `/majorbot/.htaccess`
   - `/majorbot/public/.htaccess`

3. Verifique la configuración de Apache `AllowOverride`:
   ```apache
   AllowOverride All
   ```

4. Si ha actualizado desde una versión anterior, asegúrese de que el archivo `.htaccess` raíz esté actualizado y use rutas relativas (sin `RewriteBase /`)

### La URL base no se detecta correctamente

Edite manualmente `config/config.php`:

```php
define('BASE_URL', 'http://localhost/majorbot');
```

### Error de permisos

```bash
sudo chown -R www-data:www-data /var/www/html/majorbot
sudo chmod -R 755 /var/www/html/majorbot
```

## Seguridad en Producción

Antes de implementar en producción:

1. **Cambie todas las contraseñas de ejemplo**
2. **Use HTTPS** (Let's Encrypt gratuito)
3. **Configure un firewall** (ufw, firewalld)
4. **Deshabilite el archivo** `test_connection.php`
5. **Configure backups automáticos** de la base de datos
6. **Actualice PHP y MySQL** regularmente
7. **Revise los logs** periódicamente

## Backups

### Backup de Base de Datos

```bash
mysqldump -u majorbot_user -p majorbot_db > backup_$(date +%Y%m%d).sql
```

### Backup Completo

```bash
tar -czf majorbot_backup_$(date +%Y%m%d).tar.gz /var/www/html/majorbot
```

## Actualización

Para actualizar a una nueva versión:

1. Haga backup de la base de datos y archivos
2. Descargue la nueva versión
3. Ejecute cualquier script SQL de migración incluido
4. Limpie la caché del navegador

## Soporte

Para obtener ayuda:

- Consulte el archivo `README.md`
- Abra un issue en GitHub
- Revise la documentación en línea

---

**MajorBot** - Sistema de Mayordomía Online © 2024
