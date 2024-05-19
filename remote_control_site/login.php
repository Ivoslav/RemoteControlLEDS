<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='./'" . "</script>";
  exit;
}

require_once "./config.php";

$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["user_login"]))) {
    $user_login_err = "Въведете потребителско име или имейл адрес.";
  } else {
    $user_login = trim($_POST["user_login"]);
  }

  if (empty(trim($_POST["user_password"]))) {
    $user_password_err = "Въведете парола.";
  } else {
    $user_password = trim($_POST["user_password"]);
  }

  if (empty($user_login_err) && empty($user_password_err)) {
    $sql = "SELECT users_id, username, password FROM users WHERE username = ? OR email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

      $param_user_login = $user_login;

      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $users_id, $username, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($user_password, $hashed_password)) {
              # Store users_id in session
              $_SESSION["users_id"] = $users_id;
              $_SESSION["username"] = $username;
              $_SESSION["loggedin"] = TRUE;
              echo "<script>" . "window.location.href='./'" . "</script>";
              exit;
            } else {
              $login_err = "Имейлът или паролата са невалидни.";
            }
          }
        } else {
          $login_err = "Невалидно потребителско име или парола.";
        }
      } else {
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php'" . "</script>";
        exit;
      }

      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Remote control</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
  <script defer src="./js/index.js"></script>
  <style>
    .dark-mode {
      background-color: #212529;
      color: #fff;
    }

    .dark-mode input {
      background-color: #3f424a;
      color: #fff;
      border-color: #545c64;
    }
  </style>
</head>

<body>
  <button id="darkModeToggle" class="btn btn-primary position-absolute top-0 end-0 mt-2 me-2">🌙</button>
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <?php
        if (!empty($login_err)) {
          echo "<div class='alert alert-danger'>" . $login_err . "</div>";
        }
        ?>
        <div class="form-wrap border rounded p-4">
          <h1>Влизане</h1>
          <p>Влезте във вашия профил</p>
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="user_login" class="form-label">Потребителско име или имейл адрес</label>
              <input type="text" class="form-control" name="user_login" id="user_login" value="<?= $user_login; ?>">
              <small class="text-danger"><?= $user_login_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Парола</label>
              <input type="password" class="form-control" name="user_password" id="password">
              <small class="text-danger"><?= $user_password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Покажи паролата</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Влез">
            </div>
            <p class="mb-0">Нямате профил? <a href="./register.php">Регистрирайте се</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

<script>
  const darkModeToggle = document.getElementById('darkModeToggle');

  darkModeToggle.addEventListener('click', function() {
    const body = document.body;
    body.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
      localStorage.setItem('darkMode', 'on');
    } else {
      localStorage.removeItem('darkMode');
    }
  });

  const darkModePref = localStorage.getItem('darkMode');
  if (darkModePref === 'on') {
    document.body.classList.add('dark-mode');
  }
</script>

</html>