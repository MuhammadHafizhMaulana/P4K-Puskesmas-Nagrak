<?php
    session_start();
    $_SESSION = []; // Perhatikan penulisan $_SESSION
    session_unset();
    session_destroy();

    setcookie('yudi', '', time() - 3600, "/");
    setcookie('key', '', time() - 3600, "/");

    header('Location: ../login_admin.php'); // Perhatikan tidak ada spasi setelah 'Location'
    exit();
?>
