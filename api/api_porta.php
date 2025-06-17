<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$valor_file = 'files/sensor_porta/valor.txt';

// Ensure the directory exists
if (!file_exists(dirname($valor_file))) {
    mkdir(dirname($valor_file), 0777, true);
}

// Handle POST request (toggle door state)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_state = file_exists($valor_file) ? trim(file_get_contents($valor_file)) : 'Fechada';
    $new_state = ($current_state === 'Fechada') ? 'Aberta' : 'Fechada';
    
    file_put_contents($valor_file, $new_state);
    
    echo json_encode([
        'success' => true,
        'message' => 'Estado da porta atualizado',
        'estado' => $new_state
    ]);
    exit;
}

// Handle GET request (get current door state)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $estado = file_exists($valor_file) ? trim(file_get_contents($valor_file)) : 'Fechada';
    
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