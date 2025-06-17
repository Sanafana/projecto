<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$valor_file = 'files/sensor_movimento/valor.txt';

// Ensure the directory exists
if (!file_exists(dirname($valor_file))) {
    mkdir(dirname($valor_file), 0777, true);
}

// Handle POST request (update motion sensor state)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $estado = isset($data['estado']) ? $data['estado'] : 'Inativo';
    
    file_put_contents($valor_file, $estado);
    
    echo json_encode([
        'success' => true,
        'message' => 'Estado do sensor atualizado',
        'estado' => $estado
    ]);
    exit;
}

// Handle GET request (get current motion sensor state)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $estado = file_exists($valor_file) ? trim(file_get_contents($valor_file)) : 'Inativo';
    
    echo json_encode([
        'success' => true,
        'estado' => $estado
    ]);
    exit;
}

// If method not allowed
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Método não permitido'
]); 