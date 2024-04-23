<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User login system</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <script src="script.js" type="text/javascript"></script>
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
</head>

<body>
<div class="p-3 mb-2 bg-primary-subtle text-primary-emphasis">.bg-primary-subtle</div>
    <div class="container">
      <div class="alert alert-success my-5">
        Welcome ! You are now signed in to your account.
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-5 text-center">
          <img src="./img/blank-avatar.jpg" class="img-fluid rounded" alt="User avatar" width="180">
          <h4 class="my-4">Hello, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
          <h4 class="my-4">Your ID is <?= htmlspecialchars($_SESSION["users_id"]); ?></h4>

          <a href="./logout.php" class="btn btn-danger">Log Out</a>
        </div>
      </div>
    </div>

    <div class="container rooms" id="roomsContainer">
      <div class="room" id="addNewRoomDiv">
          <h4 class="my-4">Add new room</h4>
          <button id="addNewRoom" class="btn btn-primary" onclick="addNewRoom()">Add new room</button>
      </div>
  </div>


</body>

</html>