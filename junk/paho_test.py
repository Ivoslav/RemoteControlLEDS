import paho.mqtt.client as mqtt

# Create an MQTT client instance
client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION1)

# Print the version
print(f"Paho MQTT version: {mqtt.__version__}")
