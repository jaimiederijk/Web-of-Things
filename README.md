
# IOT Sleep tracker with immediate feedback

## What you need

- A bedroom that has darkening shades
- ldr (light-dependent resistor)
- 8 Resistors
- nodemcu
- cables
- 7-Segment Display
- Breadboard

## Setting up the hardware
![Alt text](/hardwaresetup.png)

This setup has a 7-segment display that has a common anode. A common anode means that the + is shared with all 7 segments and to activate one segment you need to put the digital pins on board to low so that they functions as a ground. 
So I have connected each one of the 7 segments to a digital pin with resistors between them. The common anode of the display is connected to the 5v. The ldr is connected with the analog pin so that the board can receive variable data.

## Arduino Code
### 1 setup

I include the used libraries and declare the variables. 
```
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
```
Here I store the patterns that are neccesary for each number
```
byte seven_seg_digits[10][7] = { 
                               { 0,0,0,0,0,0,1 },  // = 0
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
```
The setup with multiple wifispots set so that I can use it in diffrent locations.
```
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

  WiFiMulti.addAP("wifi1", "password");
  WiFiMulti.addAP("wifi2", "password");
  WiFiMulti.addAP("wifi3", "password");
}
```
### 2 Display function
This function uses the earlier created pattern to activate the segments that are neccescary to create a number on the display.
```
void sevenSegWrite(byte digit) {
  int pins[] = {ledA,ledB,ledC,ledD,ledE,ledF,ledG};
  byte pin = 0;
  for (byte segCount = 0; segCount < 7; ++segCount) {
    digitalWrite(pins[pin], seven_seg_digits[digit][segCount]);
    ++pin;
  }
}
```
### 3 Send and receive data

- The loop starts with reading the ldr sensor data. After that it uses this data to create a string.
- It connects to the wifi.
- It starts a http client.
- Sends a post request to my server with the earlier created string.
- Starts a get request to a settings text on my server
- Read what it got back from the server.
- Transform the string in a number.
- Call the function that changes the display with the received number.
```
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
    delay(3000);
}
```
## Server 

### Receive data and put it in a json
- If the server gets a post with sensorlight save the data.
- Open the json
- Decode it to a php array
- Create a new array with the new data
- Push this new array in the decoded array
- Encode the array back to json
- Overwrite the old json with the new
```
  $sensorlight=$_POST["sensorlight"];

  if (!empty($sensorlight)) {
    file_put_contents("lightcontol.json", $sensorlight);

    $lightJson= file_get_contents("http://www.jaimiederijk.nl/webofthings/light.json");
    if (!empty($lightJson)) {
      
      $jsonArray = json_decode($lightJson,true);
    }
    else {
      $jsonArray = array();
    }
      $data['light']=$sensorlight;
      $data['time']=date("h:i:sa");
      $data['date']=date("Y-m-d");

    array_push($jsonArray,$data);
 
    $json = json_encode($jsonArray);
    if (!empty($json)) {
      file_put_contents("light.json", $json);
    }
  }
```
Now you got a json on your server with all the data you want.
