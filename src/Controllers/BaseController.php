<?php

namespace App\Controllers;

class BaseController
{
    protected function render(string $template, array $data = [])
    {
        extract($data);

        $templatePath = __DIR__ . "/../Views/{$template}.php";

        if (file_exists($templatePath)) {
            require_once $templatePath;
        } else {
            die("Template not found: {$template}");
        }
    }

    protected function ensureRequiredParam($param, $vars)
    {
        if (
            !array_key_exists($param, $vars)
            || !is_numeric($vars[$param])
        ) {
            header("HTTP/1.0 400 Bad Request");
            echo "400 - Bad Request";
            return false;
        }

        return true;
    }
}
