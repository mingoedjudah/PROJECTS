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

check_session();
$id = get_current_user_id();	

switch ($request_method) {
case 'GET':
    if (!empty($_GET)) {
        show($id, $_GET);
        exit();
    }
    index($id);
    exit();

case 'POST':
    store($id, $data);
    exit();

case 'PUT':
    update($id, $data);
    exit();

case 'DELETE':
    destroy($id, $data);
    exit();

default:
    http_response_code(405);
    exit();
}

function show($id, $data) {
    $fields = ['id'];
    check_fields($data, $fields);

    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND 
        student_id = ?');
    $stmt->bind_param('ii', $data['id'], $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->num_rows) {
        $mysql->close();
        http_response_code(404);
        echo '{}';
        exit();
    }
    $record = $result->fetch_assoc();
    echo json_encode($record);
    exit();
}

function index($id) {
    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT * FROM subject WHERE student_id = ?');
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $records = [];
    while ($row = $result->fetch_assoc()) {
        array_push($records, $row);    
    }
    $mysql->close();
    echo json_encode($records);
    exit();
}

function store($id, $data) {
    $data = json_decode($data, true);
    check_json($data);

    $fields = ['subject_name', 'quiz_weight', 'activity_weight', 'exam_weight', 
        'project_weight', 'exercise_weight'];
    check_fields($data, $fields);

    $total_weight = $data['quiz_weight'] + $data['activity_weight'] + 
        $data['exam_weight'] + $data['project_weight'] + 
        $data['exercise_weight'];

    if ($total_weight > 100) {
        http_response_code(400);
        $response['error_message'] = 'Total weight of assessments cannot exceed 100';
        echo json_encode($response);
        exit();
    }

    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT id FROM subject WHERE subject_name = ?');
    $stmt->bind_param('s', $data['subject_name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $mysql->close();
        http_response_code(400);
        $response['error_message'] = 'Subject name is already used';
        echo json_encode($response);
        exit();
    }

    $stmt->close();
    $stmt = $mysql->prepare('INSERT INTO subject(subject_name, student_id,
        quiz_weight, activity_weight, exam_weight, project_weight, 
        exercise_weight, quiz_total, activity_total, exam_total, project_total,
        exercise_total, grand_total) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
        ?, ?)');
    $total = 0;
    $stmt->bind_param('sisssssssssss', $data['subject_name'], $id, 
        $data['quiz_weight'], $data['activity_weight'], $data['exam_weight'], 
        $data['project_weight'], $data['exercise_weight'], $total, $total, $total, $total, $total, $total); 
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

    $fields = ['id'];
    check_fields($data, $fields);

    $total_weight = $data['quiz_weight'] + $data['activity_weight'] + 
        $data['exam_weight'] + $data['project_weight'] + 
        $data['exercise_weight'];

    if ($total_weight > 100) {
        http_response_code(400);
        $response['error_message'] = 'Total weight of assessments cannot exceed 100';
        echo json_encode($response);
        exit();
    }

    $mysql = db_connect();
    
    $fields = [
        [
            'name' => 'subject_name', 
            'type' => 's'
        ],
        [
            'name' => 'quiz_weight', 
            'type' => 's'
        ],
        [
            'name' => 'activity_weight', 
            'type' => 's'
        ],
        [
            'name' => 'exam_weight', 
            'type' => 's'
        ],
        [
            'name' => 'project_weight', 
            'type' => 's'
        ],
        [
            'name' => 'quiz_weight', 
            'type' => 's'
        ],
        [
            'name' => 'exercise_weight', 
            'type' => 's'
        ],
    ]; 

    foreach ($fields as $field) {
        if (!array_key_exists($field['name'], $data)) continue; 

        $stmt = $mysql->prepare('UPDATE subject SET ' . $field['name'] . 
            ' = ? WHERE id = ? AND student_id = ?');
        $stmt->bind_param($field['type'] . 'ii', $data[$field['name']], 
            $data['id'], $id);
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

function destroy($id, $data) {
    $data = json_decode($data, true);
    check_json($data);

    $fields = ['id'];
    check_fields($data, $fields);
    
    $mysql = db_connect();
    $stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND student_id = ?');
    $stmt->bind_param('ii', $data['id'], $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result->num_rows) {
        $mysql->close();
        http_response_code(404);
        echo '{}';
        exit();
    }
    
    $stmt->close();

    $stmt = $mysql->prepare('DELETE FROM subject WHERE id = ? AND student_id = ?');
    $stmt->bind_param('ii', $data['id'], $id);
    $stmt->execute();
    $mysql->close();
    
    http_response_code(200);
    echo '{}';
    exit();
}


?>
