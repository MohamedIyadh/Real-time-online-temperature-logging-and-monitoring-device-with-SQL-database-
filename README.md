# ESP32-Temp-online
ESP32 online temperature and humidity

Project Introduction
Welcome to the Temperature Data Logging Project!

The Temperature Data Logging Project utilizes an ESP32 microcontroller along with a DHT22 sensor to log temperature and humidity data. The logged data is then stored directly into an online SQL database hosted on your own website.

Files Overview
nodemcu-2xdht22-mysql_ESP32.ino - This file contains the ESP32 code written in Arduino programming language. It is responsible for connecting the ESP32 microcontroller to the internet, reading temperature and humidity data from two DHT22 sensors, and storing the data into the SQL database hosted on your website.

for this to be working, you need to make an sql database with the required structure, i have attached a sql file so that you can import this to your Db using PHPMyadmin. 



Functionality
The Temperature Data Logging Project offers the following functionalities:

ESP32 Code: The ESP32 code (nodemcu-2xdht22-mysql_ESP32.ino) enables the ESP32 microcontroller to connect to the internet, read temperature and humidity data from a DHT22 sensor, and store the data into the SQL database hosted on your website.

Data Logging: The ESP32 code logs temperature and humidity data from a sensors at regular intervals and stores it in the SQL database. This allows you to track and analyze the temperature and humidity variations over time.

Online SQL Database: The SQL database, named "MRI2," is hosted on your own website and accessible through phpMyAdmin. It provides a central location for storing and retrieving the temperature and humidity data collected by the ESP32.

Customizability: The ESP32 code is written in Arduino programming language, allowing you to customize it to suit your specific requirements. You can modify the code to adjust the data logging interval, add additional sensors or functionalities, or integrate it with other systems.

Feel free to make any further adjustments based on your project's specific details and requirements.

config.php:
    This file contains the configuration settings for connecting to the database and other project-specific constants. Here's a breakdown of the defined constants:
    
    DB_HOST: The hostname or IP address of the database server.
    DB_USERNAME: The username used to connect to the database.
    DB_PASSWORD: The password for the specified database user.
    DB_NAME: The name of the database where the temperature and humidity data will be stored.
    POST_DATA_URL: The URL where the sensordata.php file is located. This is the endpoint where the ESP32 will send the temperature and humidity data.
    PROJECT_API_KEY: An API key that serves as a security measure to ensure that only authorized devices can send data to the sensordata.php file.
    date_default_timezone_set(): Sets the default time zone for your country.
    Database Connection: Establishes a connection with the database using the provided credentials.
sensordata.php:
    This file is the endpoint where the ESP32 sends the temperature and humidity data. It receives the POST request, verifies the API key, and inserts the data into the SQL database.     Here's an overview of what this file does:
    
    Requires config.php: This includes the config.php file to access the configuration settings and establish a database connection.
    POST Data Handling: Checks if the request method is POST and retrieves the POST data sent by the ESP32.
    API Key Verification: Compares the received API key with the defined PROJECT_API_KEY to ensure the authenticity of the data.
    Data Insertion: If the API key is valid, it retrieves the temperature and humidity data from the POST request and inserts it into the MRI2 table of the SQL database. The date and     time of insertion are also recorded.
    Error Handling: If any errors occur during the database insertion, they are displayed.
    Response: Outputs appropriate responses based on the outcome of the data insertion process.
    Make sure to include these files in your project and configure the config.php file with your specific database credentials and API key.
    
    Note: Remember to properly secure your API key and database credentials to prevent unauthorized access.

Feel free to modify the code and files as per your project requirements.
