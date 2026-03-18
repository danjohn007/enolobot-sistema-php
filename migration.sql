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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 2. TABLA amenities: agregar columnas faltantes si no existen
--    (usa procedimiento para compatibilidad con MySQL 5.7)
-- ============================================================
DROP PROCEDURE IF EXISTS sp_migrate_amenities;
DELIMITER $$
CREATE PROCEDURE sp_migrate_amenities()
BEGIN
    -- status (debe agregarse ANTES de is_active para que el AFTER funcione)
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'status'
    ) THEN
        ALTER TABLE amenities
            ADD COLUMN status ENUM('available','occupied','maintenance','blocked') DEFAULT 'available';
    END IF;

    -- is_active
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'is_active'
    ) THEN
        ALTER TABLE amenities ADD COLUMN is_active TINYINT(1) DEFAULT 1;
    END IF;

    -- image (para almacenar ruta de imagen)
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'image'
    ) THEN
        ALTER TABLE amenities ADD COLUMN image VARCHAR(255) DEFAULT NULL;
        -- Copiar valores de image_url si esa columna existe
        IF EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'amenities'
              AND COLUMN_NAME  = 'image_url'
        ) THEN
            UPDATE amenities SET image = image_url WHERE image IS NULL;
        END IF;
    END IF;

    -- category
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'category'
    ) THEN
        ALTER TABLE amenities ADD COLUMN category VARCHAR(100) DEFAULT NULL;
    END IF;

    -- capacity
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'capacity'
    ) THEN
        ALTER TABLE amenities ADD COLUMN capacity INT DEFAULT 1;
    END IF;

    -- price
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'price'
    ) THEN
        ALTER TABLE amenities ADD COLUMN price DECIMAL(10,2) DEFAULT 0.00;
    END IF;

    -- operating_hours_start
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'operating_hours_start'
    ) THEN
        ALTER TABLE amenities ADD COLUMN operating_hours_start TIME DEFAULT NULL;
    END IF;

    -- operating_hours_end
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'amenities'
          AND COLUMN_NAME  = 'operating_hours_end'
    ) THEN
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
    -- is_available (requerido por el código; agréguese primero si falta)
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'dishes'
          AND COLUMN_NAME  = 'is_available'
    ) THEN
        ALTER TABLE dishes ADD COLUMN is_available TINYINT(1) DEFAULT 1;
    END IF;

    -- is_active
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'dishes'
          AND COLUMN_NAME  = 'is_active'
    ) THEN
        ALTER TABLE dishes ADD COLUMN is_active TINYINT(1) DEFAULT 1;
    END IF;

    -- preparation_time
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'dishes'
          AND COLUMN_NAME  = 'preparation_time'
    ) THEN
        ALTER TABLE dishes ADD COLUMN preparation_time INT DEFAULT 15;
    END IF;

    -- image (para reemplazar image_url si existía)
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'dishes'
          AND COLUMN_NAME  = 'image'
    ) THEN
        ALTER TABLE dishes ADD COLUMN image VARCHAR(255) DEFAULT NULL;
        -- Copiar valores de image_url si esa columna existe
        IF EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'dishes'
              AND COLUMN_NAME  = 'image_url'
        ) THEN
            UPDATE dishes SET image = image_url WHERE image IS NULL;
        END IF;
    END IF;

    -- category_id
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'dishes'
          AND COLUMN_NAME  = 'category_id'
    ) THEN
        ALTER TABLE dishes ADD COLUMN category_id INT DEFAULT NULL;
    END IF;
END$$
DELIMITER ;
CALL sp_migrate_dishes();
DROP PROCEDURE IF EXISTS sp_migrate_dishes;

-- ============================================================
-- 4. Migrar categorías existentes (category VARCHAR) a menu_categories
--    e inicializar category_id en dishes
-- ============================================================

-- Insertar categorías únicas por hotel desde la columna category de dishes
INSERT INTO menu_categories (hotel_id, name, display_order)
SELECT DISTINCT
    hotel_id,
    CASE category
        WHEN 'breakfast'   THEN 'Desayuno'
        WHEN 'appetizer'   THEN 'Aperitivo'
        WHEN 'main_course' THEN 'Plato Principal'
        WHEN 'dessert'     THEN 'Postre'
        WHEN 'lunch'       THEN 'Comida'
        WHEN 'dinner'      THEN 'Cena'
        ELSE category
    END AS name,
    CASE category
        WHEN 'breakfast'   THEN 1
        WHEN 'appetizer'   THEN 2
        WHEN 'main_course' THEN 3
        WHEN 'dessert'     THEN 4
        WHEN 'lunch'       THEN 5
        WHEN 'dinner'      THEN 6
        ELSE 7
    END AS display_order
FROM dishes
WHERE category IS NOT NULL
  AND category != ''
  AND category_id IS NULL
ON DUPLICATE KEY UPDATE display_order = VALUES(display_order);

-- Asignar category_id en dishes según el nombre de categoría en menu_categories
UPDATE dishes d
INNER JOIN menu_categories mc
    ON mc.hotel_id = d.hotel_id
    AND mc.name = CASE d.category
        WHEN 'breakfast'   THEN 'Desayuno'
        WHEN 'appetizer'   THEN 'Aperitivo'
        WHEN 'main_course' THEN 'Plato Principal'
        WHEN 'dessert'     THEN 'Postre'
        WHEN 'lunch'       THEN 'Comida'
        WHEN 'dinner'      THEN 'Cena'
        ELSE d.category
    END
SET d.category_id = mc.id
WHERE d.category_id IS NULL;
