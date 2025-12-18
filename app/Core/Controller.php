<?php

/**
 * Base Controller Class
 *
 * Provides common functionality for all controllers including:
 * - View rendering
 * - Response handling (redirects, JSON)
 * - Authentication helpers
 * - Request data access
 */
class Controller
{
    /**
     * Render a view file with data
     *
     * @param string $viewPath Path to view relative to Views/pages/
     * @param object|array $data Data to pass to the view (ViewData object or array)
     * @return void
     * @throws Exception When view file is not found
     */
    protected function view(string $viewPath, object|array $data = []): void
    {
        // Support both ViewData objects and arrays
        if (is_object($data)) {
            // Extract $data for typed access: $data->property
            $viewData = ['data' => $data];
            // Also extract individual properties for backward compatibility: $property
            foreach (get_object_vars($data) as $key => $value) {
                $viewData[$key] = $value;
            }
            extract($viewData);
        } else {
            extract($data);
        }

        // Build view file path (prefix with pages/ as per architectural refactor)
        $viewFile = __DIR__ . "/../Views/pages/" . $viewPath . ".php";

        if (!file_exists($viewFile)) {
            throw new Exception("View not found: " . $viewPath);
        }

        require $viewFile;
    }

    /**
     * Redirect to a different path
     *
     * @param string $path URL path to redirect to
     * @return never
     */
    protected function redirect(string $path): never
    {
        header("Location: " . $path);
        exit();
    }

    /**
     * Send JSON response
     *
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return never
     */
    protected function json(mixed $data, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    /**
     * Check if user is authenticated
     *
     * @return bool True if user is logged in
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION["status"]) && $_SESSION["status"] === "login";
    }

    /**
     * Require authentication for the current request
     * Redirects to login if not authenticated
     *
     * @return void
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect("/");
            exit();
        }
    }

    /**
     * Get value from POST data
     *
     * @param string $key Key to retrieve
     * @param mixed $default Default value if key not found
     * @return mixed The POST value or default
     */
    protected function getPost(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get value from GET data
     *
     * @param string $key Key to retrieve
     * @param mixed $default Default value if key not found
     * @return mixed The GET value or default
     */
    protected function getGet(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get parsed JSON body from request
     *
     * @return array
     */
    protected function getJsonBody(): array
    {
        $contentType = $_SERVER["CONTENT_TYPE"] ?? "";

        if (strpos($contentType, "application/json") === false) {
            return [];
        }

        $rawBody = file_get_contents("php://input");
        if (empty($rawBody)) {
            return [];
        }

        $decoded = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $decoded ?? [];
    }

    /**
     * Validate request data
     *
     * @param array $rules Validation rules
     * @param string $source 'body', 'query', or 'form'
     * @return Validator
     */
    protected function validate(array $rules, string $source = "body"): array
    {
        $validator = Validator::make($rules, $source);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            $firstError = reset($errors)[0] ?? "Validation failed";
            throw new Exception($firstError);
        }

        return $validator->validated();
    }
}
