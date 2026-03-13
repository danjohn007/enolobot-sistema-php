-- Database: majorbot_db
-- MySQL 5.7 compatible

CREATE DATABASE IF NOT EXISTS majorbot_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE majorbot_db;

-- ============================================
-- SUBSCRIPTION PLANS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    billing_period ENUM('monthly', 'annual', 'trial') NOT NULL,
    trial_days INT DEFAULT 0,
    max_rooms INT DEFAULT 10,
    max_tables INT DEFAULT 10,
    max_amenities INT DEFAULT 10,
    max_collaborators INT DEFAULT 5,
    features TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- HOTELS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100) DEFAULT 'México',
    logo VARCHAR(255),
    subscription_plan_id INT,
    subscription_status ENUM('active', 'expired', 'trial', 'cancelled') DEFAULT 'trial',
    subscription_start DATE,
    subscription_end DATE,
    timezone VARCHAR(50) DEFAULT 'America/Mexico_City',
    currency VARCHAR(10) DEFAULT 'MXN',
    language VARCHAR(10) DEFAULT 'es',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plans(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('superadmin', 'hotel_admin', 'restaurant_manager', 'hostess', 'collaborator', 'guest') NOT NULL,
    avatar VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ROLES AND PERMISSIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS custom_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    permissions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ROOMS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_number VARCHAR(50) NOT NULL,
    room_type ENUM('single', 'double', 'suite', 'deluxe', 'presidential') NOT NULL,
    capacity INT DEFAULT 2,
    price_per_night DECIMAL(10,2) NOT NULL,
    floor INT,
    description TEXT,
    amenities TEXT,
    status ENUM('available', 'occupied', 'maintenance', 'blocked') DEFAULT 'available',
    images TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    UNIQUE KEY unique_room (hotel_id, room_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- RESTAURANT TABLES
-- ============================================
CREATE TABLE IF NOT EXISTS restaurant_tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    table_number VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(100),
    status ENUM('available', 'occupied', 'reserved', 'blocked') DEFAULT 'available',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    UNIQUE KEY unique_table (hotel_id, table_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- MENU CATEGORIES TABLE
-- ============================================
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

-- ============================================
-- DISHES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS dishes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    service_time ENUM('breakfast', 'lunch', 'dinner', 'all_day') DEFAULT 'all_day',
    preparation_time INT DEFAULT 15,
    is_available TINYINT(1) DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- AMENITIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    capacity INT DEFAULT 1,
    price DECIMAL(10,2) DEFAULT 0.00,
    operating_hours_start TIME,
    operating_hours_end TIME,
    image VARCHAR(255),
    status ENUM('available', 'occupied', 'maintenance', 'blocked') DEFAULT 'available',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ROOM RESERVATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS room_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_id INT NOT NULL,
    guest_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests_count INT DEFAULT 1,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABLE RESERVATIONS
-- ============================================
CREATE TABLE IF NOT EXISTS table_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    table_id INT NOT NULL,
    guest_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    guests_count INT NOT NULL,
    status ENUM('pending', 'confirmed', 'seated', 'completed', 'cancelled') DEFAULT 'pending',
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- AMENITY RESERVATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS amenity_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    amenity_id INT NOT NULL,
    guest_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    guests_count INT DEFAULT 1,
    total_price DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('pending', 'confirmed', 'in_use', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ORDERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    guest_id INT NOT NULL,
    room_id INT NULL,
    table_reservation_id INT NULL,
    order_type ENUM('room_service', 'restaurant', 'takeout') NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    FOREIGN KEY (table_reservation_id) REFERENCES table_reservations(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ORDER ITEMS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    dish_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    special_instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- SERVICE REQUESTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    guest_id INT NOT NULL,
    room_id INT NULL,
    service_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    assigned_to INT NULL,
    status ENUM('pending', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- BLOCKS TABLE (for manual blocking)
-- ============================================
CREATE TABLE IF NOT EXISTS blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    resource_type ENUM('room', 'table', 'amenity') NOT NULL,
    resource_id INT NOT NULL,
    blocked_by INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NULL,
    is_active TINYINT(1) DEFAULT 1,
    released_by INT NULL,
    released_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (blocked_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (released_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- NOTIFICATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    related_id INT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- PAYMENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_type ENUM('subscription', 'reservation', 'service', 'order') NOT NULL,
    payment_method VARCHAR(50),
    transaction_id VARCHAR(255),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- SETTINGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    UNIQUE KEY unique_setting (hotel_id, setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- SAMPLE DATA
-- ============================================

-- Insert subscription plans
INSERT INTO subscription_plans (name, description, price, billing_period, trial_days, max_rooms, max_tables, max_amenities, max_collaborators, features) VALUES
('Plan Prueba', 'Prueba gratuita por 30 días', 0.00, 'trial', 30, 5, 5, 5, 3, 'Funcionalidades básicas'),
('Plan Mensual', 'Plan mensual con todas las funciones', 499.00, 'monthly', 0, 50, 30, 20, 20, 'Todas las funcionalidades'),
('Plan Anual', 'Plan anual con descuento del 20%', 4790.00, 'annual', 0, 100, 50, 40, 50, 'Todas las funcionalidades + Soporte prioritario');

-- Insert sample hotel
INSERT INTO hotels (name, email, phone, address, city, state, country, subscription_plan_id, subscription_status, subscription_start, subscription_end) VALUES
('Hotel Gran Plaza', 'admin@granplaza.com', '555-1234', 'Av. Principal 123', 'Ciudad de México', 'CDMX', 'México', 2, 'active', '2024-01-01', '2024-12-31');

-- Insert users
INSERT INTO users (hotel_id, email, password, first_name, last_name, role) VALUES
(NULL, 'superadmin@majorbot.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super', 'Admin', 'superadmin'),
(1, 'admin@granplaza.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Hernández', 'hotel_admin'),
(1, 'restaurant@granplaza.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'González', 'restaurant_manager'),
(1, 'hostess@granplaza.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'López', 'hostess'),
(1, 'staff@granplaza.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Pérez', 'collaborator'),
(1, 'guest@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro', 'Ramírez', 'guest');

-- Note: Default password for all users is 'password'

-- Insert sample rooms
INSERT INTO rooms (hotel_id, room_number, room_type, capacity, price_per_night, floor, description, status) VALUES
(1, '101', 'single', 1, 800.00, 1, 'Habitación individual con vista a la ciudad', 'available'),
(1, '102', 'double', 2, 1200.00, 1, 'Habitación doble con cama king size', 'available'),
(1, '201', 'suite', 4, 2500.00, 2, 'Suite ejecutiva con sala de estar', 'available'),
(1, '301', 'deluxe', 3, 3500.00, 3, 'Habitación deluxe con terraza privada', 'available'),
(1, '401', 'presidential', 6, 8000.00, 4, 'Suite presidencial con jacuzzi', 'available');

-- Insert restaurant tables
INSERT INTO restaurant_tables (hotel_id, table_number, capacity, location, status) VALUES
(1, 'M1', 2, 'Ventana', 'available'),
(1, 'M2', 4, 'Centro', 'available'),
(1, 'M3', 4, 'Centro', 'available'),
(1, 'M4', 6, 'Terraza', 'available'),
(1, 'M5', 8, 'Salón privado', 'available');

-- Insert menu categories
INSERT INTO menu_categories (hotel_id, name, description, display_order) VALUES
(1, 'Entradas', 'Aperitivos y entradas', 1),
(1, 'Platos Fuertes', 'Platos principales', 2),
(1, 'Postres', 'Postres y dulces', 3),
(1, 'Bebidas', 'Bebidas y cócteles', 4);

-- Insert sample dishes
INSERT INTO dishes (hotel_id, category_id, name, description, price, service_time) VALUES
(1, 1, 'Ensalada César', 'Lechuga romana, crutones, parmesano y aderezo césar', 120.00, 'all_day'),
(1, 1, 'Sopa de Tortilla', 'Sopa tradicional mexicana con chile pasilla', 95.00, 'lunch'),
(1, 2, 'Filete de Res', 'Corte de res 250g con papas y vegetales', 385.00, 'dinner'),
(1, 2, 'Pasta Alfredo', 'Fettuccine en salsa alfredo con pollo', 235.00, 'all_day'),
(1, 2, 'Tacos de Camarón', 'Tres tacos de camarón con guacamole', 195.00, 'all_day'),
(1, 3, 'Pastel de Chocolate', 'Pastel de chocolate con helado de vainilla', 95.00, 'all_day'),
(1, 3, 'Flan Napolitano', 'Flan casero con caramelo', 75.00, 'all_day'),
(1, 4, 'Margarita', 'Margarita clásica de limón', 120.00, 'all_day'),
(1, 4, 'Agua de Jamaica', 'Agua fresca natural', 45.00, 'all_day'),
(1, 4, 'Café Americano', 'Café americano recién hecho', 55.00, 'breakfast');

-- Insert amenities
INSERT INTO amenities (hotel_id, name, description, category, capacity, price, operating_hours_start, operating_hours_end, status) VALUES
(1, 'Spa y Masajes', 'Servicio de spa con masajes relajantes', 'Wellness', 4, 500.00, '09:00:00', '20:00:00', 'available'),
(1, 'Gimnasio', 'Gimnasio completamente equipado', 'Fitness', 20, 0.00, '06:00:00', '22:00:00', 'available'),
(1, 'Piscina', 'Piscina climatizada con área de descanso', 'Recreation', 30, 0.00, '07:00:00', '21:00:00', 'available'),
(1, 'Sala de Juntas', 'Sala equipada para reuniones de negocios', 'Business', 12, 800.00, '08:00:00', '18:00:00', 'available'),
(1, 'Transporte al Aeropuerto', 'Servicio de transporte privado', 'Transport', 4, 300.00, '00:00:00', '23:59:00', 'available');

-- Insert sample service requests
INSERT INTO service_requests (hotel_id, guest_id, room_id, service_type, description, priority, status) VALUES
(1, 6, 1, 'Limpieza', 'Solicito limpieza de habitación', 'medium', 'pending'),
(1, 6, 1, 'Room Service', 'Toallas adicionales', 'low', 'completed');

-- Insert sample notifications
INSERT INTO notifications (hotel_id, user_id, title, message, type) VALUES
(1, 2, 'Nueva Reservación', 'Nueva reservación para habitación 101', 'reservation'),
(1, 3, 'Nuevo Pedido', 'Nuevo pedido para mesa M2', 'order'),
(1, 4, 'Mantenimiento Programado', 'Mesa M5 requiere mantenimiento', 'maintenance');
