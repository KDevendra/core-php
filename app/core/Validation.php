<?php
class Validation {
    protected $errors = [];
    public function validate($data, $rules) {
        foreach ($rules as $field => $ruleset) {
            $rulesArray = explode('|', $ruleset);
            foreach ($rulesArray as $rule) {
                $params = [];
                if (strpos($rule, ':') !== false) {
                    list($rule, $paramString) = explode(':', $rule);
                    $params = explode(',', $paramString);
                }
                $method = 'validate' . str_replace(' ', '', ucwords(str_replace('_', ' ', $rule)));
                if (method_exists($this, $method)) {
                    // Use ternary operator instead of null coalescing operator
                    $this->$method($field, isset($data[$field]) ? $data[$field] : null, $params);
                } else {
                    throw new Exception("Validation rule {$rule} not implemented.");
                }
            }
        }
        return empty($this->errors);
    }
    public function errors() {
        return $this->errors;
    }
    protected function validateRequired($field, $value, $params) {
        if (empty($value)) {
            $this->errors[$field][] = "{$field} is required.";
        }
    }
    protected function validateEmail($field, $value, $params) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "{$field} must be a valid email address.";
        }
    }
    protected function validateMin($field, $value, $params) {
        if (strlen($value) < $params[0]) {
            $this->errors[$field][] = "{$field} must be at least {$params[0]} characters.";
        }
    }
    protected function validateMax($field, $value, $params) {
        if (strlen($value) > $params[0]) {
            $this->errors[$field][] = "{$field} must not exceed {$params[0]} characters.";
        }
    }
    protected function validateAlpha($field, $value, $params) {
        if (!ctype_alpha($value)) {
            $this->errors[$field][] = "{$field} must contain only letters.";
        }
    }
    protected function validateAccepted($field, $value, $params) {
        $acceptedValues = ['yes', 'on', '1', true];
        if (!in_array($value, $acceptedValues, true)) {
            $this->errors[$field][] = "{$field} must be accepted.";
        }
    }
    protected function validateAfter($field, $value, $params) {
        $afterDate = $params[0];
        if (strtotime($value) <= strtotime($afterDate)) {
            $this->errors[$field][] = "{$field} must be a date after {$afterDate}.";
        }
    }
    protected function validateAlphaDash($field, $value, $params) {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            $this->errors[$field][] = "{$field} may only contain letters, numbers, dashes, and underscores.";
        }
    }
    protected function validateBetween($field, $value, $params) {
        $min = $params[0];
        $max = $params[1];
        if (strlen($value) < $min || strlen($value) > $max) {
            $this->errors[$field][] = "{$field} must be between {$min} and {$max} characters.";
        }
    }
    protected function validateBoolean($field, $value, $params) {
        if (!is_bool($value) && !in_array($value, [0, 1, '0', '1'], true)) {
            $this->errors[$field][] = "{$field} must be true or false.";
        }
    }
}
