<?php
// Health check endpoint for Coolify
// Returns 200 OK if application is running

header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'healthy',
    'timestamp' => time(),
    'app' => 'Alanya TEKMER'
]);

