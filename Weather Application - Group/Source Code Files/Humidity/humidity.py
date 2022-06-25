import os
import time
import sys
import paho.mqtt.client as mqtt
import json
import serial

device = "/dev/ttyACM0"
arduino = serial.Serial(device,9600)
data = arduino.readline();
THINGSBOARD_HOST = '172.20.10.7'
ACCESS_TOKEN = 'HUMI'

# Data capture and upload interval in seconds. Less interval will eventually hang the DHT22.
INTERVAL=2

sensor_data = {'humidity': data}

next_reading = time.time() 

client = mqtt.Client()

# Set access token
client.username_pw_set(ACCESS_TOKEN)

# Connect to ThingsBoard using default MQTT port and 60 seconds keepalive interval
client.connect(THINGSBOARD_HOST, 1883, 60)

client.loop_start()

try:
    while True:
                # Sending humidity and temperature data to ThingsBoard
        client.publish('v1/devices/me/telemetry', json.dumps(sensor_data), 1)
        client.publish('v1/devices/me/attributes', json.dumps(sensor_data), 1)
        next_reading += INTERVAL
        sleep_time = next_reading-time.time()
        if sleep_time > 0:
            time.sleep(sleep_time)
            print(sensor_data)
except KeyboardInterrupt:
    pass

client.loop_stop()
client.disconnect()