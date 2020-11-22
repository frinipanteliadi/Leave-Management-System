<?php
    // Initializing the session
    session_start();

    // Unsetting all of the session variables
    $_SESSION = array();

    // Destroying the session.
    session_destroy();

    // Redirecting to login page
    header("location: /index.php");
    exit;