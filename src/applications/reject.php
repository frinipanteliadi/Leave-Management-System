<?php
    // Initializing the session
    session_start();

    // Including the configuration file
    include_once "../common/db.php";
    include_once "../functions.php";

    // Checking if the user is logged in
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
        header("location: index.php");
        exit();
    }

    // Checking if the user is an admin
    if(!($_SESSION["type"] == 2)) {
        header("location: /welcome.php");
        exit();
    }

    // Getting the id of the applications
    if(isset($_GET['application_id'])) {
        $application_id = $_GET['application_id'];
        unset($_GET['application_id']);
    }


    $flag = get_application($application_id, $connection)->status;
    if($flag == "approved" || $flag == "rejected")
        echo "The status of the applications has already been set to '".$flag."'";
    else {

        $status = "rejected";
        $query = "UPDATE applications SET status=? WHERE application_id=?";

        if (!($statement = $connection->prepare($query)))
            echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;

        if (!$statement->bind_param("si", $status, $application_id)) {
            echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
        }

        if (!$statement->execute()) {
            echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
        }

        include_once "./notify_user.php";
        notifyUser($application_id, "rejected", $connection);
        echo "The application has been rejected";

    }
?>