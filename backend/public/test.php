<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'API funcionando',
    'timestamp' => date('Y-m-d H:i:s'),
    'uri' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD']
]);
