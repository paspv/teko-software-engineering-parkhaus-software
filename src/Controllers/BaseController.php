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

    protected function ensureRequiredIntParam(string $param, array $urlVariables)
    {
        if (
            !array_key_exists($param, $urlVariables)
            || !is_numeric($urlVariables[$param])
        ) {
            header("HTTP/1.0 400 Bad Request");
            echo "400 - Bad Request";
            return false;
        }

        return true;
    }

    protected function ensureRequiredFormParam(array $urlVariables, string $identifier) 
    {
        if (!$value = $_POST[$identifier] ?? null) {
            header('Location: /'. $urlVariables['garageId'] . '/entrance');
            die();
        }
        return $value;
    }
}
