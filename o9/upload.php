<?php
// upload.php

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/conn/db_conn.php';  // fixed path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photosJson = $_POST['photos'] ?? '';
    $photos = json_decode($photosJson, true);

    if (!is_array($photos) || count($photos) !== 4) {
        http_response_code(400);
        echo json_encode(['error' => 'Exactly 4 photos are required']);
        exit;
    }

    $uploadDir = __DIR__ . '/o9/n_e/records/uploads/';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create upload directory']);
            exit;
        }
    }

    $conn = getDbConnection();

    $savedFiles = [];

    foreach ($photos as $index => $dataUrl) {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $imageType = strtolower($type[1]);

            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid image type: ' . $imageType]);
                exit;
            }

            $base64Str = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $imageData = base64_decode($base64Str);

            if ($imageData === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Base64 decoding failed']);
                exit;
            }

            $filename = uniqid('img_', true) . '.' . $imageType;
            $filePath = $uploadDir . $filename;

            if (file_put_contents($filePath, $imageData) === false) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to save file']);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO uploaded_photos (image_name, uploaded_at) VALUES (?, NOW())");
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(['error' => 'Database prepare error: ' . $conn->error]);
                exit;
            }

            $stmt->bind_param('s', $filename);

            if (!$stmt->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'Database insert error: ' . $stmt->error]);
                exit;
            }

            $stmt->close();
            $savedFiles[] = $filename;
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid image data format']);
            exit;
        }
    }

    $conn->close();

    echo json_encode(['success' => true, 'files' => $savedFiles]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
