<?php

include 'data.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('X-Content-Type-Options: nosniff');
    header('Strict-Transport-Security: max-age=63072000');
    header('X-Robots-Tag: noindex, nofollow', true);

    $input = json_decode(file_get_contents('php://input'), true);

    $title = htmlspecialchars($input['title'] ?? '');
    $message = htmlspecialchars($input['message'] ?? '');
    $priority = htmlspecialchars($input['priority'] ?? '');
    $token = htmlspecialchars($input['token'] ?? '');

    if (empty($title) || empty($message) || empty($priority) || empty($token)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    if (!is_numeric($priority) || $priority < 0 || $priority > 10) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid priority value']);
        exit;
    }

    $data = [
        'title' => $title,
        'message' => $message,
        'priority' => $priority
    ];

    $ch = curl_init("$gotifyUrl/message?token=$token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    switch ($httpcode) {
        case 200:
            echo json_encode(['message' => 'Your Message was Submitted']);
            break;
        case 400:
            echo json_encode(['error' => 'Bad Request']);
            break;
        case 401:
            echo json_encode(['error' => 'Unauthorized Error - Invalid Token']);
            break;
        case 403:
            echo json_encode(['error' => 'Forbidden']);
            break;
        case 404:
            echo json_encode(['error' => 'API URL Not Found']);
            break;
        default:
            http_response_code(500);
            echo json_encode(['error' => 'Hmm Something Went Wrong or HTTP Status Code is Missing', 'details' => $curlError ?: $response]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

?>