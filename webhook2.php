<?php
$method = $_SERVER['REQUEST_METHOD'];


//{
//"responseId": "259ad273-a110-4b85-a775-b62dee4fee6d-0f0e27e1",
//"queryResult": {
//"queryText": "hello",
//"action": "sayHello",
//"parameters": {
//      "sample": "1"
//},
//"allRequiredParamsPresent": true,
//"fulfillmentText": "Hi! Welcome to Union Bank.",
//"fulfillmentMessages": [
//{
//"text": {
//"text": [
//"Hi! Welcome to Union Bank."
//]
//}
//}
//],
//"outputContexts": [
//{
//"name": "projects/bankbot-kytsdd/agent/sessions/dc5d7006-dd9e-0521-5811-b31a1a903d77/contexts/__system_counters__",
//"parameters": {
//"no-input": 0,
//"no-match": 0
//}
//}
//],
//"intent": {
//"name": "projects/bankbot-kytsdd/agent/intents/81b9e6ff-8a59-47a7-a747-0edd2da77167",
//"displayName": "Default Welcome Intent"
//},
//"intentDetectionConfidence": 1,
//"languageCode": "en"
//},
//"originalDetectIntentRequest": {
//"payload": {}
//},
//"session": "projects/bankbot-kytsdd/agent/sessions/dc5d7006-dd9e-0521-5811-b31a1a903d77",
//"alternativeQueryResults": [
//{
//"queryText": "hello",
//"languageCode": "en"
//}
//]
//}








if ($method == "POST") {
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$action = $json->queryResult->action;


	if ($action == 'sayHello') {
		$speech = "Hi, from Union Bank";
		$text = "Hi from Union Bank";
	} else {
		$speech = "I don't understand you";
		$text = "I don't understand you";
	}




	// Server Reply
	$response = new \stdClass();
	$response->speech = $speech;
	$response->fulfillmentText = $text;
	$response->source = "webhook";

	echo json_encode($response);
} else {
	echo "Failed";
}

