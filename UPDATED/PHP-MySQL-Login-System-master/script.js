function addNewRoom() {
    var roomName = prompt("Enter the name of the new room:");
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

        client.subscribe(roomName.toLowerCase().replace(/\s/g, ''));
        console.log("New room '" + roomName + "' added.");
    }
}
