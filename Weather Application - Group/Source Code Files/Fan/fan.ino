
#define fan 2
String command;

void setup() 
{
  Serial.begin(9600);
  pinMode(fan, OUTPUT); 
}

void loop()
{
  if(Serial.available()){
    
   command = Serial.readStringUntil('\n');
   command.trim();
   if(command.equals("ON")){
    analogWrite(fan,255);
   }
   else if (command.equals("OFF")){
    analogWrite(fan,0);
   }    
  }

   delay(1000); 
}
