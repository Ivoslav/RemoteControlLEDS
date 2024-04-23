<?php
require_once "./config.php";

$username_err = $email_err = $password_err = "";
$username = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    $username = trim($_POST["username"]);
    if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
      $username_err = "Username can only contain letters, numbers and symbols like '@', '_', or '-'.";
    } else {
      $sql = "SELECT users_id FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          if (mysqli_stmt_num_rows($stmt) == 1) {
            $username_err = "This username is already registered.";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.')" . "</script>";
        }

        mysqli_stmt_close($stmt);
      }
    }
  }

  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter an email address";
  } else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Please enter a valid email address.";
    } else {
      $sql = "SELECT users_id FROM users WHERE email = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        $param_email = $email;

        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          if (mysqli_stmt_num_rows($stmt) == 1) {
            $email_err = "This email is already registered.";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        }

        mysqli_stmt_close($stmt);
      }
    }
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } else {
    $password = trim($_POST["password"]);
    if (strlen($password) < 8) {
      $password_err = "Password must contain at least 8 or more characters.";
    }
  }

  # Check input errors before inserting data into database
  if (empty($username_err) && empty($email_err) && empty($password_err)) {
    # Prepare an insert statement
    $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind varibales to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

      # Set parameters
      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      # Execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        echo "<script>" . "alert('Registeration completed successfully. Login to continue.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php';" . "</script>";
        exit;
      } else {
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
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
  <script defer src="./js/script.js"></script>
  <style>
    .dark-mode {
  background-color: #212529; /* Adjust background color as needed */
  color: #fff;
    }
  .dark-mode input {
  background-color: #3f424a; /* Adjust color as needed */
  color: #fff; /* Ensure text is readable */
  border-color: #545c64; /* Adjust border color as needed */
}


  </style>
</head>

<body>
<button id="darkModeToggle" class="btn btn-primary position-absolute top-0 end-0 mt-2 me-2">🌙</button>

  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <div class="form-wrap border rounded p-4">
          <h1>Sign up</h1>
          <p>Please fill this form to register</p>
          <!-- form starts here -->
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" name="username" id="username" value="<?= $username; ?>">
              <small class="text-danger"><?= $username_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" id="email" value="<?= $email; ?>">
              <small class="text-danger"><?= $email_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>">
              <small class="text-danger"><?= $password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Show Password</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Sign Up">
            </div>
            <p class="mb-0">Already have an account ? <a href="./login.php">Log In</a></p>
          </form>
          <!-- form ends here -->
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

  // Save dark mode preference in local storage
  if (body.classList.contains('dark-mode')) {
    localStorage.setItem('darkMode', 'on');
  } else {
    localStorage.removeItem('darkMode');
  }
});

// Check for saved dark mode preference on page load
const darkModePref = localStorage.getItem('darkMode');
if (darkModePref === 'on') {
  document.body.classList.add('dark-mode');
}
</script>

</html>