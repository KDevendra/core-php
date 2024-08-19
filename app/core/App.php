<?php

class App {
    protected $controller;
    protected $method = 'index';
    protected $params = [];
    protected $config;
    protected $routes;

    public function __construct($config, $routes) {
        $this->config = $config;
        $this->routes = $routes;

        // Ensure routes are defined correctly
        if (!isset($this->routes['web'])) {
            die('Web routes not defined in routes file.');
        }

        if (!isset($this->routes['api'])) {
            die('API routes not defined in routes file.');
        }

        $this->handleRequest();
    }

    private function handleRequest() {
        $url = $this->parseUrl();
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/api/') !== false;

        if ($isApiRequest) {
            $this->handleApiRequest($url);
        } else {
            $this->handleWebRequest($url);
        }
    }

    private function handleApiRequest($url) {
        $routeMatch = $this->matchRoute($url, $this->routes['api']);

        if ($routeMatch) {
            list($controllerName, $methodName) = explode('@', $routeMatch['action']);
            $params = $routeMatch['params'];

            require_once __DIR__ . '/../controllers/api/' . $controllerName . '.php';
            $this->controller = new $controllerName($this->config);

            if (!method_exists($this->controller, $methodName)) {
                die("Method {$methodName} not found in controller {$controllerName}");
            }

            call_user_func_array([$this->controller, $methodName], $params);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'API route not found']);
        }
    }

    private function handleWebRequest($url) {
        $routeMatch = $this->matchRoute($url, $this->routes['web']);

        if ($routeMatch) {
            list($controllerName, $methodName) = explode('@', $routeMatch['action']);
            $params = $routeMatch['params'];

            require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
            $this->controller = new $controllerName($this->config);

            if (!method_exists($this->controller, $methodName)) {
                die("Method {$methodName} not found in controller {$controllerName}");
            }

            call_user_func_array([$this->controller, $methodName], $params);
        } else {
            http_response_code(404);
            echo 'Web route not found';
        }
    }

    private function matchRoute($url, $routes) {
        $urlPath = '/' . implode('/', $url);

        foreach ($routes as $route => $action) {
            // Replace route placeholders with regex patterns
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $route);
            if (preg_match('#^' . $pattern . '$#', $urlPath, $matches)) {
                array_shift($matches);  // Remove the full match
                return [
                    'action' => $action,
                    'params' => $matches
                ];
            }
        }
        return null;
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
