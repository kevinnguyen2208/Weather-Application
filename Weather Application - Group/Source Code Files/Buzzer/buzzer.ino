
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>

#define BUZZER  6 //Connect Buzzzer to pin 6

String command; 

bool buzzer_state=false;
int buzzer_level=100; //Default cound level range 0 to 225


void setup() {
  Serial.begin(9600);
  
  pinMode(BUZZER,OUTPUT);

}

void loop() {

  if(buzzer_state)
  {

analogWrite(BUZZER,constrain(buzzer_level,0,225));
  
  }
  
   if(Serial.available())
  {
    command=Serial.readStringUntil('\n');
    command.trim();
    if(command.equals("ON")){
    buzzer_state=true;
  
    }
    else if(command.equals("OFF")){

  digitalWrite(BUZZER,LOW);
   buzzer_state=false;
   //debugprintln("LED OFF");
       
   }
   else 
   {
    delay(1);
    }
   
  }
}
