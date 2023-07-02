<?php 
define('DB_HOST'    , 'localhost'); 
define('DB_USERNAME', 'mohasuwb_TRHDB'); 
define('DB_PASSWORD', '12131415*'); 
define('DB_NAME'    , 'mohasuwb_TRH_RSSI');

define('POST_DATA_URL', 'https://mohamediyad.com/sensordata.php');

//PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in NodeMCU sketch file
//Both values must be same
define('PROJECT_API_KEY', '12131415');


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
