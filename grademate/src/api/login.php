<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

require __DIR__ . '/db_connection.php'; // Correct path
require __DIR__ . '/website.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(501);
    exit();
}

$data = file_get_contents('php://input');
$data = json_decode($data, true);
check_json($data);

$fields = ['username', 'password'];
check_fields($data, $fields);

$mysql = db_connect();
$stmt = $mysql->prepare('SELECT id, password FROM student WHERE username = ?');
if (!$stmt) {
    error_log("Prepare statement failed: " . $mysql->error);
    http_response_code(500);
    exit();
}
$stmt->bind_param('s', $data['username']);
$stmt->execute();
$result = $stmt->get_result();
if (!$result->num_rows) {
    http_response_code(401);
    $response['error_message'] = 'User does not exist';
    $mysql->close();
    echo json_encode($response);
    exit();
}
$record = $result->fetch_assoc();
if (!password_verify($data['password'], $record['password'])) {
    http_response_code(401);
    $response['error_message'] = 'Password is incorrect';
    $mysql->close();
    echo json_encode($response);
    exit();
}

$token = substr(bin2hex(random_bytes(60)), 0, 60);

$stmt->close();
$stmt = $mysql->prepare('INSERT INTO user_token(user_id, token) VALUES(?, ?)');
if (!$stmt) {
    error_log("Prepare statement failed: " . $mysql->error);
    http_response_code(500);
    exit();
}
$stmt->bind_param('is', $record['id'], $token);
$result = $stmt->execute();
if (!$result) {
    http_response_code(400);
    echo '{}';
    exit();
}

session_set_cookie_params(29030400);
session_start();
$_SESSION['token'] = $token;

$mysql->close();
echo '{}';
?>
