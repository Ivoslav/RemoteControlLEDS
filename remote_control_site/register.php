<?php
require_once "./config.php";

$username_err = $email_err = $password_err = "";
$username = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["username"]))) {
    $username_err = "Моля въведете потребителско име.";
  } else {
    $username = trim($_POST["username"]);
    if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
      $username_err = "Потребителското име може да съдъра само букви, числа и символи като '@', '_', or '-'.";
    } else {
      $sql = "SELECT users_id FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          if (mysqli_stmt_num_rows($stmt) == 1) {
            $username_err = "Това потребителско име е заето.";
          }
        } else {
          echo "<script>" . "alert('Опс! Има някаква грешка. Моля опитайте по-късно.')" . "</script>";
        }

        mysqli_stmt_close($stmt);
      }
    }
  }

  if (empty(trim($_POST["email"]))) {
    $email_err = "Въведете имейл адрес.";
  } else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Въведете валиден имейл адрес.";
    } else {
      $sql = "SELECT users_id FROM users WHERE email = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        $param_email = $email;

        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          if (mysqli_stmt_num_rows($stmt) == 1) {
            $email_err = "Този имейл адрес е зает.";
          }
        } else {
          echo "<script>" . "alert('Опс! Има някаква грешка. Моля опитайте по-късно.');" . "</script>";
        }

        mysqli_stmt_close($stmt);
      }
    }
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Въведете парола.";
  } else {
    $password = trim($_POST["password"]);
    if (strlen($password) < 8) {
      $password_err = "Паролата трябва да има 8 или повече символа.";
    }
  }

  if (empty($username_err) && empty($email_err) && empty($password_err)) {
    $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if (mysqli_stmt_execute($stmt)) {
        echo "<script>" . "alert('Регистрацията е успешна. Влезте в профила си, за да продължите.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php';" . "</script>";
        exit;
      } else {
        echo "<script>" . "alert('Опс! Има някаква грешка. Моля опитайте по-късно.');" . "</script>";
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
  <title>User login system</title>
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
        <div class="form-wrap border rounded p-4">
          <h1>Вписване</h1>
          <p>Попълнете формата, за да се регистрирате</p>
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="username" class="form-label">Потребителско име</label>
              <input type="text" class="form-control" name="username" id="username" value="<?= $username; ?>">
              <small class="text-danger"><?= $username_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Имайл адрес</label>
              <input type="email" class="form-control" name="email" id="email" value="<?= $email; ?>">
              <small class="text-danger"><?= $email_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Парола</label>
              <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>">
              <small class="text-danger"><?= $password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Покажи паролата</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Регистрация">
            </div>
            <p class="mb-0">Вече имате профил? <a href="./login.php">Влезте</a></p>
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