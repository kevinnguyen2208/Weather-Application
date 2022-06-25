import time
import mysql.connector
import serial

global lastID
global temp
global lastState

if __name__=='__main__':
    ser=serial.Serial('/dev/ttyACM0',9600,timeout=1)
    ser.flush()

mydb=mysql.connector.connect(
    host="localhost",
    user="tempUser",
    password="123456",
    database="tempApp"
    )
mycursor =mydb.cursor()

sql=("select id from buzzlevel order by id desc limit 1")
mycursor.execute(sql)
lastID=mycursor.fetchall()
lastID=lastID[0][0]
print(lastID)
sql=("select status from outputStatus order by update_time desc limit 1")
mycursor.execute(sql)
lastState=mycursor.fetchall()
lastState=lastState[0][0]
print(lastState)
if lastState=="ON":
    ser.write('ON\n'.encode('utf-8'))
    print("SERIAL SEND ON")
    
else:
    ser.write(b"OFF\r\n")

while True:
    if ser.in_waiting > 0: 
         line=ser.readline().decode('utf-8').rstrip()
         temp=float(line)
         sql="insert into temp(temp) values (%s)" %(temp)
         mycursor.execute(sql)
         mydb.commit()     
         sql=("select id from buzzlevel order by id desc limit 1")
         mycursor.execute(sql)
         currentID=mycursor.fetchall()
         currentID=currentID[0][0]
         if currentID != lastID:
             sql=("select level from buzzlevel order by id desc limit 1")
             mycursor.execute(sql)
             level=mycursor.fetchall()
             level=level[0][0]
             print(level)
             lastID=currentID             
             if level == "UP":
                 ser.write(b"UP\r\n")
                 print("UP LEVEL")
             if level == "DOWN":
                 ser.write(b"DOWN\r\n")
                 print("DOWN LEVEL")
         mycursor.execute ("select status from outputStatus order by update_time desc limit 1")
         outputStatus=mycursor.fetchall()
         outputStatus=outputStatus[0][0]
         if lastState!= outputStatus:
             lastState = outputStatus
             if outputStatus=="ON":
                 ser.write(b"ON\r\n")
        #          print("SERIAL SEND ON")
             elif outputStatus=="OFF":
                 ser.write(b"OFF\r\n")
        #          print("SERIAL SEND OFF")
             else:
                 ser.write(b"error\r\n")
                 print("error")


    print(temp, "OUTPUT STATUS IS",outputStatus)
    if temp > 20 and outputStatus == 'OFF':
        sql=("INSERT INTO outputStatus(status) VALUE ('ON')")
        mycursor.execute(sql)
        mydb.commit()
    if temp < 15 and outputStatus == 'ON':
        sql=("INSERT INTO outputStatus(status) VALUE ('OFF')")
        mycursor.execute(sql)
        mydb.commit()

          
     
        
                 
                 
         
             
         
         