/**
 * BasicHTTPClient.ino
 *
 *  Created on: 24.05.2015
 *
 */

#include <Arduino.h>

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>

#include <ESP8266HTTPClient.h>



ESP8266WiFiMulti WiFiMulti;

int sensorValue = 0;
int aSensor = A0;
int led = D2;

void setup() {
  pinMode(aSensor,INPUT);
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(led,OUTPUT);
  
  Serial.begin(9600);

  WiFiMulti.addAP("AndroidAP", "hgbt1799");
  WiFiMulti.addAP("jaimie", "jaimie22");
  WiFiMulti.addAP("dijkemans", "9Het7debiele3netwerkje4van2J@@p");
}

void loop() {
    sensorValue = analogRead(aSensor);
    
    String postDataOne = "sensorlight=";
    String postDataTwo = postDataOne + sensorValue;
    // wait for WiFi connection
    if((WiFiMulti.run() == WL_CONNECTED)) {
        Serial.println(WiFi.localIP());
        HTTPClient http;
        
        http.begin("http://www.jaimiederijk.nl/webofthings/index.php"); //HTTP
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        http.POST(postDataTwo);

        http.end();

        http.begin("http://www.jaimiederijk.nl/webofthings/led.txt"); 
        int httpCode = http.GET();        
        String payload = http.getString();

        Serial.println(payload);
        if (payload == "off") {
          digitalWrite(LED_BUILTIN, LOW);
          digitalWrite(led, LOW);
        } else {
          digitalWrite(LED_BUILTIN, HIGH);
          digitalWrite(led, HIGH);
        }
      

        http.end();
    }

    delay(2000);
}

