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

function check_session() {
	session_start();
	if (!isset($_SESSION['token'])) {
		http_response_code(401);
		$response['error_message'] = 'Not logged in';
		echo json_encode($response);
		exit();
	}
}

function get_current_user_id() {
	$mysql = db_connect();
	$stmt = $mysql->prepare('SELECT user_id FROM user_token WHERE token = ?');
	$stmt->bind_param('s', $_SESSION['token']);
	$stmt->execute();
	$result = $stmt->get_result();
	if (!$result->num_rows) {
		$mysql->close();
		http_response_code(401);
		$response['error_message'] = 'Invalid token';
		echo json_encode($response);
		exit();
	}
	$record = $result->fetch_assoc();
	return $record['user_id'];
}

function check_json($json) {
	if (!$json) {
		http_response_code(400);
		$response['error_message'] = 'Invalid JSON';
		echo json_encode($response);
		exit();
	}
}

function check_fields($data, $fields) {
	foreach ($fields as $field) {
		if (!array_key_exists($field, $data)) {
			http_response_code(400);
			$response['error_message'] = 'Missing required fields';
			echo json_encode($response);
			exit();
		}
	}
}

function check_array($data) {
	if (!is_array($data)) {
			http_response_code(400);
			$response = [];
			array_push($response, ['error_message' => 'Request body is not ' . 
				'an array']); 
			echo json_encode($response);
			exit();
	}
}
?>
