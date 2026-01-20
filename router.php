<?php
// 1. Set Header CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

// 2. Tangani Preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 3. Cari file yang diminta
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $uri;

// Cek apakah file ada, jika tidak, coba hapus prefix /api di path
if (!is_file($file)) {
    $cleanPath = str_replace('/api/', '/', $uri);
    $file = __DIR__ . $cleanPath;
}

// 4. Sajikan file jika ketemu
if (is_file($file)) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if ($ext == 'json') header('Content-Type: application/json');
    if ($ext == 'js') header('Content-Type: application/javascript');
    
    readfile($file);
    exit;
}

// Jika file benar-benar tidak ada
http_response_code(404);
echo json_encode(["error" => "File not found: " . $uri]);
