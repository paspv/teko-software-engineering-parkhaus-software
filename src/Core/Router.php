<?php
namespace App\Core;

/**
 * @source class generated using Gemini
 */
class Router 
{
    protected $routes = [];

    public function add($route, $params) 
    {
        // 1. Escape slashes
        $pattern = str_replace('/', '\/', $route);
        // 2. Convert {vars} to named regex capture groups
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $pattern);
        // 3. Add delimiters and start/end anchors
        $pattern = "#^" . $pattern . "/?$#i";
        
        $this->routes[$pattern] = $params;
    }

    public function dispatch(string $uri) 
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes as $pattern => $params) {
            if (preg_match($pattern, $path, $matches)) {
                // Extract only the named variables (garageId, reportId)
                $urlVariables = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $controllerName = $params[0];
                $methodName = $params[1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    return $controller->$methodName($urlVariables);
                }
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 - Page Not Found";
    }
}