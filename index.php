<?php
require_once 'src/Uploader.php';

use App\Uploader;

$message = '';
$status = '';

// Обработка запроса (если пришел POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploader = new Uploader(__DIR__ . '/uploads/');
    $result = $uploader->upload($_FILES['file']);
    
    // Возвращаем JSON если запрос асинхронный (AJAX)
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern File Uploader</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="upload-card">
        <div class="header">
            <h2>File Uploader</h2>
            <p>Upload your images or documents safely</p>
        </div>

        <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
            <div class="drop-zone" id="dropZone">
                <span class="material-icons-outlined icon">cloud_upload</span>
                <p>Drag & Drop files here or <span class="browse-btn">Browse</span></p>
                <input type="file" name="file" id="fileInput" hidden>
            </div>
            
            <div class="progress-area" id="progressArea"></div>
            <div class="status-message" id="statusMessage"></div>
        </form>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>