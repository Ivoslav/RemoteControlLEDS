import paho.mqtt.client as mqtt

def on_message(client, userdata, message):
    payload = str(message.payload.decode("utf-8"))
    print("Received message:", payload)
    
    # Parse the room name and state from the payload
    room_name, state = payload.split()
    
    # Control GPIO based on room name and state
    if room_name == "test1":
        print("Controlled test1 with state:", state)
    elif room_name == "test2":
        print("Controlled test2 with state:", state)
    elif room_name == "test3":
        print("Controlled test3 with state:", state)

broker_address = "test.mosquitto.org"
print("Creating new instance")
client = mqtt.Client()
print("Connecting to broker")
client.connect(broker_address)
print("Subscribing to topic 'ivan'")
client.subscribe("ivan")

client.on_message = on_message

print("Listening for messages...")

client.loop_forever()
