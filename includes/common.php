<?php // common.php
///// Disable below in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/// CONSTANTS /////
define('testKey', 'sk_test_65b67291a1f77372d1bd4304145784cf88c9aa0d');

//////////////////////////////////////////////////////////
function curl_get ($url){ 
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"authorization: Bearer ".testKey, 
		"cache-control: no-cache"
	],
	));

	$response = curl_exec($curl);

	$tranx = json_decode($response, true);

	// Close cURL session handle
	curl_close($curl);

	return $tranx;
}

function curl_post ($url, $data){
	$payload = json_encode($data);
        // Prepare new cURL resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "authorization: Bearer ".testKey, 
            "cache-control: no-cache"
        ));

        $tranx = json_decode(curl_exec($ch), true);
        
        // Close cURL session handle
		curl_close($ch);
		
		return $tranx;
}

///////////////////////////////
function error($msg) { // Alert error mesage
   echo "
   <html> 
   <head> 
   
   <script language=\"JavaScript\"> 
   <!-- 
       alert(\"$msg\"); 
       history.back(); 
   //--> 
   </script> 
   </head> 
   <body> 
   </body> 
   </html> 
   ";
   exit(); 
} // End of error
#############################################################################
function addslash ($value,$allowed_tags="") { // Add slashes if magic_quotes_gpc is off (i.e. 0)
	/*
	if (!get_magic_quotes_gpc()):
	   return addslashes(htmlspecialchars ($str));
	else:
		if(strpos(str_replace("\'",""," $str"),"'")!=false):
			return addslashes(htmlspecialchars ($str));
		else:
			return $str;
		endif;
	endif;
	*/
	$value=trim($value);
	if(get_magic_quotes_gpc() || strpos(str_replace("\'",""," $value"),"'")==false)
	{
	$value=stripslashes($value);
	}
	$value=strip_tags($value,$allowed_tags);
	$value=htmlentities($value);
	$value=addslashes($value);
	
	
	return $value;
}
function validate_email ($address){
	// check address format
	$address = stripslashes($address);
	if (!preg_match ('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $address) || empty ($address)) return FALSE;
	//if (preg_match ('/\r/i', $address) || preg_match ('/\n/i', $address)) return FALSE;
	
	return $address;
}
#############################################################################
function random_str($numchar) {
   $str = bin2hex( MD5( time(), TRUE ) );
   $start = mt_rand(1, (strlen($str)-$numchar));
   $suff_str = str_shuffle($str);
   $encr_str = substr($suff_str,$start,$numchar);
   return($encr_str);
}
#############################################################################
function get_number ($number){ // Properly format phone numbers. There is a better way
	$number = "0".substr($number,-10);
	return $number;
}
#############################################################################
function get_pass ($password=""){ // Password Hash
	if (empty($password)):
		$password = substr (MD5(time()), 0, 6);
	endif;
	
	$pass['txt'] = $password;
	
	$pass['p'] = MD5($password);
	
	//$pass['h'] = sha1(mt_rand());
	
	//$pass['p'] = fSalt.$password.$pass['h'];
	
	return $pass;
}



?>