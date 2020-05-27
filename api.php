<?php

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;
use App;

// namespace Google\Cloud\Samples\Dialogflow;
require 'vendor/autoload.php';
require_once('App/loader.php');
require_once('includes/processes.php');

$method = $_SERVER['REQUEST_METHOD'];

/**
 * Returns the result of detect intent with texts as inputs.
 * Using the same `session_id` between requests allows continuation
 * of the conversation.
 */
function startSession(&$request)
{
	//$cred = array('credentials' => 'bankbot-kytsdd-14f414c25837.json');
	//$projectId = 'banking-1-anoalk';
	//print_r($request);
	// new session
	$sessionsClient = new SessionsClient();

	//$session = $sessionsClient->sessionName($projectId, $request->session ?: uniqid());

	// API Logic
	if ($request->action == 'input.welcome') {
		$text = "Welcome to Union Bank. How may we be of help?";
	} else if ($request->action == 'verify.account' && array_key_exists('accountNumber', $request->parameters)) {

		$getAccount = new App\getACCount;

		if ($accName = $getAccount->getAcc($request->parameters['accountNumber'])) {
			$text = "The account owner is: " . $accName;
		} else {
			$text = "This doesn't seem like a Union Bank account number";
		}
	} else if ($request->action == "account.balance.check" && array_key_exists("account", $request->parameters)) {

		$getAccount = new App\getACCount;

		$accBalance = $getAccount->getAccBalance($request->parameters['accountNumber'], $request->parameters['account']);

		if (!empty($accBalance['balance'])) {
			$speech = $text = "Your " . $accBalance["account"] . " account balance is N" . $accBalance['balance'];
		}
	} else {
		$speech = $text = "I am sorry, I don't understand you";
	}

	//$fulfilmentText = $queryResult->getFulfillmentText(); // Look at this later

	// Server Reply
	$text2 = new \stdClass();

	$text2->text->text[] = $text;

	$response['fulfillmentMessages'][] = $text2;

	//$response->speech = (!empty($speech)) ? $speech : $text;

	//$response->source = "webhook";

	echo json_encode($response);





	//printf('Fulfilment text: %s' . PHP_EOL, $text);

	$sessionsClient->close();
}


if ($method == "POST") {
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$request = new App\loader($json);

	startSession($request);
} else {
	echo "Failed";
}
