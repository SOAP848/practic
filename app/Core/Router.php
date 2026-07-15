<?php
namespace App\Core;


class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->defineRoutes();
    }

    private function defineRoutes(): void
    {
        // API маршруты
        $this->addRoute('POST', 'api/auth/signin',    'AuthController@signin');
        $this->addRoute('POST', 'api/auth/signup',    'AuthController@signup');
        $this->addRoute('GET',  'api/auth/check',     'AuthController@check');
        $this->addRoute('GET',  'api/auth/logout',    'AuthController@logout');
        $this->addRoute('POST', 'api/booking',        'BookingController@store');
        $this->addRoute('POST', 'api/contact',        'ContactController@store');
        $this->addRoute('GET',  'api/static/{section}','StaticController@get');
        $this->addRoute('GET',  'api/specialities',   'SpecialityController@all');
        $this->addRoute('GET',  'api/menu',           'MenuController@all');
        $this->addRoute('GET',  'api/menu/{category}','MenuController@byCategory');
    }

    private function addRoute(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && $basePath !== '.') {
            $basePath = rtrim($basePath, '/');
            if (strpos($requestUri, $basePath) === 0) {
                $requestUri = substr($requestUri, strlen($basePath));
            }
        }

        $requestUri = trim($requestUri, '/');

       if ($requestUri === '' || $requestUri === 'index.html' || $requestUri === 'index.php') {
            return;
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                $handlerParts = explode('@', $route['handler']);
                $controllerName = 'App\\Controllers\\' . $handlerParts[0];
                $actionName     = $handlerParts[1];

                if (!class_exists($controllerName)) {
                    http_response_code(500);
                    echo json_encode(['error' => "Controller $controllerName not found"]);
                    return;
                }

                $controller = new $controllerName();

                if (!method_exists($controller, $actionName)) {
                    http_response_code(500);
                    echo json_encode(['error' => "Method $actionName not found in $controllerName"]);
                    return;
                }

                // Извлекаем именованные параметры из URL
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                header('Content-Type: application/json');
                $controller->$actionName($params);
                return;
            }
        }

         http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}