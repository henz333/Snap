<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'conn/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $photos = $data['photos'] ?? null;

    if (!$photos || !is_array($photos)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No photos provided.']);
        exit;
    }

    $uploadDir = __DIR__ . '/o9/n_e/records/uploads/';

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory.']);
        exit;
    }

    $savedFiles = [];

    foreach ($photos as $index => $dataUrl) {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $ext = strtolower($type[1]);
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($ext, $allowedExt)) {
                continue;
            }
            $data = base64_decode($data);
            if ($data === false) {
                continue;
            }
        } else {
            continue;
        }

        $fileName = uniqid('photo_', true) . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (file_put_contents($filePath, $data) === false) {
            continue;
        }

        try {
            $stmt = $conn->prepare("INSERT INTO uploads (name, hour) VALUES (:name, NOW())");
            $stmt->execute(['name' => $fileName]);
            $savedFiles[] = $fileName;
        } catch (PDOException $e) {
            unlink($filePath);
            continue;
        }
    }

    if (count($savedFiles) === count($photos)) {
        echo json_encode(['status' => 'success', 'message' => 'Photos uploaded successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Error uploading photos.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
