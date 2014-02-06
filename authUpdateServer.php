<?php
/*
 * Auth Server
 * cokeeffe <cokeeffe@gmail.com>
 *
 * Class authUser to handle auth requests, check for updates, send response in JSON.
 * 
 * Table Structure for authentication:
         CREATE TABLE IF NOT EXISTS `authUpdates` (
          `intID` int(11) NOT NULL AUTO_INCREMENT,
          `txtCustomerName` text NOT NULL,
          `intCustomerID` int(11) NOT NULL,
          `boolActive` tinyint(1) NOT NULL,
          `txtUserKey` text NOT NULL,
          `txtAuthSalt` text NOT NULL,
          `txtLastVersionID` text NOT NULL,
          `dateLastVersionDate` datetime NOT NULL,
          PRIMARY KEY (`intID`)
        ) ;
        
 * Table Structure for version values:
        CREATE TABLE IF NOT EXISTS `authVersion` (
          `txtCurrentVersionID` text NOT NULL
        ) ;
 */
class authUser
{
    private $db;
    private $userKey;
    private $authToken;
    private $authSalt;
    private $isAuth;
    private $customerID;
    private $responseCode;
    
    function __construct() {
        $this->db = mysql_connect('host','user','password');
        $this->db = mysql_select_db('database', $this->db);
        $this->isAuth = false;
        $this->responseCode = 5; //oops
    }
    
    function __destruct() {
        //
    }
    
    function getResponseMessage($key) {
        $codes = array(0 => "Database Error",
                       1 => "Authenticated",
                       2 => "Authentication Error",
                       3 => "Version up-to-date",
                       4 => "New Version Available",
                       5 => "Unknown");
                       
        return $codes[$key];
    }
    
    function checkAuth() {
        if(isset($_GET['authToken'])) {
             $this->authToken = mysql_escape_string($_GET['authToken']);
        }
        
        if(isset($_GET['customerID']) && is_numeric($_GET['customerID'])) {
            $this->customerID = (int)$_GET['customerID'];
        }
       
        //compute the auth
        if(isset($this->authToken) && isset($this->customerID)) {
            $query = mysql_query("SELECT txtUserKey, txtAuthSalt FROM authUpdates WHERE intCustomerID = '".$this->customerID."' LIMIT 1");
            if(mysql_num_rows($query) > 0) {
                $result = mysql_fetch_array($query);
                $this->userKey = $result['txtUserKey'];
                $this->authSalt = $result['txtAuthSalt'];
                
                if(hash_hmac('md5', $this->userKey, $this->authSalt) == $this->authToken) {
                    $this->isAuth = true; 
                    $this->responseCode = 1; //authenticated
                }else{
                    $this->responseCode = 2; //auth error
                }
            }else{
                $this->responseCode = 0;
            }
        } //
    } //end func
    
    function checkUpdates() {
        if($this->isAuth == 1) {
            if(isset($_GET['versionID'])) {
                $versionID = mysql_escape_string($_GET['versionID']);
                $query = mysql_query("SELECT txtCurrentVersionID FROM authVersion LIMIT 1");
                if(mysql_num_rows($query) > 0) {
                    $result = mysql_fetch_array($query);
                    
                    if($versionID == $result['txtLastVersionID']) {
                        $this->responseCode = 3; //up to date  
                    }else{
                        $this->responseCode = 4; //new version available
                    }
                }else{
                    $this->responseCode = 0; //DB error
                }
            }
        }else{
            $this->responseCode = 2; //auth error
        }   
    }
    
    function printStatus() {
        echo "user Key: ".$this->userKey."<br/>\n";
        echo "auth salt: ".$this->authSalt."<br/>\n";
        echo "is auth: ".$this->isAuth."<br/>\n";
        echo "response code: ".$this->getResponseMessage($this->responseCode)."<br/>\n";
    }
    
    function jsonResponse() {
        $json = array("response_code" => $this->responseCode,
                    "response_message" => $this->getResponseMessage($this->responseCode) );
                    
        return $json;
    }
    
    function sendResponse()
    {
        $status_header = 'HTTP/1.1 ' . $status . ' OK';
        header($status_header);
        header('Content-type: text/html');
        echo json_encode($this->jsonResponse());
    }
}

$auth = new authUpdate;
$auth->checkAuth();
$auth->checkUpdates();
//$auth->printStatus();
$auth->sendResponse();
?>