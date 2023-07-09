<?php 
define('DB_HOST'    , 'localhost'); 
define('DB_USERNAME', 'YOUR DB USERNAME HERE'); 
define('DB_PASSWORD', 'YOUR DB PASSWORD HERE'); 
define('DB_NAME'    , 'YOUR DB NAME HERE');

define('POST_DATA_URL', 'LOCATION OF THE "sensordata.php" FILE HERE');

//PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in NodeMCU sketch file
//Both values must be same
define('PROJECT_API_KEY', 'YOUR API KEY / SHOULD BE THE SAME IN THE ARDUINO PROGRAM');


//set time zone for your country
date_default_timezone_set('Asia/Karachi');

// Connect with the database 
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); 
//echo "connected";
// Display error if failed to connect 
if ($db->connect_errno) { 
    echo "Connection to database is failed: ".$db->connect_error;
    exit();
}
