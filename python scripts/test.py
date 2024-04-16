import paho.mqtt.client as mqtt
import RPi.GPIO as GPIO
import uuid

# GPIO setup
GPIO.setmode(GPIO.BCM)
GPIO.setup(20, GPIO.OUT, initial=GPIO.HIGH)  # GPIO for the first GPIO, initial state LOW
GPIO.setup(21, GPIO.OUT, initial=GPIO.HIGH)  # GPIO for the second GPIO, initial state LOW
GPIO.setup(26, GPIO.OUT, initial=GPIO.HIGH)  # GPIO for the third GPIO, initial state LOW

def on_message(client, userdata, message):
    payload = str(message.payload.decode("utf-8"))
    print("Received message:", payload)
    
    # Parse the room name and state from the payload
    room_name, state = payload.split()
    
    # Control GPIO based on room name and state
    if room_name == "test1":
        if state == "on":
            GPIO.output(20, GPIO.HIGH)
        elif state == "off":
            GPIO.output(20, GPIO.LOW)
    elif room_name == "test2":
        if state == "on":
            GPIO.output(21, GPIO.HIGH)
        elif state == "off":
            GPIO.output(21, GPIO.LOW)
    elif room_name == "test3":
        if state == "on":
            GPIO.output(26, GPIO.HIGH)
        elif state == "off":
            GPIO.output(26, GPIO.LOW)

broker_address = "test.mosquitto.org"
print("Creating new instance")
client_id = str(uuid.uuid4())
client = mqtt.Client(client_id=client_id)
print("Connecting to broker")
client.connect(broker_address)
print("Subscribing to topic 'ivan'")
client.subscribe("ivan")

client.on_message = on_message

print("Listening for messages...")

client.loop_forever()
