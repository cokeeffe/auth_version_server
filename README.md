auth_version_server
===================

Authentication Client and Server, useful for checking software versions. Allows a client application to authenticate to a server, and check for a new software version, afterwhich something could run. 
Uses salted client values to ensure unique clients. 
Uses a hashed version number to compare clients version number with servers version number.
JSON used to respond to client requests, with response code values:

 0 => "Database Error" - server could not connect to MySQL Database
 1 => "Authenticated" - clients connection has successfully been authenticated 
 2 => "Authentication Error" - clients connection has failed to be authenticated
 3 => "Version up-to-date" - clients version value is current
 4 => "New Version Available" - clients version value is out of date
 5 => "Unknown" - unknown error, something went wrong along the way

Table Structure for authentication:
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
        
 Table Structure for version values:
        CREATE TABLE IF NOT EXISTS `authVersion` (
          `txtCurrentVersionID` text NOT NULL
        ) ;

authUpdateServer.php - Server class
authUpdateClient.php - Client code, to be inculded in distributed software.


Do with, as you please.
