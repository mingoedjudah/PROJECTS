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

$table_name = 'subject_exam';
$column_name = 'exam_total';
$weight_column_name = 'exam_weight';

switch ($request_method) {
case 'GET':
	if (isset($_GET['id'])) {
		show($id, $_GET, $table_name, $column_name, $weight_column_name);
		exit();
	}
	else index($id, $_GET, $table_name, $column_name, $weight_column_name);
	exit();

case 'POST':
	store($id, $data, $table_name, $column_name, $weight_column_name);
	exit();

case 'PUT':
	update($id, $data, $table_name, $column_name, $weight_column_name);
	exit();

case 'DELETE':
	destroy($id, $data, $table_name, $column_name, $weight_column_name);
	exit();

default:
	http_response_code(405);
	exit();
}

function show($id, $data, $table_name, $column_name, $weight_column_name) {
	$fields = ['id', 'subject_id'];
	check_fields($data, $fields);

	$mysql = db_connect();
	$stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND 
		student_id = ?');
	$stmt->bind_param('ii', $data['subject_id'], $id);
	$stmt->execute();
	$result = $stmt->get_result();
	if (!$result->num_rows) {
		$mysql->close();
		http_response_code(404);
		$response['error_message'] = 'Subject id does not exist for this user';
		echo json_encode($response);
		exit();
	}
	$stmt->close();
	$stmt = $mysql->prepare('SELECT * FROM '. $table_name . ' WHERE id = ? AND 
		subject_id = ?');
	$stmt->bind_param('ii', $data['id'], $data['subject_id']);
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

function index($id, $data, $table_name, $column_name, 
	$weight_column_name) {

	$fields = ['subject_id'];
	check_fields($data, $fields);

	$mysql = db_connect();
	$stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND 
		student_id = ?');
	$stmt->bind_param('ii', $data['subject_id'], $id);
	$stmt->execute();
	$result = $stmt->get_result();
	if (!$result->num_rows) {
		http_response_code(404);
		$response['error_message'] = 'Subject id does not exist for this user';
		echo json_encode($response);
		exit();
	}
	$stmt->close();
	$stmt = $mysql->prepare('SELECT * FROM '. $table_name . ' WHERE 
		subject_id = ?');
	$stmt->bind_param('s', $data['subject_id']);
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

function store($id, $data, $table_name, $column_name, $weight_column_name) {
	$json = json_decode($data);
	check_json($json);
	check_array($json);
	$data = json_decode($data, true);

	$response = [];
	$mysql = db_connect();
	foreach ($data as $score_data) {
		$fields = ['subject_id', 'score', 'total'];
		check_fields($score_data, $fields);

		$stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND 
			student_id = ?');
		$stmt->bind_param('ii', $score_data['subject_id'], $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if (!$result->num_rows) {
			$mysql->close();
			http_response_code(404);
			$response = [];
			array_push($response, ['error_message' => 'Subject id does not ' .
				'exist for this user']);
			echo json_encode($response);
			exit();
		}

		$stmt->close();
		$stmt = $mysql->prepare('INSERT INTO '. $table_name . '(subject_id, 
			score, total) VALUES(?, ?, ?)');
		$total = 0;
		$stmt->bind_param('iss', $score_data['subject_id'], 
			$score_data['score'], 
			$score_data['total']);
		$result = $stmt->execute();
		if (!$result) {
			$mysql->close();
			http_response_code(400);
			$response = [];
			array_push($response, ['error_message' => 'Invalid field values']);
			echo json_encode($response);
			exit();
		}
		$stmt->close();
		$stmt = $mysql->prepare('SELECT LAST_INSERT_ID() as id');
		$stmt->execute();
		$result = $stmt->get_result();
		array_push($response, $result->fetch_assoc());
		$stmt->close();
	}

	update_grades($id, $data[0], $table_name, $column_name, 
		$weight_column_name);

	$mysql->close();
	http_response_code(201);
	echo json_encode($response);
	exit();
}

function update($id, $data, $table_name, $column_name, $weight_column_name) {
	$json = json_decode($data);
	check_json($json);
	check_array($json);
	$data = json_decode($data, true);

	$response = [];
	$mysql = db_connect();
	foreach ($data as $score_data) {
		$fields = ['id', 'subject_id'];
		check_fields($score_data, $fields);

		$stmt = $mysql->prepare('SELECT id FROM subject WHERE id = ? AND 
			student_id = ?');
		$stmt->bind_param('ii', $score_data['subject_id'], $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if (!$result->num_rows) {
			$mysql->close();
			http_response_code(404);
			$response = [];
			array_push($response, ['error_message' => 'Subject id do not ' . 
				'exist for this user']);
			echo json_encode($response);
			exit();
		}
		$stmt->close();
		
		$fields = [
			[
				'name' => 'score', 
				'type' => 's'
			],
		]; 

		foreach ($fields as $field) {
			if (!array_key_exists($field['name'], $score_data)) continue; 

			$stmt = $mysql->prepare('UPDATE '. $table_name . ' SET ' . 
				$field['name'] .  ' = ? WHERE id = ? AND subject_id = ?');
			$stmt->bind_param($field['type'] . 'ii', 
				$score_data[$field['name']], $score_data['id'], 
				$score_data['subject_id']);
			$result = $stmt->execute();
			if (!$result) {
				$mysql->close();
				http_response_code(400);
				$response = [];
				array_push($response, ['error_message' => 'Invalid values']); 
				echo json_encode($response);
				exit();
			}
			$stmt->close();
			array_push($response, [
				'id' => $score_data['id'],
				$field['name'] => $score_data[$field['name']],
			]);
		}
	}

	update_grades($id, $data[0], $table_name, $column_name, 
		$weight_column_name);

	$mysql->close();
	http_response_code(201);
	echo json_encode($response);
	exit();
}

function destroy($id, $data, $table_name, $column_name, $weight_column_name) {
	$json = json_decode($data);
	check_json($json);
	check_array($json);
	$data = json_decode($data, true);

	$mysql = db_connect();
	foreach ($data as $score_data) {
		$fields = ['id', 'subject_id'];
		check_fields($score_data, $fields);
		
		$stmt = $mysql->prepare('SELECT id FROM subject WHERE id = ? AND 
			student_id = ?');
		$stmt->bind_param('ii', $score_data['subject_id'], $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if (!$result->num_rows) {
			$mysql->close();
			http_response_code(404);
			$response = [];
			array_push($response, ['error_message' => 'Subject id do not ' . 
				'exist for this user']);
			echo json_encode($response);
			exit();
		}

		$stmt->close();
		$stmt = $mysql->prepare('DELETE FROM '. $table_name . ' WHERE id = ? 
			AND subject_id = ?');
		$stmt->bind_param('ii', $score_data['id'], $score_data['subject_id']);
		$stmt->execute();
		$stmt->close();
	}

	update_grades($id, $data[0], $table_name, $column_name, 
		$weight_column_name);

	$mysql->close();
	http_response_code(200);
	echo '[]';
	exit();
}

function update_grades($id, $data, $table_name, $column_name, 
	$weight_column_name) {
	$mysql = db_connect();
	$stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND 
		student_id = ?');
	$stmt->bind_param('ii', $data['subject_id'], $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$record = $result->fetch_assoc();
	$weight = $record[$weight_column_name];

	$stmt->close();
	$stmt = $mysql->prepare('SELECT * FROM '. $table_name . ' 
		WHERE subject_id = ?');
	$stmt->bind_param('i', $data['subject_id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$total_score = 0;
	$grade = 0;
	$num_scores = $result->num_rows;
	while ($row = $result->fetch_assoc()) {
		$score = ($row['score'] / $row['total']);
		$total_score = ($total_score + $score);
	}
	if ($total_score) {
		$average_score = ($total_score / $num_scores);
		$grade = (float)($average_score * $weight);
	}


	$stmt->close();
	$stmt = $mysql->prepare('UPDATE subject SET ' . $column_name . ' = ? 
		WHERE id = ?');
	$stmt->bind_param('si', $grade, $data['subject_id']);
	$stmt->execute(); 

	$stmt->close();
	$stmt = $mysql->prepare('SELECT * FROM subject WHERE id = ? AND 
		student_id = ?');
	$stmt->bind_param('ii', $data['subject_id'], $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$record = $result->fetch_assoc();
	$final_grade = $record['quiz_total'] + $record['exam_total'] + 
		$record['project_total'] + $record['exercise_total'] + 
		$record['activity_total'];

	$stmt->close();
	$stmt = $mysql->prepare('UPDATE subject SET grand_total = ? WHERE id = ?');
	$stmt->bind_param('si', $final_grade, $data['subject_id']);
	$stmt->execute(); 

	$mysql->close();
}

?>
