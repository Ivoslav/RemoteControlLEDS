import paho.mqtt.client as mqtt
import RPi.GPIO as GPIO

# Set up GPIO mode
GPIO.setmode(GPIO.BCM)
GPIO.setup(20, GPIO.OUT)
GPIO.setup(21, GPIO.OUT)
GPIO.setup(26, GPIO.OUT)

# Callback function to handle when the client connects to the broker
def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
    client.subscribe("ivan")

# Callback function to handle when a message is received
def on_message(client, userdata, msg):
    payload = msg.payload.decode()
    payload_parts = payload.split()

    # Check if payload has at least two parts
    if len(payload_parts) < 2:
        print("Invalid payload:", payload)
        return

    room_name, state = payload_parts[:2]
    # Control GPIO based on room name and state
    if room_name == "баня":
        if state == "on":
            GPIO.output(20, GPIO.HIGH)
        elif state == "off":
            GPIO.output(20, GPIO.LOW)
    elif room_name == "спалня":
        if state == "on":
            GPIO.output(21, GPIO.HIGH)
        elif state == "off":
            GPIO.output(21, GPIO.LOW)
    elif room_name == "хол":
        if state == "on":
            GPIO.output(26, GPIO.HIGH)
        elif state == "off":
            GPIO.output(26, GPIO.LOW)

    print("Controlled", room_name, "with state:", state)

# Create a client instance
client = mqtt.Client()

# Set callback functions
client.on_connect = on_connect
client.on_message = on_message

# Connect to the broker
client.connect("test.mosquitto.org", 1883, 60)

# Start the loop to process incoming messages
client.loop_forever()
