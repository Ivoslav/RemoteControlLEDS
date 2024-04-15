import paho.mqtt.client as mqtt

# Callback function to handle when the client connects to the broker
def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
    client.subscribe("ivan")

# Callback function to handle when a message is received
def on_message(client, userdata, msg):
    payload = msg.payload.decode()
    room_name, state = payload.split()

    # Control GPIO based on room name and state
    if room_name == "test1":
        print("Controlled test1 with state:", state)
    elif room_name == "test2":
        print("Controlled test2 with state:", state)
    elif room_name == "test3":
        print("Controlled test3 with state:", state)

# Create a client instance
client = mqtt.Client()

# Set callback functions
client.on_connect = on_connect
client.on_message = on_message

# Connect to the broker
client.connect("test.mosquitto.org", 1883, 60)

# Start the loop to process incoming messages
client.loop_forever()
