<?php
// For Debug // Uncomment this in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo "Seen";
///////////////
//require_once ('includes/session_mini.php'); // Mini Session
//require_once ('includes/db.php'); // Database link
require_once('includes/common.php'); // contains commonly used functions and constants
require_once('includes/process.php'); // Contains the Central Processing file

use Process\initiateTransfer; // Class for Initiating a Transfer
use Process\finalizeTransfer; // Class for Finalizing a Transfer

if (!empty($_POST['otp'])) { // If user submitted an OTP for The Tranfer

	if ($finalize_transfer = new Process\finalizeTransfer($_POST['t_code'], $_POST['otp'])) {
		$ref_code = $finalize_transfer->transfer();
		if (substr($ref_code, 0, 5) == "Error") {
			echo $ref_code;
			exit();
		}

		// HTML displayed on success . # Test this after you transfer some money!!!
		$html = '
    <div class="card-body">
        <img src="images/success.gif" alt="Transfer Successful" class="img-fluid">
    </div>
    <div class="card-footer">
      Your transfer was successful <a href="index.php" class="btn btn-info" target="_top">Continue</a>
    </div>
    ';
	}
} elseif (!empty($_POST['recipient_code'])) { // If user Initiated a Transfer (The only time this page is loaded with $_POST['recipient_code'])

	if ($initiate_transfer = new Process\initiateTransfer($_POST['reason'], $_POST['amount'], $_POST['recipient_code'])) {
		$code = $initiate_transfer->transfer();
		if (substr($code, 0, 5) == "Error") {
			echo $code;
			exit();
		}
	} else {
		echo "Some Required Fields are absent, please try again";
		exit();
	}


	// Display page for OTP input
	$html = '
  <div class="card-header">Input the <strong class="text-info">OTP</strong> code that will be sent to you shortly</div>
      <div class="card-body">
      <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
          <div class="form-group">
            <input class="form-control" id="otp" name="otp" type="text" pattern="[0-9]{6}">
          </div>
          <input type="hidden" name="t_code" id="t_code" value="' . $code . '">
          <input type="submit" class="btn btn-success btn-block" value="Authorize Payment">
        </form>
      </div>
  ';
} else {
	header("Location: pay.php?supply_id=" . $_SESSION['supply_id']);
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Fund Transfer</title>
	<!-- Bootstrap core CSS-->
	<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- Custom fonts for this template-->
	<link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!-- Custom styles for this template-->
	<link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-light">
	<div class="container">
		<div class="card card-login mx-auto mt-5">
			<?php
			echo $html;
			?>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/jquery.easing.min.js"></script>
</body>

</html>
