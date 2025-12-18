<?php
/**
 * Centralized Path Mapper
 * 
 * Defines all application paths and provides helper functions
 * for requiring files from any location.
 * 
 * Usage:
 *   require_once core('Controller.php');
 *   require_once model('Purchase.php');
 *   require_once dto('PurchaseDTO.php');
 */

// ==================== PATH CONSTANTS ====================

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__DIR__));  // app/
}

if (!defined('CORE_PATH')) {
    define('CORE_PATH', APP_PATH . '/Core');
}

if (!defined('CONTROLLER_PATH')) {
    define('CONTROLLER_PATH', APP_PATH . '/Controllers');
}

if (!defined('MODEL_PATH')) {
    define('MODEL_PATH', APP_PATH . '/Models');
}

if (!defined('DTO_PATH')) {
    define('DTO_PATH', APP_PATH . '/DTO');
}

if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . '/Views');
}

// ==================== PATH HELPER FUNCTIONS ====================

/**
 * Get path to Core file
 */
if (!function_exists('core')) {
    function core(string $file): string
    {
        return CORE_PATH . '/' . ltrim($file, '/');
    }
}

/**
 * Get path to Controller file
 */
if (!function_exists('controller')) {
    function controller(string $file): string
    {
        return CONTROLLER_PATH . '/' . ltrim($file, '/');
    }
}

/**
 * Get path to Model file
 */
if (!function_exists('model')) {
    function model(string $file): string
    {
        return MODEL_PATH . '/' . ltrim($file, '/');
    }
}

/**
 * Get path to DTO file
 */
if (!function_exists('dto')) {
    function dto(string $file): string
    {
        return DTO_PATH . '/' . ltrim($file, '/');
    }
}

/**
 * Get path to View file
 */
if (!function_exists('view')) {
    function view(string $file): string
    {
        return VIEW_PATH . '/' . ltrim($file, '/');
    }
}

// ==================== VIEW ALIASES ====================

if (!defined('VIEW_ALIASES')) {
    define('VIEW_ALIASES', [
        '@layout' => VIEW_PATH . '/components/layout/',
        '@components' => VIEW_PATH . '/components/',
        '@pages' => VIEW_PATH . '/pages/',
        '@views' => VIEW_PATH . '/',
        '@ui' => VIEW_PATH . '/components/ui/',
        '@feature' => VIEW_PATH . '/components/feature/',
    ]);
}

// ==================== VIEW HELPER FUNCTIONS ====================

/**
 * Resolve a path alias to an absolute path
 */
if (!function_exists('view_path')) {
    function view_path(string $path): string
    {
        foreach (VIEW_ALIASES as $alias => $realPath) {
            if (str_starts_with($path, $alias)) {
                return str_replace($alias, rtrim($realPath, '/'), $path);
            }
        }
        return VIEW_PATH . '/' . ltrim($path, '/');
    }
}

/**
 * Include a view file using path aliases
 */
if (!function_exists('include_view')) {
    function include_view(string $path, array $data = []): void
    {
        $file = view_path($path);

        if (!file_exists($file)) {
            echo "<!-- View not found: {$path} -->";
            return;
        }

        if (!empty($data)) {
            extract($data);
        }

        require $file;
    }
}

/**
 * Require a view file using path aliases (throws exception if not found)
 */
if (!function_exists('require_view')) {
    function require_view(string $path, array $data = []): void
    {
        $file = view_path($path);

        if (!file_exists($file)) {
            throw new Exception("View not found: {$path} (resolved to: {$file})");
        }

        if (!empty($data)) {
            extract($data);
        }

        require $file;
    }
}

/**
 * Component Helper to render components easily
 */
if (!function_exists('render_component')) {
    function render_component(string $name, array $props = [], string $content = ''): void
    {
        $file = view_path('@components/' . $name . '.php');

        if (file_exists($file)) {
            extract($props);
            require $file;
        } else {
            echo "<!-- Component {$name} not found -->";
        }
    }
}
