import paho.mqtt.client as mqtt

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

    # Print messages based on room name and state
    if room_name == "test1":
        print("Room test1 is now", state)
    elif room_name == "test2":
        print("Room test2 is now", state)
    elif room_name == "test3":
        print("Room test3 is now", state)


# Create a client instance
client = mqtt.Client("Ivoslav")

# Set callback functions
client.on_connect = on_connect
client.on_message = on_message

# Connect to the broker
client.connect("test.mosquitto.org", 1883, 60)

# Start the loop to process incoming messages
client.loop_forever()
