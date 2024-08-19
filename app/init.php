<?php

// Load configuration
$config = require_once __DIR__ . '/../config/config.php';

// Load routes
$routes = require_once __DIR__ . '/routes/routes.php';

// Set the default timezone
date_default_timezone_set($config['timezone']);

// Enable error reporting based on the configuration
if ($config['display_errors']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting($config['error_reporting']);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Load core files
require_once __DIR__ . '/core/DB.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/App.php';
require_once __DIR__ . '/core/Validation.php';

// Make the configuration available globally
$GLOBALS['config'] = $config;

// Initialize the application with the configuration and routes
$app = new App($config, $routes);

