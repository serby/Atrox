<?php
$response = new stdClass();
$response->success = false;
$response->headers = apache_request_headers();

if (isset($_POST)) {
	$response->success = true;
	$response->postData = $_POST;
}

echo json_encode($response);