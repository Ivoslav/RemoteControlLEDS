function addNewRoom() {
    var roomName = prompt("Enter the name of the new room:");
    if (roomName) {
        var newRoomDiv = document.createElement("div");
        newRoomDiv.classList.add("room");

        var roomHeading = document.createElement("h2");
        roomHeading.textContent = roomName;

        var roomButton = document.createElement("button");
        roomButton.classList.add("lightBtn");
        roomButton.textContent = "On";
        roomButton.setAttribute("data-state", "on");
        roomButton.setAttribute("onclick", "toggleRoom(this)");

        var removeButton = document.createElement("button");
        removeButton.classList.add("lightBtn");
        removeButton.textContent = "Remove";
        removeButton.setAttribute("onclick", "removeRoom(this)");

        newRoomDiv.appendChild(roomHeading);
        newRoomDiv.appendChild(roomButton);
        newRoomDiv.appendChild(removeButton);

        var roomsContainer = document.getElementById("roomsContainer");
        roomsContainer.insertBefore(newRoomDiv, roomsContainer.lastElementChild);

        client.subscribe(roomName.toLowerCase().replace(/\s/g, ''));
        console.log("New room '" + roomName + "' added.");

    }
}