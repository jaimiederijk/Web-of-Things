#include <EIoTCloudRestApiConfig.h>
#include <EIoTCloudRestApi.h>

#include <ThingSpeak.h>
#include <ESP8266WiFi.h>


// WiFi settings
char ssid[] = "...";
char pass[] = "...";
int status = WL_IDLE_STATUS;
WiFiClient  client;

long channelID = 106687;
const char * myWriteAPIKey = "NVFO8KGMXDIV8I2F";


EIoTCloudRestApi eiotcloud;
/*

*/
int aSensor = A0;
int dSensor = D0;
int sensorValue = 0;
int dState = 0;

void setup() {
  WiFi.begin(ssid, pass);
  ThingSpeak.begin(client);

  eiotcloud.begin();

  pinMode(dSensor,INPUT);
  pinMode(aSensor,INPUT);
  pinMode(LED_BUILTIN, OUTPUT);     // Initialize the LED_BUILTIN pin as an output
  Serial.begin(9600);
}

// the loop function runs over and over again forever
void loop() {
  sensorValue = analogRead(aSensor);
  if(sensorValue<800){
    digitalWrite(LED_BUILTIN, HIGH);
  } else {
    digitalWrite(LED_BUILTIN, LOW);
  }
  dState = digitalRead(dSensor);
   
  Serial.print("sensor = ");                                  
  Serial.print(sensorValue);
  
  Serial.print("d = ");
  Serial.print(dState);

  eiotcloud.sendParameter("5706ad3cc943a0661cf314dc/VJJ3mN6CPkJ3d5m2", sensorValue);
  eiotcloud.sendParameter("5706ad3cc943a0661cf314dc/UORFCIfHMTpgvd1c",dState);

  ThingSpeak.writeField(channelID, 1, sensorValue, myWriteAPIKey);
  ThingSpeak.writeField(channelID, 2, dState, myWriteAPIKey);
     delay(1500);                            
                    
}
