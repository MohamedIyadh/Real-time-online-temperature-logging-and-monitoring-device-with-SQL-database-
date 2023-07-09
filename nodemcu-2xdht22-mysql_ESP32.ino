#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <DHT.h>

#define DHT_PIN 5    // Replace with the GPIO pin number connected to DHT sensor data pin
#define DHT_TYPE DHT22

const char* ssid = "YOUR SSID HERE";
const char* password = "YOUR PASSWORD HERE";

const char* SERVER_NAME = "YOUR SERVER LINK HERE/ FULL PATH ";
const String PROJECT_API_KEY = "12131415";

DHT dht(DHT_PIN, DHT_TYPE);

unsigned long lastMillis = 0;
long interval = 30000;  // Send an HTTP POST request every 30 seconds
int maxRetryCount = 3;
int retryDelay = 1000;
const int LED_PIN = 2;

void setup() {
  Serial.begin(115200);
  Serial.println("ESP32 serial initialized");

  dht.begin();
  Serial.println("DHT sensor initialized");

  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  pinMode(LED_PIN, OUTPUT);
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    if (millis() - lastMillis > interval) {
      uploadSensorData();
      lastMillis = millis();
    }
  } else {
    Serial.println("WiFi Disconnected");
  }

  delay(2000);
}

void uploadSensorData() {
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  if (isnan(temperature) || isnan(humidity)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  String temperatureData = "api_key=" + PROJECT_API_KEY;
  temperatureData += "&temperature=" + String(temperature, 2);
  temperatureData += "&humidity=" + String(humidity, 2);

  Serial.println("--------------------------");
  Serial.println("Temperature Data: " + temperatureData);

  WiFiClientSecure client;
  HTTPClient http;
  http.setFollowRedirects(HTTPC_FORCE_FOLLOW_REDIRECTS);

  client.setInsecure(); // Disable SSL certificate verification

  http.begin(client, SERVER_NAME);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int httpResponseCode = -1;
  int retryCount = 0;

  digitalWrite(LED_PIN, HIGH);

  while (httpResponseCode != HTTP_CODE_OK && retryCount < maxRetryCount) {
    httpResponseCode = http.POST(temperatureData);

    if (httpResponseCode != HTTP_CODE_OK) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
      Serial.println("Connection lost. Retrying...");
      delay(retryDelay);
    }

    retryCount++;
  }

  digitalWrite(LED_PIN, LOW);

  if (httpResponseCode == HTTP_CODE_OK) {
    Serial.println("Temperature data uploaded successfully!");
  } else {
    Serial.println("Failed to upload temperature data. Max retry count exceeded.");
  }

  http.end();
}
