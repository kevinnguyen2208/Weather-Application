
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>

#define DHTPIN 2   //connect DHT 11 to pin 2 
#define OUTPIN 5    // 5 is a PWM pin for Brightness control 
#define POT  A0 // connect the potentiometer to A0 
#define BUZZER  6 //Connect Buzzzer to pin 6


//#define ENABLE_DEBUG

#define DHTTYPE    DHT11     // DHT 11

String command; 
int pot_read=0;
int led_brightness=0;
bool buzzer_state=false;
int buzzer_level=100; //Default cound level range 0 to 225
float temp=0;


#ifdef ENABLE_DEBUG

#define debugprint(A) Serial.print(A)
#define debugprintln(A) Serial.println(A)
#else 
#define debugprint(A) 
#define debugprintln(A) 
#endif


DHT_Unified dht(DHTPIN, DHTTYPE);

uint32_t delayMS;

void setup() {
  Serial.begin(9600);

  pinMode(OUTPIN,OUTPUT);
  pinMode(BUZZER,OUTPUT);
  pinMode(POT,INPUT);
  
  // Initialize device.
  dht.begin();
    sensor_t sensor;
     dht.humidity().getSensor(&sensor);
     dht.temperature().getSensor(&sensor);
  delayMS = sensor.min_delay / 1000;
  debugprintln("Serial Monitor Started");
}

void loop() {
  // Delay between measurements.
  //delay(delayMS);
  delay(100);
  sensors_event_t event;
  dht.temperature().getEvent(&event);
  
 
   if (!isnan(event.temperature)) {
    temp=event.temperature;
  }
  Serial.println(temp);
  
  pot_read=analogRead(POT);
    led_brightness=map(pot_read,0,1024,0,225);
    debugprintln(led_brightness);
  analogWrite(OUTPIN,led_brightness);
  
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
//    debugprintln("LED ON");
 // Serial.println("LED ON");
  
    }
    else if(command.equals("OFF")){

  digitalWrite(BUZZER,LOW);
   buzzer_state=false;
   //debugprintln("LED OFF");
       
   }
     else if(command.equals("UP")){
  
  buzzer_level+=50;
       
   }
    else if(command.equals("DOWN")){
  
  buzzer_level-=50;
       
   }
   else if (command.equals("DATA")){
   
  Serial.println(temp);
   }
  
   else 
   {
    delay(1);
    }
   
  }
}
