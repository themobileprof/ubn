<?php
namespace Process;

// Get List of Banks with their Bank codes
class getBanks {

    public function banks ($output="dropdown"){
        $out="";
        $tranx = curl_get ("https://api.paystack.co/bank");

        foreach ($tranx['data'] as $banks){
            $out .= $this->$output($banks);
        }
        // redirect to page so User can pay
        // uncomment this line to allow the user redirect to the payment page
        // header('Location: ' . $tranx['data']['authorization_url']);

        return $out;
    }

    function dropdown ($banks){ // Create dropdown of banks list
        return '<option value="'.$banks['code'].'">'.$banks['name'].'</option>'."\n";
    }
}

// Get client Account Details
class getACCount {
    function getAcc ($acc_num, $bank_code){
        $tranx = curl_get("https://api.paystack.co/bank/resolve?account_number=".$acc_num."&bank_code=".$bank_code);

        if (!empty($tranx['data']['account_name'])){
            return $tranx['data']['account_name'];
        } else {
            return FALSE;
        }
    }
}

// Add new or existing recipients
class addRecipient {
    public $dblink;
    private $type = "nuban";
    private $name;
    private $description;
    private $account_number;
    private $bank_code;
    private $currency = "NGN";

    private $data = array();

    function __construct(){
        global $link;
        $this->dblink = $link; // insert the global $link into this class

        if (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['account_number']) && !empty($_POST['bank_code'])){
            $this->name = $this->data['name']= addslash($_POST['name']);
            $this->description = $this->data['description'] = addslash($_POST['description']);
            $this->account_number = $this->data['account_number'] = addslash($_POST['account_number']);
            $this->bank_code = $this->data['bank_code'] = addslash($_POST['bank_code']);
            

            if (!empty($_POST['currency'])){
                $this->currency = $this->data['currency'] = addslash($_POST['currency']);
            }

            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add (){
        $tranx = curl_post('https://api.paystack.co/transferrecipient', $this->data);

        if (!empty($tranx['data']['recipient_code'])){
            $this->update_db($this->dblink, $tranx['data']['recipient_code']); // Update the Suppliers table with Paystack Recipient Code
            return $tranx['data']['recipient_code'];
        } else {
            return FALSE;
        }
    }

    function update_db ($db, $code){
        $sql = "UPDATE `suppliers` SET
        `paystack_recepient_code` = '$code'
        WHERE 
        `id` = '".$_SESSION['supplier_id']."'";
        if (@mysqli_query($db, $sql)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

class initiateTransfer {
    public $dblink;
    private $source = "balance";
    private $reason;
    private $amount;
    private $recipient;
    
    private $data = array();

    function __construct($reason, $amount, $recipient){
        global $link;
        $this->dblink = $link;

        if (!empty($reason) && !empty($amount) && !empty($recipient)){
            $this->reason = $this->data['reason']= addslash($reason);
            $this->amount = $this->data['amount'] = addslash($amount) * 100;
            $this->recipient = $this->data['recipient'] = addslash($recipient);

            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    // curl https://api.paystack.co/transfer \
    // -H "Authorization: Bearer SECRET_KEY" \
    // -H "Content-Type: application/json" \
    // -d '{"source": "balance", "reason": "Calm down", "amount":3794800, "recipient": "RCP_gx2wn530m0i3w3m"}' \
    // -X POST

    function transfer (){
        $tranx = curl_post('https://api.paystack.co/transfer', $this->data);

        if (!empty($tranx['data']['transfer_code'])){
            $this->update_transfer_code($this->dblink, $tranx['data']['transfer_code']); // Add transfer code, useful for confirmation
            return $tranx['data']['transfer_code'];
        } else {
            return "Error: " . $tranx['message'];
        }
    }

    function update_transfer_code ($db, $ref){
        $sql = "UPDATE `supplies` SET
        `transfer_code` = '".$ref."'
        WHERE 
        `id` = '".$_SESSION['supply_id']."'";
        // echo $sql;
        if (@mysqli_query($db, $sql)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}



class finalizeTransfer {
    public $dblink;
    private $transfer_code;
    private $otp;
    
    private $data = array();

    function __construct($transfer_code, $otp){
        global $link;
        $this->dblink = $link;
        
        if (!empty($transfer_code) && !empty($otp)){
            $this->transfer_code = $this->data['transfer_code']= addslash($transfer_code);
            $this->otp = $this->data['otp'] = addslash($otp);

            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    // curl https://api.paystack.co/transfer/finalize_transfer \
    // -H "Authorization: Bearer SECRET_KEY" \
    // -H "Content-Type: application/json" \
    // -d '{"transfer_code": "TRF_vsyqdmlzble3uii", "otp": "928783"}' \
    // -X POST

    function transfer (){
        $tranx = curl_post('https://api.paystack.co/transfer/finalize_transfer', $this->data);

        if (!empty($tranx['data']['reference'])){
            $this->update_db($this->dblink, $tranx['data']['reference']); // Update supplies table 
            return $tranx['data']['reference'];
        } else {
            return "Error: " . $tranx['message'];
        }
    }

    function update_db ($db, $ref){
        $sql = "UPDATE `supplies` SET
        `status` = 'paid',
        `paid` = 'yes',
        `payment_reference_id` = '".$ref."',
        `payment_date` = NOW()
        WHERE 
        `id` = '".$_SESSION['supply_id']."'";
        // echo $sql;
        if (@mysqli_query($db, $sql)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>
