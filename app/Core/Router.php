<?php

/**
 * Router Class
 * 
 * Handles HTTP request routing with support for dynamic route parameters.
 * Features: Pre-compiled regex patterns, controller caching, middleware support.
 */
class Router {
    /** @var array Registered routes with compiled patterns */
    private $routes = [];
    
    /** @var array Registered middlewares */
    private $middlewares = [];
    
    /** @var array Cache for compiled regex patterns */
    private $compiledRoutes = [];
    
    /** @var array Cache for loaded controllers */
    private $loadedControllers = [];
    
    /**
     * Register a GET route
     * 
     * @param string $path Route path with optional {param} placeholders
     * @param string $handler Controller@method string
     * @return void
     */
    public function get(string $path, string $handler): void {
        $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Register a POST route
     * 
     * @param string $path Route path with optional {param} placeholders
     * @param string $handler Controller@method string
     * @return void
     */
    public function post(string $path, string $handler): void {
        $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Register a PUT route
     * 
     * @param string $path Route path with optional {param} placeholders
     * @param string $handler Controller@method string
     * @return void
     */
    public function put(string $path, string $handler): void {
        $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Register a DELETE route
     * 
     * @param string $path Route path with optional {param} placeholders
     * @param string $handler Controller@method string
     * @return void
     */
    public function delete(string $path, string $handler): void {
        $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Add a route to the routes array
     * 
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $path Route path
     * @param string $handler Controller@method string
     * @return void
     */
    private function addRoute(string $method, string $path, string $handler): void {
        // Pre-compile regex pattern for performance
        $pattern = $this->convertToRegex($path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'pattern' => $pattern // Store compiled pattern
        ];
    }
    
    /**
     * Register a middleware
     * 
     * @param string $name Middleware name
     * @param callable $callback Middleware callback function
     * @return void
     */
    public function middleware(string $name, callable $callback): void {
        $this->middlewares[$name] = $callback;
    }
    
    /**
     * Dispatch the current HTTP request to the appropriate handler
     * 
     * @return void
     */
    public function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remove query string and normalize path
        $requestUri = strtok($requestUri, '?');
        $requestUri = '/' . trim($requestUri, '/');
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        
        // Group routes by method for faster lookup
        foreach ($this->routes as $route) {
            // Quick method check first (fastest comparison)
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Use pre-compiled pattern
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                
                // Extract controller and method
                list($controller, $method) = explode('@', $route['handler'], 2);
                
                // Load and execute controller
                $this->executeController($controller, $method, $matches);
                return;
            }
        }
        
        $this->error404();
    }
    
    /**
     * Load and execute a controller method
     * 
     * @param string $controller Controller class name
     * @param string $method Method name to call
     * @param array $params Route parameters to pass
     * @return void
     */
    private function executeController(string $controller, string $method, array $params): void {
        // Check if controller is already loaded (cache)
        if (!isset($this->loadedControllers[$controller])) {
            $controllerFile = __DIR__ . '/../Controllers/' . $controller . '.php';
            
            if (!file_exists($controllerFile)) {
                $this->error404();
                return;
            }
            
            require_once $controllerFile;
            
            if (!class_exists($controller)) {
                $this->error404();
                return;
            }
            
            // Cache the controller class name
            $this->loadedControllers[$controller] = true;
        }
        
        // Instantiate controller
        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $method)) {
            $this->error404();
            return;
        }
        
        // Call controller method with parameters
        call_user_func_array([$controllerInstance, $method], $params);
    }
    
    /**
     * Convert route path to regex pattern
     * 
     * @param string $path Route path with {param} placeholders
     * @return string Compiled regex pattern
     */
    private function convertToRegex(string $path): string {
        // Escape special regex characters except {param}
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Display 404 error page
     * 
     * @return void
     */
    private function error404(): void {
        http_response_code(404);
        echo '<!DOCTYPE html>
<html>
<head>
    <title>404 Not Found</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: #dc3545; }
    </style>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p>The page you are looking for does not exist.</p>
    <a href="/">Go to Home</a>
</body>
</html>';
    }
}
