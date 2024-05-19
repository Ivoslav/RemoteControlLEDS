import paho.mqtt.client as mqtt

def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
    client.subscribe("ivan")

def on_message(client, userdata, msg):
    payload = msg.payload.decode()
    payload_parts = payload.split()
    
    if len(payload_parts) < 2:
        print("Invalid payload:", payload)
        return
    
    room_name, state = payload_parts[:2]

    if room_name == "test1":
        print("Room test1 is now", state)
    elif room_name == "test2":
        print("Room test2 is now", state)
    elif room_name == "test3":
        print("Room test3 is now", state)


client = mqtt.Client()

client.on_connect = on_connect
client.on_message = on_message

client.connect("test.mosquitto.org", 1883, 60)

client.loop_forever()
