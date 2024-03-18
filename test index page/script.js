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

function publishMessage(message){
    msg = message;
    topic = 'ivan';

    Message = new Paho.MQTT.Message(msg);
    Message.destinationName = topic;

    client.send(Message);
    console.log("Message to topic "+topic+" is sent");
}

function addNewRoom() {
    var roomName = prompt("Enter the name of the new room:");
    if (roomName) {
        var newRoomDiv = document.createElement("div");
        newRoomDiv.classList.add("room");

        var roomHeading = document.createElement("h2");
        roomHeading.textContent = roomName;

        var roomButton = document.createElement("button");
        roomButton.classList.add("lightBtn");
        roomButton.textContent = "Turn On";
        roomButton.setAttribute("onclick", "publishMessage('" + roomName.toLowerCase().replace(/\s/g, '') + "')");

        var removeButton = document.createElement("button");
        removeButton.classList.add("lightBtn");
        removeButton.textContent = "Remove";
        removeButton.setAttribute("onclick", "removeRoom(this)");

        newRoomDiv.appendChild(roomHeading);
        newRoomDiv.appendChild(roomButton);
        newRoomDiv.appendChild(removeButton);

        var addNewRoomButton = document.getElementById("addNewRoom").parentNode;
        addNewRoomButton.parentNode.insertBefore(newRoomDiv, addNewRoomButton);

        client.subscribe(roomName.toLowerCase().replace(/\s/g, ''));
        console.log("New room '" + roomName + "' added.");
    }
}


function removeRoom(button) {
    var roomToRemove = button.parentNode;
    roomToRemove.parentNode.removeChild(roomToRemove);
    console.log("Room removed.");
}
