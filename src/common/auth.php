<?php
    function isLoggedIn() {
        return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
    }

    function isAdmin()
    {
        return $_SESSION["type"] == 2;
    }