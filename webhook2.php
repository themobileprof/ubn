<?php
require_once('App/loader.php');
require_once('includes/processes.php');

use App;

$method = $_SERVER['REQUEST_METHOD'];


// Don't process if not POST
if ($method == "POST") {
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$request = new App\loader($json);




	// API Logic
	if ($request->action == 'sayHello') {
		$speech = "Hi, from Union Bank";
		$text = "Hi there, from Union Bank";
	} else if ($request->action == 'getAccountName' && array_key_exists('accountNumber', $request->parameters)) {

		$getAccount = new App\getACCount;

		if ($accName = $getAccount->getAcc($request->parameters['accountNumber'])) {
			$text = $accName;
			$speech = $accName;
		}
	} else if ($request->action == "account.balance.check" && array_key_exists("account", $request->parameters)) {

		$getAccount = new App\getACCount;

		$accBalance = $getAccount->getAccBalance($request->parameters['accountNumber'], $request->parameters['account']);

		if (!empty($accBalance['balance'])) {
			$text = "Your " . $accBalance["account"] . " account balance is N" . $accBalance['balance'];
			$speech = $text;
		}
	} else {
		$text = "I am sorry, I don't understand you";
	}




	// Server Reply
	$response = new \stdClass();

	$response->fulfillmentText = $text;

	$response->speech = (!empty($speech)) ? $speech : $text;

	$response->source = "webhook";

	echo json_encode($response);
} else {
	echo "Failed";
}
