<?php
include_once "common/db.php";

session_start();

/* Functions */

function get_user($user_id, $link)
{
    $query = "SELECT * FROM user WHERE user_id=?";
    return fetch_one($link, $query, $user_id);
}

// Returns the applications whose id is application_id
function get_application($application_id, $link)
{
    $query = "SELECT * FROM applications WHERE application_id=?";
    return fetch_one($link, $query, $application_id);
}


function fetch_one($link, $query, $id)
{
    $statement = $link->prepare($query);
    $statement->bind_param("i", $id);
    $statement->execute();
    $result = $statement->get_result();
    return $result->fetch_object();
}

?>