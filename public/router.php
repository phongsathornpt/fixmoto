<?php
/**
 * Optimized Router script for PHP built-in server
 * Handles routing with performance optimizations since .htaccess doesn't work with php -S
 */

// Get the requested URI (already decoded by PHP server)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Security: Prevent directory traversal attacks
if (strpos($uri, '..') !== false) {
    http_response_code(403);
    exit('Forbidden');
}

// Static file extensions that should be served directly
static $staticExtensions = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'json' => 'application/json',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'eot' => 'application/vnd.ms-fontobject',
];

// Quick check: If URI has a file extension, check if it's a static file
if ($uri !== '/' && strpos(basename($uri), '.') !== false) {
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    
    // If it's a known static extension
    if (isset($staticExtensions[$ext])) {
        $filePath = __DIR__ . $uri;
        
        // File exists check with realpath for security
        if (file_exists($filePath) && is_file($filePath)) {
            $realPath = realpath($filePath);
            $baseDir = realpath(__DIR__);
            
            // Ensure file is within public directory (security check)
            if (strpos($realPath, $baseDir) === 0) {
                // Set appropriate content type
                header('Content-Type: ' . $staticExtensions[$ext]);
                
                // Enable browser caching for static files (1 week)
                header('Cache-Control: public, max-age=604800');
                header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT');
                
                // Output file efficiently
                readfile($realPath);
                exit;
            }
        }
        
        // File not found
        http_response_code(404);
        exit('Not Found');
    }
}

// Route all other requests to index.php
require_once __DIR__ . '/index.php';
