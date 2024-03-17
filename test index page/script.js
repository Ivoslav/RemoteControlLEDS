function startConnect(){

    clientID = "clientID - "+parseInt(Math.random() * 100);

    host = "test.mosquitto.org";   
    port = "8080";
    userId  = ""; 
    password = "";  

    console.log("Connecting to " + host + " on port " +port);
    console.log("Using the client Id " + clientID);

    client = new Paho.MQTT.Client(host, Number(port), clientID);

    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;

    client.connect({
        onSuccess: onConnect,
        userName: userId,
        password: password
    });
}

function onConnectionLost(responseObject){
    console.log("ERROR: Connection is lost.");
    if(responseObject !=0){
        console.log("ERROR: "+ responseObject.errorMessage);
    }
}

function onMessageArrived(message){
    console.log(message.payloadString);
}

function onConnect(){
    topic =  'ivan';
    console.log("Subscribing to topic "+topic);
    client.subscribe(topic);
}

function startDisconnect(){
    client.disconnect();
    console.log("Started disconnect");
}

function publishMessage(){
msg = document.getElementById("Message").value;
topic = 'ivan';

Message = new Paho.MQTT.Message(msg);
Message.destinationName = topic;

client.send(Message);
document.getElementById("messages").innerHTML += "<span> Message to topic "+topic+" is sent </span><br>";

}