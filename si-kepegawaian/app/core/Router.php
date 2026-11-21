<?php
class Router {
    private static $routes = [];
   public static function add($method, $uri, $controller, $action, $middleware = []) {
    self::$routes[] = [
        'method' => strtoupper($method),
        'uri' => $uri,
        'controller' => $controller,
        'action' => $action,
        'middlewares' => $middleware
    ];
   }
   public static function get($uri, $controller, $action, $middleware = []) {
    self::add('GET', $uri, $controller, $action, $middleware);
   }
   public static function post($uri, $controller, $action, $middleware = []) {
    self::add('POST', $uri, $controller, $action, $middleware);
   }
   public static function put($uri, $controller, $action, $middleware = []) {
    self::add('PUT', $uri, $controller, $action, $middleware);
   }
   public static function delete($uri, $controller, $action, $middleware = []) {
    self::add('DELETE', $uri, $controller, $action, $middleware);
   }

   public static function dispatch($requestUri, $requestMethod) {
    $parsedUri = parse_url($requestUri, PHP_URL_PATH);

    if ($requestMethod === 'POST' && isset($_POST['_method'])) { 
        $requestMethod = strtoupper($_POST['_method']);
    } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
        $requestMethod = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
    }
    foreach (self::$routes as $route) {
        if ($route['method'] !== $requestMethod) {
                continue;
            }
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";
            if (preg_match($pattern, $parsedUri, $matches)) {
                array_shift($matches);
                foreach ($route['middlewares'] as $middleware) {
                    if (is_callable($middleware)) {
                        call_user_func($middleware);
                    }
                }
                $controllerClass = $route['controller'];
                $actionName      = $route['action'];
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    if (method_exists($controllerInstance, $actionName)) {
                        call_user_func_array([$controllerInstance, $actionName], $matches);
                        return;
                    }
                }
                Response::error("Action {$actionName} tidak ditemukan pada controller {$controllerClass}", 500);
                return;
            }
    }
    Response::error("Endpoint [{$requestMethod}] {$parsedUri} tidak ditemukan", 404);
   }
}