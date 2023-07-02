<?php

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = escape_data($_POST["api_key"]);
    echo "<br>PROJECT_API_KEY: " . PROJECT_API_KEY . "<br>";
    
    if ($api_key == PROJECT_API_KEY) {
        $temperature = escape_data($_POST["temperature"]);
        $humidity = escape_data($_POST["humidity"]);
        $temperature2 = escape_data($_POST["temperature2"]);
        $humidity2 = escape_data($_POST["humidity2"]);

        // Debugging statements to inspect the received POST data
        echo "Received POST data:<br>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        
        echo "Temperature: " . $temperature . "<br>";
        echo "Humidity: " . $humidity . "<br>";
        echo "Temperature2: " . $temperature2 . "<br>";
        echo "Humidity2: " . $humidity2 . "<br>";

        // Insert the data into the database
        $sql = "INSERT INTO `MRI2` (`Temperature`, `Humidity`, `number`, `date time`, `Temperature2`, `Humidity2`) 
                VALUES ('" . $temperature . "', '" . $humidity . "', '', '" . date("Y-m-d H:i:s") . "', '" . $temperature2 . "', '" . $humidity2 . "')";

        if ($db->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $db->error;
        }

        echo "OK. INSERT ID: ";
        echo $db->insert_id;
    } else {
        echo "Wrong API Key";
    }
} else {
    echo "No HTTP POST request found";
}

function escape_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
