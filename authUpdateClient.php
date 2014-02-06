<?php
/*
 * Auth Client
 * cokeeffe <cokeeffe@gmail.com>
 *
 * Add this code to your application, to connect, auth, and check for verion updates
 *  Response Codes:
 *   0 => "Database Error"
 *   1 => "Authenticated"
 *   2 => "Authentication Error"
 *   3 => "Version up-to-date"
 *   4 => "New Version Available"
 *   5 => "Unknown" 
 */
mysql_connect('host','username','password');
mysql_select_db('database');

function checkForUpdates() {
    $_URL = "http://localhost/"; //end with slash
    $_VERSIONID = 0;
    $_USERKEY = 0;
    $_AUTHSALT = 0;
    $_CUSTOMERID = 0;
      
    //these values should be changed per client install
    $_VERSIONID = 'c4ca4238a0b923820dcc509a6f75849b';
    $_USERKEY = '4195fd5cfaf00ade971f8d933584d2e2';
    $_AUTHSALT = '4b56240622699097749b37f317919259';
    $_CUSTOMERID = 1;
    
    $token = hash_hmac('md5', $_USERKEY, $_AUTHSALT);
    
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $_URL."authUpdateServer.php?customerID=".$_CUSTOMERID."&versionID=$_VERSIONID&authToken=$token",
        CURLOPT_USERAGENT => 'SomethingHere'
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    
    $response = json_decode($resp);
    return $response;
}

$resp = checkForUpdates();
echo $resp->response_code;
echo "</br>";
echo $resp->response_message;
?>