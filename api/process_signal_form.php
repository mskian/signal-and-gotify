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

    $message = htmlspecialchars($input['message'] ?? '');
    $number = htmlspecialchars($input['number'] ?? '');
    $recipients = array_map('htmlspecialchars', $input['recipients'] ?? []);

    if (empty($message) || empty($number) || empty($recipients)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    if (!preg_match('/^\+\d{1,15}$/', $number) || !all_valid_recipients($recipients)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid phone number format']);
        exit;
    }

    $data = [
        'message' => $message,
        'number' => $number,
        'recipients' => $recipients
    ];

    $ch = curl_init("$signalUrl/v2/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);

    curl_close($ch);

    $response_data = json_decode($response, true);

    if ($curlError) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to send message', 'details' => $curlError]);
    } elseif (is_successful_api_response($response_data)) {
        echo json_encode([
            'message' => 'Message sent successfully',
            'timestamp' => time() * 1000
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to send message', 'details' => $response]);
    }

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

function all_valid_recipients($recipients) {
    foreach ($recipients as $recipient) {
        if (!preg_match('/^\+\d{1,15}$/', $recipient)) {
            return false;
        }
    }
    return true;
}

function is_successful_api_response($response_data) {
    return isset($response_data['timestamp']);
}

?>