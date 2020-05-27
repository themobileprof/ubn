<?php
/////////////////////////////////////
require_once ('includes/session_mini.php');
require_once ('includes/common.php');
require_once ('includes/process.php');
require_once ('includes/supplies.php');
/////////////////////////////////////
/////////////////////////////////////
// Exit if page doesn't have supply Id
if (empty($_GET['supply_id'])){
  exit();
}


use Process\getBanks;
use Supply\getSupply;

// Get list of banks
$get_banks = new Process\getBanks;

// Get Balance on Paystack
$bal = curl_get ("https://api.paystack.co/balance");
// print_r($bal);

// Get new supply object
$get_supply = new Supply\getSupply($_GET['supply_id']);

// Get information about this supply
$supply = $get_supply->get_supply_details($link);

// Store supply ID and Supplier Id in session
$_SESSION['supply_id'] = $supply['id'];
$_SESSION['supplier_id'] = $supply['supplier_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Bakery Make Payment</title>
  <!-- Bootstrap core CSS-->
  <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Custom fonts for this template-->
  <link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <style>
    /* Hidden Parts of the Form */
    #account_confirm {
      display: none;
    }
    #others {
      display: none;
    }
    .left-inner-addon {
        position: relative;
    }
    .left-inner-addon input {
        padding-left: 40px;    
    }
    .left-inner-addon span {
        position: absolute;
        padding: 7px 12px;
        pointer-events: none;
        font-size: 14px;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Your Current account balance is <strong class="text-danger"><?php echo $bal['data'][0]['currency'].number_format($bal["data"][0]["balance"]/100, 2, ".", ","); ?></strong></div>
      <div class="card-body">
        <small>Pay <strong><?php echo $supply['supplier_name']; ?></strong> <strong class="text-info">N<?php echo number_format($supply['amount'], 2, ".", ","); ?></strong>
         for <?php echo $supply['quantity_desc']; ?> of <strong><?php echo $supply['item_name']; ?></strong></small>
        <hr>
        <form action="transfer.php" method="post">
          <div class="form-group">
            <!-- <label for="bank">Supplier Bank</label> -->
            <select class="form-control" id="bank" name="bank">
              <option>Bank</option>
              <?php
              echo $get_banks->banks();
              ?>
            </select>
          </div>
          <div class="form-group">
            <!-- <label for="account_number">Account Number</label> -->
            <input class="form-control" id="account_number" name="account_number" type="text" placeholder="Account Number">
          </div>
          <div class="form-group">
            <div id="account_confirm" class="font-weight-bold">
              <img src="images/load.gif" alt="loading">
            </div>
          </div>
          <div id="others">
            
            <div class="form-group">
              <label for="amount">Amount</label>
              <div class="input-group left-inner-addon"> 
                  <span>NGN</span>
                  <input type="number" value="<?php echo number_format($supply['amount'], 2, ".", ""); ?>" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="currency" id="amount" name="amount" />
              </div>
            </div>

            <input type="hidden" name="account_name" id="account_name">
            <input type="hidden" name="recipient_code" id="recipient_code">
            <input type="hidden" name="supplier" id="supplier" value="<?php echo $supply['supplier_name']; ?>">
            <input type="hidden" name="reason" id="reason" value="Payment for supply,<?php echo $supply['quantity_desc']; ?> of <strong><?php echo $supply['item_name']; ?>">
            
          </div>
          <input type="submit" class="btn btn-success btn-block" value="Pay Supplier" disabled>
        </form>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <!-- <script src="//code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
  <!-- <script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
    <!-- Core plugin JavaScript-->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script> -->
    <script src="js/jquery.min.js"></script>  
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/confirm_account.js"></script>
</body>

</html>
