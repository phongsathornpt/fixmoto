<?php

// Start session
session_start();

// Define base path
define('BASE_PATH', __DIR__ . '/..');

// Load centralized path mapper first (defines all path constants and helpers)
require_once BASE_PATH . '/app/Core/Paths.php';

// Load core classes
require_once core('Env.php');
require_once core('ErrorHandler.php');
require_once core('Database.php');
require_once core('Router.php');
require_once core('Controller.php');
require_once core('Model.php');
require_once core('Validator.php');

// Load environmental variables
Env::load(BASE_PATH . '/.env');

// Register beautiful error handler (set to false in production)
ErrorHandler::register(true);

// Initialize router
$router = new Router();

// Load routes
require_once BASE_PATH . '/config/routes.php';

// Dispatch request
$router->dispatch();
