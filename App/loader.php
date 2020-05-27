<?php

namespace App;



class loader
{
	// 
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








	public $queryText;
	public $action;
	public $parameters = [];
	public $fulfillmentText;

	public function __construct($json)
	{
		$this->action = $json->queryResult->action;
		$this->parameters = (array) $json->queryResult->parameters; // Convert to array
		$this->queryText = $json->queryResult->queryText;
		return true;
	}
}
