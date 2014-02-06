auth_version_server
===================

Authentication Client and Server, useful for checking software versions. Allows a client application to authenticate to a server, and check for a new software version, afterwhich something could run. <br/>
Uses salted client values to ensure unique clients. </br>
Uses a hashed version number to compare clients version number with servers version number.<br>
JSON used to respond to client requests, with response code values:<br/>
<br/>
 0 => "Database Error" - server could not connect to MySQL Database<br/>
 1 => "Authenticated" - clients connection has successfully been authenticated <br/>
 2 => "Authentication Error" - clients connection has failed to be authenticated<br/>
 3 => "Version up-to-date" - clients version value is current<br/>
 4 => "New Version Available" - clients version value is out of date<br/>
 5 => "Unknown" - unknown error, something went wrong along the way<br/>
<br/>
Table Structure for authentication:<br/>
<pre>
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
        ) ;</pre><br/>
        
 Table Structure for version values:<br/>
 <pre>
        CREATE TABLE IF NOT EXISTS `authVersion` (
          `txtCurrentVersionID` text NOT NULL
        ) ;</pre><br/>

authUpdateServer.php - Server class<br/>
authUpdateClient.php - Client code, to be inculded in distributed software.<br/>
<br/>

Do with, as you please.
