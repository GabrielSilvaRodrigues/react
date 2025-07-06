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
        
        // Debug: mostrar rota solicitada
        error_log("Rota solicitada: $method $uri");
        
        // Verificar se há rotas registradas para o método
        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo json_encode(['error' => 'Método não suportado', 'method' => $method, 'uri' => $uri]);
            return;
        }
        
        foreach ($this->routes[$method] as $route => $callback) {
            // Escapar caracteres especiais e converter parâmetros
            $pattern = str_replace('/', '\/', $route);
            $pattern = preg_replace('/\{([^}]+)\}/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove a correspondência completa
                try {
                    call_user_func_array($callback, $matches);
                    return;
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Erro interno do servidor: ' . $e->getMessage()]);
                    return;
                }
            }
        }
        
        http_response_code(404);
        echo json_encode([
            'error' => 'Rota não encontrada', 
            'method' => $method, 
            'uri' => $uri,
            'routes_available' => array_keys($this->routes[$method] ?? [])
        ]);
    }
}
