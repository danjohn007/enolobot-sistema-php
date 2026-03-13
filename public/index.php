<?php
// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load core classes
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// Initialize router
new Router();
