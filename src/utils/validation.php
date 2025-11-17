<?php
// Validation functions

class Validator {
    private $errors = [];
    private $data = [];
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || empty(trim($this->data[$field]))) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' alanı zorunludur';
        }
        return $this;
    }
    
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !Security::validateEmail($this->data[$field])) {
            $this->errors[$field] = $message ?? 'Geçerli bir e-posta adresi giriniz';
        }
        return $this;
    }
    
    public function tcNumber($field, $message = null) {
        if (isset($this->data[$field]) && !Security::validateTcNumber($this->data[$field])) {
            $this->errors[$field] = $message ?? 'Geçerli bir TC kimlik numarası giriniz (11 hane)';
        }
        return $this;
    }
    
    public function phone($field, $message = null) {
        if (isset($this->data[$field]) && !Security::validatePhone($this->data[$field])) {
            $this->errors[$field] = $message ?? 'Geçerli bir telefon numarası giriniz';
        }
        return $this;
    }
    
    public function minLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && mb_strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? ucfirst($field) . " en az {$length} karakter olmalıdır";
        }
        return $this;
    }
    
    public function maxLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && mb_strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? ucfirst($field) . " en fazla {$length} karakter olmalıdır";
        }
        return $this;
    }
    
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' sayı olmalıdır';
        }
        return $this;
    }
    
    public function min($field, $min, $message = null) {
        if (isset($this->data[$field]) && $this->data[$field] < $min) {
            $this->errors[$field] = $message ?? ucfirst($field) . " en az {$min} olmalıdır";
        }
        return $this;
    }
    
    public function max($field, $max, $message = null) {
        if (isset($this->data[$field]) && $this->data[$field] > $max) {
            $this->errors[$field] = $message ?? ucfirst($field) . " en fazla {$max} olmalıdır";
        }
        return $this;
    }
    
    public function url($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? 'Geçerli bir URL giriniz';
        }
        return $this;
    }
    
    public function checkbox($field, $message = null) {
        if (!isset($this->data[$field]) || $this->data[$field] !== 'on') {
            $this->errors[$field] = $message ?? 'Bu alanı onaylamanız gerekmektedir';
        }
        return $this;
    }
    
    public function fails() {
        return !empty($this->errors);
    }
    
    public function passes() {
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}

