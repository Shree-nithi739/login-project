<?php

session_start();

require_once "config/redis.php";

/* ===========================
   DELETE REDIS TOKEN
=========================== */

if (isset($_SESSION["token"])) {

    $redis->del("token:" . $_SESSION["token"]);

}

/* ===========================
   DESTROY SESSION
=========================== */

$_SESSION = [];

session_unset();

session_destroy();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Logout</title>

</head>

<body>

<script>

/* ===========================
   CLEAR LOCAL STORAGE
=========================== */

localStorage.removeItem("token");
// or localStorage.clear();

/* ===========================
   REDIRECT
=========================== */

window.location.href = "login.html";

</script>

</body>

</html>