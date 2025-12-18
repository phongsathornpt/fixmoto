<?php

/**
 * Request Validator
 * 
 * Validates JSON body, query parameters, and form data against defined rules.
 * 
 * Usage:
 *   $validator = new Validator();
 *   $validator->body(['name' => 'required|string|min:3']);
 *   if (!$validator->validate()) {
 *       return $this->json(['errors' => $validator->errors()], 422);
 *   }
 *   $data = $validator->validated();
 */
class Validator {
    private array $rules = [];
    private array $data = [];
    private array $errors = [];
    private array $validated = [];
    private string $source = 'body';

    /**
     * Set validation rules for JSON body
     */
    public function body(array $rules): self {
        $this->source = 'body';
        $this->rules = $rules;
        $this->data = $this->getJsonBody();
        return $this;
    }

    /**
     * Set validation rules for query parameters
     */
    public function query(array $rules): self {
        $this->source = 'query';
        $this->rules = $rules;
        $this->data = $_GET;
        return $this;
    }

    /**
     * Set validation rules for form data
     */
    public function form(array $rules): self {
        $this->source = 'form';
        $this->rules = $rules;
        $this->data = $_POST;
        return $this;
    }

    /**
     * Run validation
     */
    public function validate(): bool {
        $this->errors = [];
        $this->validated = [];

        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            $isNullable = in_array('nullable', $rules);

            // Skip validation if nullable and empty
            if ($isNullable && ($value === null || $value === '')) {
                $this->validated[$field] = $value;
                continue;
            }

            foreach ($rules as $rule) {
                if ($rule === 'nullable') continue;

                $error = $this->applyRule($field, $value, $rule);
                if ($error !== null) {
                    $this->errors[$field][] = $error;
                }
            }

            // Only add to validated if no errors
            if (!isset($this->errors[$field])) {
                $this->validated[$field] = $value;
            }
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors
     */
    public function errors(): array {
        return $this->errors;
    }

    /**
     * Get validated data
     */
    public function validated(): array {
        return $this->validated;
    }

    /**
     * Get a single validated field
     */
    public function get(string $field, $default = null) {
        return $this->validated[$field] ?? $default;
    }

    /**
     * Apply a single validation rule
     */
    private function applyRule(string $field, $value, string $rule): ?string {
        // Parse rule with parameter (e.g., min:3, in:a,b,c)
        $parts = explode(':', $rule, 2);
        $ruleName = $parts[0];
        $param = $parts[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if ($value === null || $value === '') {
                    return "{$field} is required";
                }
                break;

            case 'string':
                if ($value !== null && !is_string($value)) {
                    return "{$field} must be a string";
                }
                break;

            case 'integer':
                if ($value !== null && !filter_var($value, FILTER_VALIDATE_INT)) {
                    return "{$field} must be an integer";
                }
                break;

            case 'numeric':
                if ($value !== null && !is_numeric($value)) {
                    return "{$field} must be numeric";
                }
                break;

            case 'email':
                if ($value !== null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "{$field} must be a valid email";
                }
                break;

            case 'min':
                if ($param !== null && $value !== null) {
                    $min = (int) $param;
                    if (is_string($value) && strlen($value) < $min) {
                        return "{$field} must be at least {$min} characters";
                    }
                    if (is_numeric($value) && $value < $min) {
                        return "{$field} must be at least {$min}";
                    }
                }
                break;

            case 'max':
                if ($param !== null && $value !== null) {
                    $max = (int) $param;
                    if (is_string($value) && strlen($value) > $max) {
                        return "{$field} must not exceed {$max} characters";
                    }
                    if (is_numeric($value) && $value > $max) {
                        return "{$field} must not exceed {$max}";
                    }
                }
                break;

            case 'in':
                if ($param !== null && $value !== null) {
                    $allowed = explode(',', $param);
                    if (!in_array($value, $allowed)) {
                        return "{$field} must be one of: " . implode(', ', $allowed);
                    }
                }
                break;

            case 'regex':
                if ($param !== null && $value !== null) {
                    if (!preg_match($param, $value)) {
                        return "{$field} format is invalid";
                    }
                }
                break;

            case 'array':
                if ($value !== null && !is_array($value)) {
                    return "{$field} must be an array";
                }
                break;

            case 'boolean':
                if ($value !== null && !is_bool($value) && !in_array($value, [0, 1, '0', '1', 'true', 'false'], true)) {
                    return "{$field} must be a boolean";
                }
                break;
        }

        return null;
    }

    /**
     * Parse JSON body from request
     */
    private function getJsonBody(): array {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') === false) {
            return [];
        }

        $rawBody = file_get_contents('php://input');
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
     * Static factory for quick validation
     */
    public static function make(array $rules, string $source = 'body'): self {
        $validator = new self();
        
        switch ($source) {
            case 'query':
                return $validator->query($rules);
            case 'form':
                return $validator->form($rules);
            default:
                return $validator->body($rules);
        }
    }
}
