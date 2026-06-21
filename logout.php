<?php
    //start the session and destroy to log out.
    session_start();

    //clear array and any ghostly cookies
    $_SESSION = array();
    setcookie(session_name(), '', time() - 3600, '/');

    session_destroy();
    header("Location: index.php");
    exit();
?>