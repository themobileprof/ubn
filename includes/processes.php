<?php

namespace App;

require_once('common.php');
// Get client Account Details
class getACCount
{
	//"name": "Guaranty Trust Bank",
	//"slug": "guaranty-trust-bank",
	//"code": "058",
	//"longcode": "058152036",

	//"name": "Union Bank of Nigeria",
	//"slug": "union-bank-of-nigeria",
	//"code": "032",
	//"longcode": "032080474",

	function getAcc($acc_num, $bank_code = "058")
	{
		$tranx = curl_get("https://api.paystack.co/bank/resolve?account_number=" . $acc_num . "&bank_code=" . $bank_code);

		if (!empty($tranx['data']['account_name'])) {
			return $tranx['data']['account_name'];
		} else {
			return FALSE;
		}
	}

	function getAccBalance($acc_num, $account = "savings")
	{
		$tranx = curl_get("https://api.paystack.co/balance");

		if (!empty($tranx['data']['account_name'])) {
			$bal['balance']  = $tranx['data']['balance'];
			$bal['account'] = str_replace("account", "", $account);

			return $bal;
		} else {
			return FALSE;
		}
	}
}


//$getAccount = new getACCount;

//$accName = $getAccount->getAcc('0427456586');

//echo $accName;
