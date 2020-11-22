<?php

    session_start(); // We need it in order to access session variables

    include_once "./upload_image.php";

    function update_user($user_id, $link) {

        $error_messages = array("user"=>"", "conf"=>"", "pass"=>"", "diff"=>"", "email"=> "");

        // Preparing a select statement
        $query = "SELECT * FROM user WHERE user_id=?";

        // Getting the user's current information
        if($statement = mysqli_prepare($link, $query)) {

            // Binding variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "i", $param_user_id);

            // Setting the parameters
            $param_user_id = $user_id;

            // Attempting to execute the prepared statement
            if(mysqli_stmt_execute($statement)) {

                // Storing the result
                mysqli_stmt_store_result($statement);

                // Checking if the user id exists
                if(mysqli_stmt_num_rows($statement) == 1) {

                    // Binding the result variables
                    mysqli_stmt_bind_result($statement, $user_id, $email, $password, $first_name,
                        $last_name, $user_type, $image_url);

                    mysqli_stmt_fetch($statement);
                }
                else{
                    //$user_err = "No user found with that id";
                    $error_messages["user"] = "No user found with that id";
                }
            }
            else
                echo "Something went wrong. Try again later";
        }

        // An array to help us keep track of the edits
        $change = array(
            'email' => 0,
            'password' => 0,
            'first_name' => 0,
            'last_name' => 0,
            'user_type' => 0,
            'image_url' => 0
        );

        // A flag to check if any changes were actually made
        $flag = 0;
        $from_employee_to_admin = 0;

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            // Email
            if(!empty(trim($_POST["email"])) && $_POST["email"] != $email) {

                // We need to check if the provided email is already being used by another user
                $query = "SELECT * FROM user WHERE email=? AND user_id!=?";

                if($statement = mysqli_prepare($link, $query)) {

                    // Binding variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($statement, "si", $param_email, $param_user_id);

                    $param_email = $_POST["email"];
                    $param_user_id = $user_id;

                    if(mysqli_stmt_execute($statement)) {

                        mysqli_stmt_store_result($statement);

                        if(mysqli_stmt_num_rows($statement) != 0) {
                            $error_messages["email"] = "Email address is already being used by another user";
                        }
                        else {
                            $email = $_POST["email"];
                            $change['email'] = 1;
                        }
                    }
                }
            }

            // Password
            if(!empty(trim($_POST["password"])) && empty(trim($_POST["conf_password"]))) {
                $error_messages["conf"] = "Please, confirm the password";
            }

            if(empty(trim($_POST["password"])) && !empty(trim($_POST["conf_password"]))) {
                $error_messages["pass"] = "Please, enter a password";
            }

            if(!empty(trim($_POST["password"])) && !empty(trim($_POST["conf_password"]))) {
                if($_POST["password"] != $_POST["conf_password"]) {
                    $error_messages["diff"] = "Please, make sure the passwords match";
                }
                else {
                    if($_POST["password"] != $password) {
                        $password = $_POST["password"];
                        $password = hash("sha512", $password);
                        $change['password'] = 1;
                    }
                }
            }

            // First Name
            if(!empty(trim($_POST["first_name"])) && $_POST["first_name"] != $first_name) {
                $first_name = $_POST["first_name"];
                $change['first_name'] = 1;
            }

            // Last Name
            if(!empty(trim($_POST["last_name"])) && $_POST["last_name"] != $last_name) {
                $last_name = $_POST["last_name"];
                $change['last_name'] = 1;
            }

            // User Image
            if(imageIsUploaded()) {
                $image_url = uploadImage($first_name, $last_name);
                $change['image_url'] = 1;
            }

            // User Type
            if($_POST["user_type"] != "empty") {

                if($_POST["user_type"] == "employee" && $user_type == 2) {
                    $user_type = 1;
                    $change['user_type'] = 1;
                }
                elseif($_POST["user_type"] == "admin" && $user_type == 1) {
                    $user_type = 2;
                    $change['user_type'] = 1;
                    $from_employee_to_admin = 1;
                }
            }

            // Check if any changes were made
            foreach ($change as $value) {
                if($value == 1) {
                    $flag = 1;
                    break;
                }
            }

            // No errors were found and some changes were made
            if(empty($error_messages["conf"]) && empty($error_messages["pass"]) &&
                empty($error_messages["diff"]) && empty($error_messages["user"]) && $flag == 1) {

                // If a user is turning from an employee to an admin, we need to delete his/her applications
                if ($from_employee_to_admin == 1) {
                    $query = "DELETE FROM applications WHERE user_id=?";
                    $statement = $link->prepare($query);

                    if (!($statement = $link->prepare($query)))
                        echo "Prepare failed: (" . $link->errno . ") " . $link->error;

                    if (!$statement->bind_param("i", $user_id))
                        echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;

                    if (!$statement->execute())
                        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
                }

                $query = "UPDATE user SET email=?, password=?, first_name=?, last_name=?, user_type=?, image_url=? WHERE user_id=?";
                $statement = $link->prepare($query);

                if (!($statement = $link->prepare($query)))
                    echo "Prepare failed: (" . $link->errno . ") " . $link->error;

                if (!$statement->bind_param("ssssisi", $email, $password, $first_name, $last_name, $user_type, $image_url, $user_id))
                    echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;

                if (!$statement->execute())
                    echo "Execute failed: (" . $statement->errno . ") " . $statement->error;

                // If the current user changed his/her information, we need to update the session variables
                if ($_SESSION['user_id'] == $user_id) {

                    // Unsetting all of the session variables
                    $_SESSION = array();

                    // Destroying the session.
                    //session_unset();

                    $_SESSION["loggedin"] = true;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION["first_name"] = $first_name;
                    $_SESSION["last_name"] = $last_name;
                    $_SESSION["email"] = $email;
                    $_SESSION["type"] = $user_type;
                    $_SESSION["image_url"] = $image_url;
                }
            }
        }

        if(!isset($_SESSION['errors'])) {
            $_SESSION['errors'] = array();
            $_SESSION['errors'] = $error_messages;
        }
    }
?>