<?php
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST"){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$text = $json->result->parameters->text;


	if ($text == 'sayHello'){
		$speech = "Hi, from Union Bank";
		$displayText = "Hi from Union Bank";
	}
	else
	{
		$speech = "I don't understand you";
		$displayText = "I don't understand you";

	}




	// Server Reply
	$response = new \stdClass();
	$response->speech = $speech;
	$response->displayText = $displayText;
	$response->source = "webhook";

	echo json_encode($response);
} 
else
{
	echo "Failed";
}

