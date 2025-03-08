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

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/website.php';
header('Content-Type: application/json');

$data = file_get_contents('php://input');
$request_method = $_SERVER['REQUEST_METHOD'];
$id = 0;

if ($request_method !== 'POST') {
    check_session();
    $id = get_current_user_id();    
}

switch ($request_method) {
    case 'GET':
        show($id);
        exit();

    case 'POST':
        store($data);
        exit();

    case 'PUT':
        update($id, $data);
        exit();

    case 'DELETE':
        destroy($id);
        exit();

    default:
        http_response_code(405);
        exit();
}

function show($id) {
    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT * FROM student WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    if (!$record) {
        $mysql->close();
        http_response_code(404);
        echo '{}';
        exit();
    }
    echo json_encode($record);
    exit();
}

function index() {
    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT * FROM student');
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->num_rows) {
        http_response_code(404);
        echo json_encode([]);
        exit();
    }
    $data = [];
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);    
    }
    $mysql->close();
    echo json_encode($data);
    exit();
}

function store($data) {
    $data = json_decode($data, true);
    check_json($data);

    $fields = ['first_name', 'middle_name', 'surname', 'email', 'birthdate', 
        'gender', 'password', 'university', 'academic_level', 'username']; 
    check_fields($data, $fields);

    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT id FROM student WHERE username = ?');
    $stmt->bind_param('s', $data['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $mysql->close();
        http_response_code(400);
        $response['error_message'] = 'Username not available';
        echo json_encode($response);
        exit();
    }

    $stmt = $mysql->prepare('SELECT id FROM student WHERE email = ?');
    $stmt->bind_param('s', $data['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $mysql->close();
        http_response_code(400);
        $response['error_message'] = 'Email is already used';
        echo json_encode($response);
        exit();
    }

    $stmt->close();
    $stmt = $mysql->prepare('INSERT INTO student(first_name, middle_name, 
        surname, email, birthdate, gender, password, university, 
        academic_level, username) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssssssis', $data['first_name'], $data['middle_name'], 
        $data['surname'], $data['email'], $data['birthdate'], $data['gender'], 
        $data['password'], $data['university'], $data['academic_level'], 
        $data['username']);
    $result = $stmt->execute();
    if (!$result) {
        $mysql->close();
        http_response_code(400);
        $response['error_message'] = 'Invalid field values';
        echo json_encode($response);
        exit();
    }
    $stmt->close();
    $stmt = $mysql->prepare('SELECT LAST_INSERT_ID() as id');
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_assoc();
    $mysql->close();
    http_response_code(201);
    echo json_encode($response);
    exit();
}

function update($id, $data) {
    $data = json_decode($data, true);
    check_json($data);

    $mysql = db_connect();
    
    $fields = [
        [
            'name' => 'first_name', 
            'type' => 's'
        ],
        [
            'name' => 'middle_name', 
            'type' => 's'
        ],
        [
            'name' => 'surname', 
            'type' => 's'
        ],
        [
            'name' => 'email', 
            'type' => 's'
        ],
        [
            'name' => 'birthdate', 
            'type' => 's'
        ],
        [
            'name' => 'gender', 
            'type' => 's'
        ],
        [
            'name' => 'password', 
            'type' => 's'
        ],
        [
            'name' => 'university', 
            'type' => 's'
        ],
        [
            'name' => 'academic_level',
            'type' => 'i',
        ]
    ]; 

    foreach ($fields as $field) {
        if (!array_key_exists($field['name'], $data)) continue; 

        $stmt = $mysql->prepare('UPDATE student SET ' . $field['name'] . 
            ' = ? WHERE id = ?');
        $stmt->bind_param($field['type'] . 'i', $data[$field['name']], 
            $id);
        $result = $stmt->execute();
        if (!$result) {
            $mysql->close();
            http_response_code(400);
            echo '{}';
            exit();
        }
        $stmt->close();
        $response[$field['name']] = $data[$field['name']];
    }

    $mysql->close();
    http_response_code(201);
    echo json_encode($response);
    exit();
}

function destroy($id) {
    $data = json_decode($data, true);
    
    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT * FROM student WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->num_rows) {
            $mysql->close();
            http_response_code(404);
            echo '{}';
            exit();
    }
    $stmt->close();

    $stmt = $mysql->prepare('DELETE FROM student WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $mysql->close();
    http_response_code(200);
    echo '{}';
    exit();
}

?>
