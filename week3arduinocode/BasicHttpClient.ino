

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
int ledA = D8;
int ledB = D1;
int ledC = D2;
int ledD = D7;
int ledE = D4;
int  ledF= D5;
int  ledG= D6;
//int ledDP = D7;

// Arduino 7 segment display example software
// http://www.hacktronics.com/Tutorials/arduino-and-7-segment-led.html
// License: http://www.opensource.org/licenses/mit-license.php (Go crazy)
 
// Define the LED digit patters, from 0 - 9
// Note that these patterns are for common cathode displays
// For common anode displays, change the 1's to 0's and 0's to 1's
// 1 = LED on, 0 = LED off, in this order:
byte seven_seg_digits[10][7] = { { 0,0,0,0,0,0,1 },  // = 0
                               { 1,0,0,1,1,1,1 },  // = 1
                                { 0,0,1,0,0,1,0 },  // = 2
                                                           { 0,0,0,0,1,1,0 },  // = 3
                                                           { 1,0,0,1,1,0,0 },  // = 4
                                                           { 0,1,0,0,1,0,0 },  // = 5
                                                           { 0,1,0,0,0,0,0 },  // = 6
                                                           { 0,0,0,1,1,1,1 },  // = 7
                                                           { 0,0,0,0,0,0,0 },  // = 8
                                                           { 0,0,0,1,1,0,0 }   // = 9
                                                           };

void setup() {
  pinMode(aSensor,INPUT);
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(ledG,OUTPUT);
  pinMode(ledE,OUTPUT);
  pinMode(ledF,OUTPUT);
  pinMode(ledD,OUTPUT);
  pinMode(ledA,OUTPUT);
  pinMode(ledB,OUTPUT);
  pinMode(ledC,OUTPUT);
  Serial.begin(9600);

  WiFiMulti.addAP("AndroidAP", "hgbt1799");
  WiFiMulti.addAP("jaimie", "jaimie22");
  WiFiMulti.addAP("dijkemans", "9Het7debiele3netwerkje4van2J@@p");
}

void sevenSegWrite(byte digit) {
  int pins[] = {ledA,ledB,ledC,ledD,ledE,ledF,ledG};
  byte pin = 0;
  for (byte segCount = 0; segCount < 7; ++segCount) {
    digitalWrite(pins[pin], seven_seg_digits[digit][segCount]);
    ++pin;
  }
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
        int num = payload.toInt();
        sevenSegWrite(num);
          
          
  
      

        http.end();
    }
 
  
    delay(120000);
}

