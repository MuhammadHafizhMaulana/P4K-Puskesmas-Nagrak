<?php
    session_start();
    $_SESSION = []; // Perhatikan penulisan $_SESSION
    session_unset();
    session_destroy();

    header('Location: ../login_admin.php'); // Perhatikan tidak ada spasi setelah 'Location'
    exit();
?>
