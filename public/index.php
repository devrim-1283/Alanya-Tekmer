<?php
// Minimal working index - Database bağlantısı YOK, sadece çalışıyor mu test

// Health check
if ($_SERVER['REQUEST_URI'] === '/health' || $_SERVER['REQUEST_URI'] === '/health.php') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'time' => time()]);
    exit;
}

// Ping
if ($_SERVER['REQUEST_URI'] === '/ping' || $_SERVER['REQUEST_URI'] === '/ping.php') {
    header('Content-Type: text/plain');
    http_response_code(200);
    echo 'pong';
    exit;
}

// Ana sayfa
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alanya TEKMER</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #2c3e50; }
        .status { 
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        ul { line-height: 1.8; }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Alanya TEKMER - Site Çalışıyor!</h1>
        
        <div class="status">
            ✅ PHP Server: Aktif<br>
            ✅ Port: <?php echo getenv('PORT') ?: '3000'; ?><br>
            ✅ PHP Version: <?php echo PHP_VERSION; ?>
        </div>

        <div class="info">
            <strong>📊 Sistem Bilgileri:</strong><br>
            Environment: <?php echo getenv('APP_ENV') ?: 'development'; ?><br>
            Debug Mode: <?php echo getenv('DEBUG_MODE') === 'true' ? 'Açık' : 'Kapalı'; ?><br>
            Base URL: <?php echo getenv('BASE_URL') ?: 'Not set'; ?>
        </div>

        <h3>🔗 Test Endpoint'leri:</h3>
        <ul>
            <li><a href="/health">/health</a> - Health check (JSON)</li>
            <li><a href="/ping">/ping</a> - Ping test</li>
            <li><a href="/debug.php">/debug.php</a> - Detaylı debug bilgisi</li>
        </ul>

        <h3>📝 Sonraki Adımlar:</h3>
        <ol>
            <li>✅ <strong>Site çalışıyor!</strong></li>
            <li>⏳ Database bağlantısını ekle</li>
            <li>⏳ Cache sistemini ekle</li>
            <li>⏳ Admin panelini ekle</li>
            <li>⏳ Frontend sayfalarını ekle</li>
        </ol>

        <hr>
        <p style="text-align: center; color: #666; margin-top: 30px;">
            <small>Alanya TEKMER © 2024 - Developed by <a href="https://devrimtuncer.com">Devrim Tuncer</a></small>
        </p>
    </div>
</body>
</html>
