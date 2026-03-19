-- ============================================================
-- MIGRATION SCRIPT
-- Actualización de la base de datos para corregir Amenidades y Dishes
-- Ejecutar este script en la base de datos existente (enolobot_chatbot)
-- Compatible con MySQL 5.7+
-- ============================================================

-- ============================================================
-- 1. TABLA menu_categories (nueva, requerida por el código)
-- ============================================================
CREATE TABLE IF NOT EXISTS menu_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABLA amenities: agregar columnas faltantes si no existen
-- ============================================================
DROP PROCEDURE IF EXISTS sp_migrate_amenities;
DELIMITER $$
CREATE PROCEDURE sp_migrate_amenities()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'status') THEN
        ALTER TABLE amenities ADD COLUMN status ENUM('available','occupied','maintenance','blocked') DEFAULT 'available';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'is_active') THEN
        ALTER TABLE amenities ADD COLUMN is_active TINYINT(1) DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'image') THEN
        ALTER TABLE amenities ADD COLUMN image VARCHAR(255) DEFAULT NULL;
        IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'image_url') THEN
            UPDATE amenities SET image = image_url WHERE image IS NULL;
        END IF;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'category') THEN
        ALTER TABLE amenities ADD COLUMN category VARCHAR(100) DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'capacity') THEN
        ALTER TABLE amenities ADD COLUMN capacity INT DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'price') THEN
        ALTER TABLE amenities ADD COLUMN price DECIMAL(10,2) DEFAULT 0.00;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'operating_hours_start') THEN
        ALTER TABLE amenities ADD COLUMN operating_hours_start TIME DEFAULT NULL;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'amenities' AND COLUMN_NAME = 'operating_hours_end') THEN
        ALTER TABLE amenities ADD COLUMN operating_hours_end TIME DEFAULT NULL;
    END IF;
END$$
DELIMITER ;
CALL sp_migrate_amenities();
DROP PROCEDURE IF EXISTS sp_migrate_amenities;

-- ============================================================
-- 3. TABLA dishes: agregar columnas faltantes si no existen
-- ============================================================
DROP PROCEDURE IF EXISTS sp_migrate_dishes;
DELIMITER $$
CREATE PROCEDURE sp_migrate_dishes()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dishes' AND COLUMN_NAME = 'is_available') THEN
        ALTER TABLE dishes ADD COLUMN is_available TINYINT(1) DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dishes' AND COLUMN_NAME = 'is_active') THEN
        ALTER TABLE dishes ADD COLUMN is_active TINYINT(1) DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dishes' AND COLUMN_NAME = 'preparation_time') THEN
        ALTER TABLE dishes ADD COLUMN preparation_time INT DEFAULT 15;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dishes' AND COLUMN_NAME = 'image') THEN
        ALTER TABLE dishes ADD COLUMN image VARCHAR(255) DEFAULT NULL;
        IF EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dishes' AND COLUMN_NAME = 'image_url') THEN
            UPDATE dishes SET image = image_url WHERE image IS NULL;
        END IF;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dishes' AND COLUMN_NAME = 'category_id') THEN
        ALTER TABLE dishes ADD COLUMN category_id INT DEFAULT NULL;
    END IF;
END$$
DELIMITER ;
CALL sp_migrate_dishes();
DROP PROCEDURE IF EXISTS sp_migrate_dishes;

-- ============================================================
-- 4. Migrar categorías existentes a menu_categories e inicializar category_id
-- ============================================================

-- Insertar categorías únicas por hotel desde la columna category de dishes
INSERT INTO menu_categories (hotel_id, name, display_order)
SELECT DISTINCT
    hotel_id,
    CASE category
        WHEN 'breakfast' THEN 'Desayuno'
        WHEN 'appetizer' THEN 'Aperitivo'
        WHEN 'main_course' THEN 'Plato Principal'
        WHEN 'dessert' THEN 'Postre'
        WHEN 'lunch' THEN 'Comida'
        WHEN 'dinner' THEN 'Cena'
        ELSE category
    END AS name,
    CASE category
        WHEN 'breakfast' THEN 1
        WHEN 'appetizer' THEN 2
        WHEN 'main_course' THEN 3
        WHEN 'dessert' THEN 4
        WHEN 'lunch' THEN 5
        WHEN 'dinner' THEN 6
        ELSE 7
    END AS display_order
FROM dishes
WHERE category IS NOT NULL
  AND category != ''
  AND category_id IS NULL
ON DUPLICATE KEY UPDATE display_order = VALUES(display_order);

-- Asignar category_id en dishes con actualizaciones separadas (collation seguro)
UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = 'Desayuno'
SET d.category_id = mc.id
WHERE d.category_id IS NULL AND d.category = 'breakfast';

UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = 'Aperitivo'
SET d.category_id = mc.id
WHERE d.category_id IS NULL AND d.category = 'appetizer';

UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = 'Plato Principal'
SET d.category_id = mc.id
WHERE d.category_id IS NULL AND d.category = 'main_course';

UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = 'Postre'
SET d.category_id = mc.id
WHERE d.category_id IS NULL AND d.category = 'dessert';

UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = 'Comida'
SET d.category_id = mc.id
WHERE d.category_id IS NULL AND d.category = 'lunch';

UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = 'Cena'
SET d.category_id = mc.id
WHERE d.category_id IS NULL AND d.category = 'dinner';

-- Categorías personalizadas (catch-all)
UPDATE dishes d
INNER JOIN menu_categories mc 
    ON mc.hotel_id = d.hotel_id 
    AND mc.name COLLATE utf8mb4_unicode_ci = d.category COLLATE utf8mb4_unicode_ci
SET d.category_id = mc.id
WHERE d.category_id IS NULL
  AND d.category NOT IN ('breakfast','appetizer','main_course','dessert','lunch','dinner');

-- ============================================================
-- 5. TABLA dishes: hacer category_id nullable y agregar columna
--    category para soporte de categorías de texto libre
-- ============================================================
DROP PROCEDURE IF EXISTS sp_migrate_dishes_category;
DELIMITER $$
CREATE PROCEDURE sp_migrate_dishes_category()
BEGIN
    -- Hacer category_id nullable si actualmente es NOT NULL
    IF EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'dishes'
          AND COLUMN_NAME = 'category_id'
          AND IS_NULLABLE = 'NO'
    ) THEN
        ALTER TABLE dishes MODIFY COLUMN category_id INT NULL DEFAULT NULL;
    END IF;

    -- Agregar columna category (texto) si no existe
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'dishes'
          AND COLUMN_NAME = 'category'
    ) THEN
        ALTER TABLE dishes ADD COLUMN category VARCHAR(100) DEFAULT NULL AFTER category_id;
    END IF;
END$$
DELIMITER ;
CALL sp_migrate_dishes_category();
DROP PROCEDURE IF EXISTS sp_migrate_dishes_category;

-- Sincronizar columna category con el nombre de la categoría FK existente
UPDATE dishes d
JOIN menu_categories c ON d.category_id = c.id
SET d.category = c.name
WHERE d.category IS NULL AND d.category_id IS NOT NULL;

-- ============================================================
-- 6. TABLA wines (nueva)
-- ============================================================
CREATE TABLE IF NOT EXISTS wines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    details TEXT,
    suggested_for TEXT,
    price DECIMAL(10,2) DEFAULT 0.00,
    image_path VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TABLA wine_drafts (borradores / solicitudes de vino del chatbot)
-- ============================================================
CREATE TABLE IF NOT EXISTS wine_drafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    customer_name VARCHAR(200) DEFAULT NULL,
    wine_id INT DEFAULT NULL,
    status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (wine_id) REFERENCES wines(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. TABLA conversation_logs (registros de interacciones del chatbot)
-- ============================================================
CREATE TABLE IF NOT EXISTS conversation_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT DEFAULT NULL,
    phone VARCHAR(30) NOT NULL,
    message_type ENUM('text','button','image','audio','video','document','location','other') DEFAULT 'text',
    message_content TEXT DEFAULT NULL,
    direction ENUM('inbound','outbound') DEFAULT 'inbound',
    wine_id INT DEFAULT NULL,
    draft_id INT DEFAULT NULL,
    session_id VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE SET NULL,
    FOREIGN KEY (wine_id) REFERENCES wines(id) ON DELETE SET NULL,
    FOREIGN KEY (draft_id) REFERENCES wine_drafts(id) ON DELETE SET NULL,
    INDEX idx_phone (phone),
    INDEX idx_created_at (created_at),
    INDEX idx_hotel_id (hotel_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- NOTA SOBRE VISIBILIDAD DE AMENIDADES:
-- Si las amenidades no aparecen en el sistema (pantalla vacía),
-- es probable que el hotel_id de los registros importados no
-- coincida con el hotel_id del usuario activo en sesión.
-- Identifique el hotel_id correcto y ajuste los datos:
--
--   SELECT id, name FROM hotels;
--   UPDATE amenities SET hotel_id = <ID_CORRECTO>
--   WHERE hotel_id != <ID_CORRECTO>;
--
-- También asegúrese de que is_active = 1 en todos los registros
-- que desea mostrar:
--   UPDATE amenities SET is_active = 1 WHERE is_active IS NULL;
-- ============================================================

-- ============================================================
-- ACTUALIZACIÓN: Tabla customer_profile para nombres de clientes
-- Ejecute esta sentencia si la tabla no existe aún en la base de datos
-- ============================================================
CREATE TABLE IF NOT EXISTS customer_profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(30) NOT NULL UNIQUE,
    customer_name VARCHAR(200) DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- ============================================================
-- NOTA: La tabla customer_profile almacena los perfiles de clientes
-- del chatbot. El campo 'phone' debe coincidir con el campo 'phone'
-- de la tabla 'conversation_logs' para que aparezca el nombre del
-- cliente en el módulo de Interacciones (/conversation_logs).
-- ============================================================
