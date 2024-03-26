import paho.mqtt.client as mqtt

def on_message(client, userdata, message):
    print("Received message:", str(message.payload.decode("utf-8")))

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
