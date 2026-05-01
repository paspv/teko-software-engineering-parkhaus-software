<?php
namespace App\Controllers;

class BaseController {
    public function render(string $template, array $data = []) {
        extract($data);

        $templatePath = __DIR__ . "/../Views/{$template}.php";
        
        if (file_exists($templatePath)) {
            require_once $templatePath;
        } else {
            die("Template not found: {$template}");
        }
    }
}