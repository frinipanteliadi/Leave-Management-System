<?php

    // Initializing the session
    session_start();

    // include_once the configuration file
    include_once "common/db.php";

    // Checking if the user is logged in
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

        // Redirecting the user according to his/her type
        if($_SESSION["type"] == 1)
            header("location: welcome.php");
        else if($_SESSION["type"] == 2)
            header("location: admin.php");

        exit;
    }

    // Defining variables and initializing them with empty values
    $email = "";
    $password = "";
    $email_err = "";
    $password_err = "";

    // Processing the data of the submitted form
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // Checking if the email is empty
        if(empty(trim($_POST["email"])))
            $email_err = "Please, enter an email";
        else
            $email = trim($_POST["email"]);

        // Checking if the password is empty
        if(empty(trim($_POST["password"])))
            $password_err = "Please, enter your password";
        else
            $password = trim($_POST["password"]);

        // Validating the credentials
        if(empty($email_err) && empty($password_err)) {

            // Preparing a select statement
            $query = "SELECT * FROM user WHERE email =?";

            if($statement = mysqli_prepare($connection, $query)) {

                // Binding variables to the prepared statement as parameters
                mysqli_stmt_bind_param($statement, "s", $param_email);

                // Setting the parameters
                $param_email = $email;

                // Attempting to execute the prepared statement
                if(mysqli_stmt_execute($statement)) {

                    // Storing the result
                    mysqli_stmt_store_result($statement);

                    // Checking if the email exists
                    if(mysqli_stmt_num_rows($statement) == 1) {

                        // Binding the result variables
                        mysqli_stmt_bind_result($statement, $user_id, $email, $result_password, $first_name,
                        $last_name, $type, $image_url);

                        if(mysqli_stmt_fetch($statement)) {

                            // Checking if the given password is the one we have in storage
                            if(hash("sha512", $password) == $result_password) {

                                // The password was correct, starting a new session
                                session_start();

                                // Storing data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["user_id"] = $user_id;
                                $_SESSION["first_name"] = $first_name;
                                $_SESSION["last_name"] = $last_name;
                                $_SESSION["email"] = $email;
                                $_SESSION["type"] = $type;
                                $_SESSION["image_url"] = $image_url;

                                if($type == 1)
                                    // Redirecting the user to the home page
                                    header("location: welcome.php");
                                elseif ($type == 2)
                                    header("location: admin.php");
                            }
                            else{
                                // The password is not valid, display an error message
                                $password_err = "The password you entered is incorrect";
                            }
                        }
                    }
                    else
                        $email_err = "No account found with that email";
                }
                else
                    echo "Something went wrong. Try again later";
            }
        }

        // Closing the connection
        mysqli_close($connection);
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Epignosis - Employee Management Portal</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.epignosishq.com/wp-content/themes/epignosishq/dist/images/favicon/favicon_32x32.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!--Top section: The navigation bar-->
    <?php
        include_once 'common/navbar.php';
    ?>
    <!--Next section: The login form-->
    <section class=" hero has-background-grey-lighter is-fullheight">
        <div class="hero-body is-align-items-flex-start">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-5-tablet is-4-desktop is-3-widescreen is-align-content-start">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="box">
                            <div>
                                <label for="" style="font-size: 30px;" class="label has-text-centered">User Login</label>
                            </div>

                            <div class="field">
                                <label for="" class="label">Email</label>
                                <div class="control has-icons-left <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <input type="email" name="email" placeholder="e.g. bobsmith@gmail.com" class="input" required>
                                    <span class="icon is-small is-left">
                                         <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                                <p class="help is-danger"> <?php echo $email_err;?> </p>
                            </div>

                            <div class="field">
                                <label for="" class="label">Password</label>
                                <div class="control has-icons-left <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <input type="password" name="password" placeholder="*******" class="input" required>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <p class="help is-danger"> <?php echo $password_err; ?> </p>
                            </div>

                            <div class="field">
                                <button type="submit" class="button is-success has-background-link">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


</body>
</html>