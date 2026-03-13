# MajorBot - Sistema de Mayordomía Online

Sistema completo de gestión hotelera con módulos de habitaciones, restaurante, amenidades, servicios de mayordomía y suscripciones.

## 🌟 Características Principales

- **Gestión de Habitaciones**: Control completo de habitaciones, disponibilidad, tipos y precios
- **Restaurante**: Gestión de mesas, menú de platillos con categorías, pedidos y reservaciones
- **Amenidades**: Spa, gimnasio, piscina, transporte y más servicios
- **Sistema de Bloqueos**: Control manual de disponibilidad por mantenimiento o eventos
- **Servicios de Mayordomía**: Solicitudes y asignación de tareas al personal
- **Gestión de Personal**: Roles personalizados y permisos específicos
- **Suscripciones**: Planes mensual, anual y prueba gratuita
- **Dashboard Inteligente**: Métricas en tiempo real por rol
- **Multi-rol**: Superadmin, Admin Hotel, Gerente, Hostess, Colaborador, Huésped

## 🚀 Tecnologías

- **Backend**: PHP 7+ (puro, sin framework)
- **Base de Datos**: MySQL 5.7
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Arquitectura**: MVC (Model-View-Controller)
- **Seguridad**: Sesiones seguras, password_hash()
- **URLs**: Amigables con mod_rewrite

## 📋 Requisitos del Sistema

- PHP 7.0 o superior
- MySQL 5.7 o superior
- Apache 2.4+ con mod_rewrite habilitado
- Extensiones PHP requeridas:
  - PDO
  - PDO_MySQL
  - mbstring
  - json

## 🔧 Instalación

### 1. Clonar o descargar el repositorio

```bash
git clone https://github.com/danjohn007/majorbot.git
cd majorbot
```

### 2. Configurar Apache

Asegúrate de que mod_rewrite esté habilitado:

```bash
# En Ubuntu/Debian
sudo a2enmod rewrite
sudo service apache2 restart

# En CentOS/RHEL
# mod_rewrite generalmente está habilitado por defecto
```

Configura el DocumentRoot apuntando a la carpeta del proyecto o copia el proyecto a `/var/www/html/`.

### 3. Configurar la Base de Datos

Edita el archivo `config/config.php` con tus credenciales de MySQL:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'majorbot_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Importar la Base de Datos

Ejecuta el script SQL incluido:

```bash
mysql -u tu_usuario -p < database.sql
```

O desde phpMyAdmin:
1. Crea una base de datos llamada `majorbot_db`
2. Importa el archivo `database.sql`

### 5. Configurar Permisos

```bash
# Asegurar que Apache tenga permisos de escritura
sudo chown -R www-data:www-data /ruta/a/majorbot
sudo chmod -R 755 /ruta/a/majorbot
```

### 6. Verificar la Instalación

1. Abre tu navegador en: `http://localhost/majorbot/test_connection.php`
2. Verifica que la conexión a la base de datos sea exitosa
3. Verifica que la URL base se detecte correctamente

### 7. Acceder al Sistema

Accede a: `http://localhost/majorbot/`

## 👥 Usuarios de Ejemplo

El sistema incluye usuarios de prueba con diferentes roles:

| Rol | Email | Contraseña |
|-----|-------|------------|
| Superadmin | superadmin@majorbot.com | password |
| Admin Hotel | admin@granplaza.com | password |
| Gerente Restaurante | restaurant@granplaza.com | password |
| Hostess | hostess@granplaza.com | password |
| Colaborador | staff@granplaza.com | password |
| Huésped | guest@example.com | password |

## 📚 Estructura del Proyecto

```
majorbot/
├── app/
│   ├── controllers/      # Controladores del sistema
│   ├── models/          # Modelos de datos
│   └── views/           # Vistas (HTML)
│       ├── layouts/     # Plantillas base
│       ├── auth/        # Login, registro
│       ├── dashboard/   # Panel principal
│       ├── rooms/       # Habitaciones
│       ├── tables/      # Mesas restaurante
│       ├── dishes/      # Menú
│       ├── amenities/   # Amenidades
│       ├── blocks/      # Bloqueos
│       ├── services/    # Solicitudes
│       └── users/       # Usuarios
├── config/
│   └── config.php       # Configuración del sistema
├── core/
│   ├── Controller.php   # Controlador base
│   ├── Database.php     # Conexión DB
│   ├── Model.php        # Modelo base
│   └── Router.php       # Enrutador
├── public/
│   ├── assets/          # CSS, JS, imágenes
│   ├── .htaccess       # Reglas de reescritura
│   └── index.php       # Punto de entrada
├── .htaccess           # Redirección a public/
├── database.sql        # Script de base de datos
├── test_connection.php # Test de conexión
└── README.md           # Este archivo
```

## 🎯 Módulos Implementados

### ✅ Módulos Completados

1. **Autenticación y Registro**
   - Login/Logout seguro
   - Registro con selección de plan
   - Recuperación de contraseña
   - Gestión de sesiones

2. **Dashboard**
   - Estadísticas por rol
   - Métricas en tiempo real
   - Reservaciones recientes
   - Solicitudes pendientes

3. **Gestión de Habitaciones**
   - CRUD completo
   - Filtros y búsqueda
   - Control de estados
   - Tipos y precios

4. **Gestión de Mesas**
   - CRUD completo
   - Estados (disponible, ocupada, reservada, bloqueada)
   - Capacidad y ubicación

5. **Gestión de Menú/Platillos**
   - CRUD completo
   - Categorías de menú
   - Precios y disponibilidad
   - Horarios de servicio (desayuno, comida, cena)
   - Control de platillos agotados

6. **Gestión de Amenidades**
   - CRUD completo
   - Categorías (Wellness, Fitness, etc.)
   - Horarios de operación
   - Precios y capacidad

7. **Sistema de Bloqueos (Hostess)**
   - Bloqueo manual de recursos
   - Motivos y períodos
   - Auto-liberación por fecha
   - Historial completo

8. **Solicitudes de Servicio**
   - Creación de solicitudes
   - Asignación a colaboradores
   - Estados y prioridades
   - Seguimiento

9. **Gestión de Usuarios**
   - CRUD de personal
   - Roles y permisos
   - Activación/desactivación

### 🔄 Módulos en Desarrollo

- Reservaciones de habitaciones
- Reservaciones de mesas
- Gestión de pedidos
- Módulo de Superadmin (gestión de hoteles)
- Sistema de notificaciones
- Reportes y exportación
- Pagos y facturación

## 🗺️ Roadmap

### Fase 1 - Core (Completado ✅)
- [x] Infraestructura MVC
- [x] Sistema de autenticación
- [x] Gestión de habitaciones
- [x] Gestión de mesas y menú
- [x] Gestión de amenidades
- [x] Sistema de bloqueos
- [x] Solicitudes de servicio

### Fase 2 - Reservaciones (En progreso 🚧)
- [ ] Módulo de reservaciones de habitaciones
- [ ] Módulo de reservaciones de mesas
- [ ] Reservaciones de amenidades
- [ ] Calendario de disponibilidad
- [ ] Sistema de confirmaciones

### Fase 3 - Operaciones (Planeado 📋)
- [ ] Gestión de pedidos y órdenes
- [ ] Check-in / Check-out
- [ ] Facturación y pagos
- [ ] Sistema de notificaciones (email/push)
- [ ] Chat interno

### Fase 4 - Analytics (Futuro 🔮)
- [ ] Reportes de ocupación
- [ ] Métricas financieras
- [ ] Reportes de actividad
- [ ] Exportación a PDF/Excel
- [ ] Dashboards avanzados

### Fase 5 - Integraciones (Futuro 🔮)
- [ ] Pasarelas de pago (Stripe, PayPal, MercadoPago)
- [ ] APIs RESTful
- [ ] Integración con sistemas PMS
- [ ] App móvil

## 🔐 Seguridad

- Contraseñas hasheadas con `password_hash()`
- Protección contra SQL Injection (PDO con prepared statements)
- Validación de entrada con `filter_input()`
- Sesiones seguras con httponly cookies
- Control de acceso por roles
- CSRF protection (recomendado implementar)

## 🎨 Personalización

### Cambiar Colores del Tema

Edita las variables CSS en `app/views/layouts/header.php`:

```css
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --danger-color: #e74c3c;
    --warning-color: #f39c12;
    --info-color: #16a085;
}
```

### Configurar Zona Horaria

Edita `config/config.php`:

```php
date_default_timezone_set('America/Mexico_City');
```

### Cambiar Nombre del Sistema

Edita `config/config.php`:

```php
define('SITE_NAME', 'Tu Hotel - Sistema de Gestión');
```

## 🐛 Solución de Problemas

### Error: "Database connection failed"

1. Verifica las credenciales en `config/config.php`
2. Asegúrate de que MySQL esté corriendo
3. Verifica que la base de datos exista
4. Comprueba los permisos del usuario MySQL

### Error 404 en todas las páginas

**✅ Corregido:** Los archivos `.htaccess` ahora usan rutas relativas y funcionan en cualquier configuración.

Si aún tienes problemas:

1. Verifica que mod_rewrite esté habilitado
2. Comprueba que los archivos .htaccess existan
3. Revisa la configuración de AllowOverride en Apache
4. Si actualizaste desde una versión anterior, asegúrate de actualizar el archivo `.htaccess` raíz

### Las rutas no funcionan

1. Verifica el DocumentRoot de Apache
2. Asegúrate de que la URL base se detecte correctamente en `test_connection.php`
3. Revisa los archivos .htaccess

### Error de permisos

```bash
sudo chown -R www-data:www-data /ruta/a/majorbot
sudo chmod -R 755 /ruta/a/majorbot
```

## 📖 Documentación de la API (Futuro)

La documentación completa de la API estará disponible en versiones futuras.

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👨‍💻 Autor

Desarrollado para hoteles modernos que buscan optimizar sus operaciones.

## 📧 Soporte

Para soporte o preguntas, por favor abre un issue en GitHub.

---

**MajorBot** - Sistema de Mayordomía Online © 2024
