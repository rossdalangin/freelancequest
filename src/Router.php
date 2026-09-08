<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Strip subdirectory prefix if running in XAMPP or subfolder (e.g. /freelancequest/public)
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            if ($baseDir !== '' && $baseDir !== '/' && str_starts_with($path, $baseDir)) {
                $path = substr($path, strlen($baseDir));
            }
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }

        // Match static routes
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $this->callHandler($handler);
            return;
        }

        // Match dynamic routes like /learn/{slug}, /mission/{id}, /verify/{code}, /p/{username}
        foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($handler, $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function callHandler(callable|array $handler, array $params = []): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            call_user_func_array([$controller, $method], $params);
        } else {
            call_user_func_array($handler, $params);
        }
    }
}
