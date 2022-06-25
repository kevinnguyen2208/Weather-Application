// Declaring all used pins here
int tempPotentiometer = A0;
//int fan = 8;
int celsius = 0;

////DC Motor/Fan Operation Setup
//void fanOn(){
//  digitalWrite(fan, HIGH);
//}
//void fanOff(){
//  digitalWrite(fan, LOW);
//}

void setup()
{
//  pinMode(fan, OUTPUT);
  pinMode(tempPotentiometer, INPUT);
  Serial.begin(9600);
}

void loop(){
  // Temperature Boundaries and Calculation
  //map(variable, from min value, from max value, to min value, to max value)
  // celsius is capped at 50 degrees and min of 0 degrees 
  celsius = map(((analogRead(A0))), 0, 1023, 0, 50);
  
  Serial.println(celsius);
  
//  if( celsius<= 22){   
//    fanOff();
//  }
//  else if(celsius >= 23 && celsius <= 27){
//    fanOff();
//  }
//  else if(celsius > 27){       
//    fanOn();
//  }
//  else{
//    Serial.println("00");
//  }
  delay(1000); // Give delay of 1 seconds before next value posted to Serial Monitor
}
