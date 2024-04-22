<?php
define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "Mysql1234");
define("DB_NAME", "Remote");

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$link) {
  die("Connection failed: " . mysqli_connect_error());
}
