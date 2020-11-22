<?php
    session_start();
    include_once "../common/db.php";
    include_once "../common/auth.php";
    include_once "update.php";

    unset($_GET['status']);

    // Checking if the user is logged in
    if(!isLoggedIn()) {
        header("location: index.php");
        exit();
    }

    // Checking if the user is an admin
    if(!($_SESSION["type"] == 2)) {
        header("location: welcome.php");
        exit();
    }

    // Checking to see if the requested user's id was provided
    if(!isset($_GET['id'])) {
        header("location: admin.php");
        exit();
    }
    else
        $user_id = $_GET['id'];

    // Error Variables (Originally empty)
    $conf_password_err = "";
    $password_err = "";
    $diff_password_err = "";
    $user_err = "";
    $email_err = "";
    $errors = 0;

    // Handling the form that updates a user's information
    if(isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['email']) ||
        isset($_POST['password']) || isset($_POST['conf_password']) || isset($_FILES['fileToUpload']['tmp_name'])) {
        update_user($user_id, $connection);
    }

    if(isset($_SESSION['errors'])) {
        $conf_password_err = $_SESSION['errors']['conf'];
        $password_err = $_SESSION['errors']['pass'];
        $diff_password_err = $_SESSION['errors']['diff'];
        $user_err = $_SESSION['errors']['user'];
        $email_err = $_SESSION['errors']['email'];

        foreach ($_SESSION['errors'] as $key => $value) {
            if(!empty($value)) {
                $errors = 1;
                break;
            }
        }

        unset($_SESSION['errors']);
    }

    // Defining variables and initializing them with empty values
    $email = "";
    $password = "";
    $first_name = "";
    $last_name = "";
    $user_type = "";
    $image_url = "";

    // Preparing a select statement
    $query = "SELECT * from user WHERE user_id=?";

    if($statement = mysqli_prepare($connection, $query)) {

        // Binding variables to the prepared statement as parameters
        mysqli_stmt_bind_param($statement, "i", $param_user_id);

        // Setting the parameters
        $param_user_id = $user_id;

        // Attempting to execute the prepared statement
        if(mysqli_stmt_execute($statement)) {

            // Storing the result
            mysqli_stmt_store_result($statement);

            // Checking if the user exists
            if(mysqli_stmt_num_rows($statement) == 1) {
                // Binding the result variables
                mysqli_stmt_bind_result($statement, $user_id, $email, $password, $first_name, $last_name, $user_type, $image_url);

                // Getting the results
                mysqli_stmt_fetch($statement);
            }
            else
                $user_err = "No account was found for the requested user";
        }
        else
            echo "Something went wrong. Try again later";
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
<!-- Top section -->
<?php
    include_once '../common/navbar.php';
?>
<!-- Middle section -->
<section class="section">

    <div class="columns">

        <!-- Left Column: The menu -->
        <aside class="column is-2">
            <nav class="menu">
                <p class="menu-label">
                    General
                </p>
                <ul class="menu-list">
                    <li>
                        <a class="is-active" href="../admin.php">User Overview</a>
                        <ul>
                            <li><a href="../admin.php" class="is-active" style="background-color: #d3d3d3;" href="../admin.php">Existing Users</a></li>
                            <ul>
                                <li><a class="is-active" style="background-color: #d9d9d9;">User Details</a></li>
                            </ul>
                            <li><a href="create.php" class="">Create a User</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="column">
            <label class="label is-large">User Details</label>
            <p class="help
                <?php
                    //if(isset($_GET['status']) && isset($_GET['status'])=='success')
                        //echo "is-success";
                    //if($_GET['status'] == 'success' && (!empty($password_err) || !empty(!$conf_password_err) || !empty($diff_password_err) || !empty($email_err)))
                        //echo "is-danger";?>" style="font-size: 15px">
                <?php
                    //if(isset($_GET['status']) && isset($_GET['status'])=='success')
                        //echo "User properties have successfully been updated";
                    //if($_GET['status'] == 'success' && !empty($password_err) || !empty(!$conf_password_err) || !empty($diff_password_err) || !empty($email_err))
                        //echo "User properties haven't been updated due to some error(s)";
                ?>
            </p>
            <form enctype="multipart/form-data" action="" method="post">
                <!-- First Name -->
                <div class="field">
                    <label for="" class="label">First Name</label>
                    <div class="control has-icons-left" <?php ?>>
                        <input type="text" name="first_name" value="<?php echo $first_name; ?>" class="input">
                        <span class="icon is-small is-left">
                            <i class="fa fa-edit"></i>
                        </span>
                    </div>
                    <p class="help is-danger"> <?php ?> </p>
                </div>

                <!-- Last Name -->
                <div class="field">
                    <label for="" class="label">Last Name</label>
                    <div class="control has-icons-left" <?php ?>>
                        <input type="text" name="last_name" value="<?php echo $last_name; ?>" class="input">
                        <span class="icon is-small is-left">
                            <i class="fa fa-edit"></i>
                        </span>
                    </div>
                    <p class="help is-danger"> <?php ?> </p>
                </div>

                <!-- Email -->
                <div class="field">
                    <label for="" class="label">Email</label>
                    <div class="control has-icons-left" <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>>
                        <input type="email" name="email" value="<?php echo $email; ?>" class="input">
                        <span class="icon is-small is-left">
                                         <i class="fa fa-envelope"></i>
                                    </span>
                    </div>
                    <p class="help is-danger"> <?php echo $email_err; ?></p>
                </div>

                <!-- Password -->
                <div class="field">
                    <label for="" class="label">Password</label>
                    <div class="control has-icons-left" <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>>
                        <input type="password" name="password" placeholder="*******" class="input">
                        <span class="icon is-small is-left">
                                         <i class="fa fa-lock"></i>
                                </span>
                    </div>
                    <p class="help is-danger"> <?php echo $password_err; ?> </p>
                </div>

                <!-- Confirm Password -->
                <div class="field">
                    <label for="" class="label">Confirm Password</label>
                    <div class="control has-icons-left" <?php echo (!empty($conf_password_err) || !empty($diff_password_err)) ? 'has-error' : ''; ?>>
                        <input type="password" name="conf_password" placeholder="*******" class="input">
                        <span class="icon is-small is-left">
                                         <i class="fa fa-lock"></i>
                                </span>
                    </div>
                    <p class="help is-danger">
                        <?php
                            if(!empty($conf_password_err)) {
                                echo $conf_password_err;
                            }
                            if(!empty($diff_password_err)) {
                                echo $diff_password_err;
                            }
                        ?>
                    </p>
                </div>

                <!-- User Image -->
                <div id="file-js-example" class="field">
                    <label class="label">Upload a User Image</label>
                    <div class="file has-name">
                        <label class="file-label">
                            <input class="file-input" type="file" name="fileToUpload">
                            <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fa fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Choose an image
                                        </span>
                                    </span>
                            <span class="file-name">
                                        <?php echo $image_url; ?>
                            </span>
                            <script>
                                const fileInput = document.querySelector('#file-js-example input[type=file]');
                                fileInput.onchange = () => {
                                    if (fileInput.files.length > 0) {
                                        const fileName = document.querySelector('#file-js-example .file-name');
                                        fileName.textContent = fileInput.files[0].name;
                                    }
                                }
                            </script>
                        </label>
                    </div>
                </div>

                <!-- User Type -->
                <div class="field">
                    <label for="" class="label">User Type</label>
                    <div class="control" <?php ?>>
                        <div class="select">
                            <select name="user_type">
                                <option  value="empty">--</option>
                                <?php
                                    if($user_type == 1)
                                        echo "<option selected value='employee'>Employee</option>
                                              <option value='admin'>Admin</option>";
                                    elseif ($user_type == 2)
                                        echo "<option value='employee'>Employee</option>
                                              <option selected value='admin'>Admin</option>";
                                ?>

                            </select>
                        </div>
                    </div>
                    <p class="help is-danger"><?php ?></p>
                </div>

                <br>

                <!-- Submit & Cancel Buttons -->
                <div class="field is-grouped is-flex is-justify-content-center">
                    <p class="control">
                        <button type="submit" formmethod="post" class="button is-success has-background-link">
                            Update
                        </button>
                    </p>
                    <p class="control">
                        <button class="button is-light" id="myButton">
                            Cancel
                        </button>
                        <script type="text/javascript">
                            document.getElementById("myButton").onclick = function () {
                                location.href = "../admin.php";
                            };
                        </script>
                    </p>
                </div>
                <br>

            </form>
        </main>
    </div>
</section>
</body>
</html>

