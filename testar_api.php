<?php
// Test script for door API

function testDoorAPI() {
    $api_url = 'http://localhost/projecto/api/api_porta.php';
    
    echo "Testing Door API...\n\n";
    
    // Test GET request
    echo "1. Testing GET request...\n";
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);
    echo "Current door state: " . $data['estado'] . "\n\n";
    
    // Test POST request
    echo "2. Testing POST request (toggling door state)...\n";
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => ''
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    $data = json_decode($response, true);
    echo "New door state: " . $data['estado'] . "\n\n";
    
    // Verify the change with another GET request
    echo "3. Verifying state change with GET request...\n";
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);
    echo "Current door state: " . $data['estado'] . "\n";
}

// Run the tests
testDoorAPI();
?> 