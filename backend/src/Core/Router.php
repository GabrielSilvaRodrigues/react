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
        
        // Verificar rota exata primeiro
        if (isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];
            if (is_callable($callback)) {
                call_user_func($callback);
                return;
            }
        }
        
        // Verificar rotas com parâmetros
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } else {
                    call_user_func_array($callback, $matches);
                }
                return;
            }
        }
        
        // Rota não encontrada - mostrar rotas disponíveis
        http_response_code(404);
        $availableRoutes = [];
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route => $callback) {
                $availableRoutes[] = "$method $route";
            }
        }
        
        echo json_encode([
            'error' => 'Rota não encontrada',
            'method' => $method,
            'uri' => $uri,
            'routes_available' => array_keys($this->routes[$method] ?? [])
        ]);
    }
}
