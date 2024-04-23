<?php
session_start();

// Include config file
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
}

// Fetch rooms for the current user
$user_id = $_SESSION["users_id"];
$sql = "SELECT * FROM rooms WHERE user_id = $user_id";
$result = mysqli_query($link, $sql);
$rooms = [];
if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $rooms[] = $row["room_name"];
  }
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User login system</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/main.css">
    <!-- <link rel="stylesheet" href="./css/custom.css"> Custom CSS file -->
    <script src="script.js" type="text/javascript"></script>
    <style>
        .rooms {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            justify-content: center;
        }

        .room {
            width: 30%;
            margin: 0 10px;
            margin-bottom: 10px
        }

        .testBorder{
          border: 1px solid #000;
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
          width: auto;
        }

        .testWidth {
          width: 200px;
        }
    </style>
    <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
</head>

<body class="d-flex flex-column justify-content-center align-items-center">
    <div class="alert alert-success my-5">
        Welcome! You are now signed in to your account.
    </div>
    <div class="container testBorder">
        <div class="row justify-content-center">
            <div class=" text-center">
                <img src="./img/blank-avatar.jpg" style="margin-top: 10px;" class="img-fluid rounded-circle border border-3 border-primary"
                    alt="User avatar" width="120">
                <h4 class="my-4 text-primary">Hello, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
                <h4 class="my-4 text-secondary">Your ID is <?= htmlspecialchars($_SESSION["users_id"]); ?></h4>
                <a href="./logout.php" style="margin-bottom: 10px;" class="btn btn-danger">Log Out</a>
            </div>
        </div>
        <div class="room card p-3 text-center col-lg-8 testWidth">  
            <button id="addNewRoom" class="btn btn-primary btn-lg " onclick="addNewRoom()">Add new room</button>
        </div>  
    </div>

    <div class="container mt-5 d-flex flex-column justify-content-center align-items-center">
        <h2>Your Rooms:</h2>
        <div class="rooms" id="roomsContainer">
            <?php
            foreach ($rooms as $room) {
                echo "<div class='room card p-3 text-center position-relative'>";
                echo "<h4 class='my-4'>$room</h4>";
                echo "<button class='btn btn-danger btn-sm remove-btn position-absolute top-0 end-0 d-none' onclick='removeRoom(this)'>X</button>";
                echo "<button class='btn btn-primary btn-lg' data-room='$room' onclick='toggleRoom(this)'>On</button>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
    var userId = <?php echo json_encode($_SESSION["users_id"]); ?>;

        // Show remove button on hover
        document.querySelectorAll('.room').forEach(room => {
            room.addEventListener('mouseenter', () => {
                room.querySelector('.remove-btn').classList.remove('d-none');
            });
            room.addEventListener('mouseleave', () => {
                room.querySelector('.remove-btn').classList.add('d-none');
            });
        });

        function removeRoom(button) {
            var roomName = button.parentElement.querySelector('h4').textContent;
            var confirmation = confirm("Are you sure you want to remove the room '" + roomName + "'?");
            if (confirmation) {
                // Send an AJAX request to remove the room from the database
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "remove_room.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // If the room is successfully removed, remove the corresponding card from the UI
                        if (xhr.responseText.trim() === "success") {
                            button.parentElement.remove();
                        } else {
                            alert("Failed to remove the room. Please try again.");
                        }
                    }
                };
                xhr.send("room=" + encodeURIComponent(roomName));
            }
        }
</script>

</body>

</html>