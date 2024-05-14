<?php
//Matikan SESSION
session_start();
$_SESSION = [];
session_unset();
session_destroy();

//Matikan Cookie
setcookie('yudi', '', time() - 3600, "/");
setcookie('key', '', time() - 3600, "/");

header('Location: ../index.php'); // Perhatikan tidak ada spasi setelah 'Location'
exit();
