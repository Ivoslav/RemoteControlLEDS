<?php
session_start();

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
        <div class="rooms" id="roomsContainer"></div>
    </div>

    <script>
    var userId = <?php echo json_encode($_SESSION["users_id"]); ?>;
</script>

</body>

</html>