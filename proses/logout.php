<?php
    session_start();
    $_SESSION = []; // Perhatikan penulisan $_SESSION
    session_unset();
    session_destroy();

    header('Location: ../index.php'); // Perhatikan tidak ada spasi setelah 'Location'
    exit();
?>
