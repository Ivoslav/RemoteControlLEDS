var roomName;

function addNewRoom() {
    roomName = prompt("Enter the name of the new room:");
    if (roomName) {
        var newRoomDiv = document.createElement("div");
        newRoomDiv.classList.add("room", "card", "p-3", "text-center");

        var roomHeading = document.createElement("h4");
        roomHeading.classList.add("my-4");
        roomHeading.textContent = roomName;

        var roomButton = document.createElement("button");
        roomButton.classList.add("btn", "btn-primary", "btn-lg");
        roomButton.textContent = "On";
        roomButton.setAttribute("data-state", "on");
        roomButton.setAttribute("onclick", "toggleRoom(this)");

        newRoomDiv.appendChild(roomHeading);
        newRoomDiv.appendChild(roomButton);

        var roomsContainer = document.getElementById("roomsContainer");
        roomsContainer.appendChild(newRoomDiv);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", 'save_room.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                console.log(this.responseText);
            }
        }
        xhr.send("roomName=" + roomName + "&userId=" + userId);
    }
}

function toggleRoom(button) {
    var currentState = button.getAttribute("data-state");
    var roomName = button.parentNode.querySelector("h4").textContent; // Corrected to "h4"

    if (currentState === "on") {
        publishMessage(roomName.toLowerCase().replace(/\s/g, ''), 'on');
        button.textContent = "Off";
        button.setAttribute("data-state", "off");
    } else {
        publishMessage(roomName.toLowerCase().replace(/\s/g, ''), 'off');
        button.textContent = "On";
        button.setAttribute("data-state", "on");
    }
}

function publishMessage(roomName, state) {
    var topic = 'ivan';

    var message = roomName + ' ' + state;
    var Message = new Paho.MQTT.Message(message);
    Message.destinationName = topic;

    client.send(Message);
    console.log("Message to topic " + topic + " is sent");
}

function startConnect() {
    clientID = "clientID - " + parseInt(Math.random() * 100);

    host = "test.mosquitto.org";
    port = "8080";
    userId = "";
    password = "";

    console.log("Connecting to " + host + " on port " + port);
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

function onConnectionLost(responseObject) {
    console.log("ERROR: Connection is lost.");
    if (responseObject != 0) {
        console.log("ERROR: " + responseObject.errorMessage);
    }
}

function onMessageArrived(message) {
    console.log(message.payloadString);
}

function onConnect() {
    topic = 'ivan';
    console.log("Subscribing to topic " + topic);
    client.subscribe(topic);

    var message = 'TEST';
    var Message = new Paho.MQTT.Message(message);
    Message.destinationName = topic;

    client.send(Message);
    console.log("Test " + topic + " is sent");
}
