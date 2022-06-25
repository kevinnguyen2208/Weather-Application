import logging
import time
import serial

from tb_device_mqtt import TBDeviceMqttClient
logging.basicConfig(level=logging.DEBUG)



def on_attributes_change(client, result, exception):
    ser = serial.Serial('/dev/ttyACM0', 9600, timeout = 1)
    ser.flush()
    client.stop()
    
    while True:
        if exception is not None:
            print("Exception: " + str(exception))
        else:
            x = list(result.values())
            y = list(x[0].values())
            j = int(y[0])
            
        if __name__=='__main__':
            ser = serial.Serial('/dev/ttyACM0', 9600, timeout = 1)
            ser.flush()
            print(j)
    
    #while True:
        if j >= 22:
            ser.write("ON\n".encode("utf-8"))
        if j < 22 :
            ser.write("OFF\n".encode("utf-8"))
        

def main():
    client = TBDeviceMqttClient("172.20.10.7", "TEMP")
    client.connect()
    client.request_attributes(["temperature", "temperature"], callback=on_attributes_change)
    while not client.stopped:
        time.sleep(1)


if __name__ == '__main__':
    main()
