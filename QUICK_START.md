# Quick Start Guide - MajorBot

## Inicio Rápido (5 minutos)

### 1. Instalación Express

```bash
# Clonar repositorio
git clone https://github.com/danjohn007/majorbot.git
cd majorbot

# Crear base de datos
mysql -u root -p -e "CREATE DATABASE majorbot_db"

# Importar datos
mysql -u root -p majorbot_db < database.sql

# Configurar credenciales
nano config/config.php
```

### 2. Acceder al Sistema

```
http://localhost/majorbot/
```

### 3. Login Rápido

```
Email: admin@granplaza.com
Password: password
```

## Guía Rápida por Módulo

### 📦 Gestión de Habitaciones

1. Dashboard → Habitaciones
2. Click "Nueva Habitación"
3. Complete: Número, Tipo, Capacidad, Precio
4. Guardar

**Estados disponibles:** Disponible, Ocupada, Mantenimiento, Bloqueada

### 🍽️ Gestión de Restaurante

#### Mesas
1. Dashboard → Mesas
2. Click "Nueva Mesa"
3. Complete: Número, Capacidad, Ubicación

#### Menú
1. Dashboard → Menú
2. Primero: Crear categorías (Entradas, Principales, Postres, Bebidas)
3. Luego: Agregar platillos a cada categoría

### 🏊 Amenidades

1. Dashboard → Amenidades
2. Click "Nueva Amenidad"
3. Complete: Nombre, Categoría, Capacidad, Precio, Horarios

**Categorías sugeridas:**
- Wellness (Spa, Masajes)
- Fitness (Gimnasio)
- Recreation (Piscina)
- Business (Salas de juntas)
- Transport (Transporte)

### 🔒 Sistema de Bloqueos

1. Dashboard → Bloqueos
2. Click "Nuevo Bloqueo"
3. Seleccione: Tipo de recurso, Recurso específico, Motivo
4. Configure: Fecha inicio, Fecha fin (opcional)

**Tip:** Dejar fecha fin vacía = bloqueo indefinido

### 🔔 Solicitudes de Servicio

#### Como Admin/Manager:
1. Dashboard → Solicitudes
2. Ver solicitudes pendientes
3. Asignar a colaborador
4. Cambiar estado según avance

#### Como Colaborador:
1. Dashboard → Solicitudes
2. Ver "Mis Tareas"
3. Actualizar estado

#### Como Huésped:
1. Dashboard → Solicitar Servicio
2. Seleccionar tipo y habitación
3. Describir solicitud

### 👥 Gestión de Personal

1. Dashboard → Usuarios
2. Click "Nuevo Usuario"
3. Complete datos y asigne rol

**Roles disponibles:**
- **Admin Hotel:** Control total
- **Gerente Restaurante:** Menú y mesas
- **Hostess:** Bloqueos y disponibilidad
- **Colaborador:** Atención de servicios
- **Huésped:** Acceso limitado

## Flujos de Trabajo Comunes

### Nuevo Check-in de Huésped

1. Verificar disponibilidad en Habitaciones
2. Si necesario, liberar bloqueos
3. Cambiar estado a "Ocupada"
4. Crear usuario huésped (opcional)

### Mantenimiento Programado

1. Ir a Bloqueos
2. Crear bloqueo con tipo "Mantenimiento"
3. Seleccionar recurso (habitación/mesa/amenidad)
4. Establecer período de tiempo
5. El sistema bloquea automáticamente

### Gestión de Pedidos

1. Huésped solicita servicio tipo "Room Service"
2. Admin/Manager asigna a colaborador de cocina
3. Colaborador actualiza estado: "En Proceso"
4. Al completar: "Completada"

### Reservación de Mesa

1. Verificar disponibilidad en Mesas
2. Cambiar estado a "Reservada"
3. Crear recordatorio (manual por ahora)
4. Al llegar el huésped: "Ocupada"
5. Al terminar: "Disponible"

## Atajos de Teclado

*No implementados aún - Próxima versión*

## Mejores Prácticas

### Para Administradores

✅ **Hacer:**
- Revisar solicitudes pendientes diariamente
- Mantener información actualizada
- Capacitar al personal en el sistema
- Hacer backups semanales

❌ **Evitar:**
- Compartir contraseñas de admin
- Eliminar usuarios con historial
- Bloqueos sin motivo claro

### Para Personal Operativo

✅ **Hacer:**
- Actualizar estados en tiempo real
- Completar información de servicios
- Notificar problemas inmediatamente

❌ **Evitar:**
- Dejar solicitudes sin asignar
- Cambiar estados sin completar tarea

## Comandos Útiles

### Backup Rápido

```bash
mysqldump -u root -p majorbot_db > backup.sql
```

### Ver Logs de Apache

```bash
tail -f /var/log/apache2/error.log
```

### Reiniciar Apache

```bash
sudo service apache2 restart
```

## Resolución Rápida de Problemas

| Problema | Solución Rápida |
|----------|----------------|
| No carga el sistema | Verificar Apache y MySQL activos |
| Error 404 | Verificar mod_rewrite habilitado |
| No conecta DB | Revisar config/config.php |
| Sesión expira rápido | Aumentar session timeout en PHP |
| Lento | Optimizar queries, agregar índices |

## Recursos de Aprendizaje

1. **README.md** - Documentación completa
2. **INSTALLATION.md** - Guía detallada de instalación
3. **database.sql** - Schema de base de datos con comentarios

## Próximos Pasos

Después de familiarizarse con lo básico:

1. Configure usuarios reales de su hotel
2. Personalice categorías y servicios
3. Agregue todas sus habitaciones y mesas
4. Configure horarios de operación
5. Capacite a su equipo

## Soporte

¿Necesita ayuda?

- 📖 Consulte la documentación completa
- 🐛 Reporte bugs en GitHub Issues
- 💡 Sugerencias bienvenidas

---

¡Listo para comenzar! 🚀

**MajorBot** - Sistema de Mayordomía Online
