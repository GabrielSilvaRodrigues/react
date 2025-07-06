<?php
namespace App\Core;

class Router
{
    private $routes = [];
    
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }
    
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }
    
    public function put($path, $callback)
    {
        $this->routes['PUT'][$path] = $callback;
    }
    
    public function delete($path, $callback)
    {
        $this->routes['DELETE'][$path] = $callback;
    }
    
    public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($callback, $matches);
                return;
            }
        }
        
        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
    }
}
