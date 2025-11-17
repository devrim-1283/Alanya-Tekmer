<?php
// Ultra simple ping endpoint - NO dependencies
header('Content-Type: text/plain');
http_response_code(200);
echo 'pong';

