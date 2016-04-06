/*
 ESP8266 Blink by Simon Peter
 Blink the blue LED on the ESP-01 module
 This example code is in the public domain
 
 The blue LED on the ESP-01 module is connected to GPIO1 
 (which is also the TXD pin; so we cannot use Serial.print() at the same time)
 
 Note that this sketch uses LED_BUILTIN to find the pin with the internal LED
*/
int soundSensor = A0;
int sensorValue = 0;

void setup() {
//  pinMode(soundSensor,INPUT);
  pinMode(LED_BUILTIN, OUTPUT);     // Initialize the LED_BUILTIN pin as an output
  Serial.begin(9600);
}

// the loop function runs over and over again forever
void loop() {
  sensorValue = analogRead(soundSensor);
  if(sensorValue<800){
    digitalWrite(LED_BUILTIN, HIGH);
  } else {
    digitalWrite(LED_BUILTIN, LOW);
  }
//     
  Serial.print("sensor = ");                                  
  Serial.print(sensorValue);
     delay(1000);                            
//  delay(1000);                      
//  digitalWrite(LED_BUILTIN, HIGH);  
//  delay(2000);                      
}
