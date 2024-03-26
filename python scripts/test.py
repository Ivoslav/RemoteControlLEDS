# import paho.mqtt.client as mqtt
# broker_address="test.mosquitto.org"
# print("creating new instance")
# client = mqtt.Client()
# print("connecting to broker")
# client.connect(broker_address)
# print("Subscribing to topic","ivan")
# client.subscribe("ivan")
# print("Publishing message to topic","ivan")
# client.publish("ivan","OFF")

import paho.mqtt.client as mqtt

# Callback function to handle when a message is received
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
