<?php
    session_start();
    include_once "../common/db.php";
    include_once "../common/auth.php";
    include_once "./upload_image.php";

    // Checking if the user is logged in
    if(!isLoggedIn()) {
        header("location: index.php");
        exit();
    }

    // Checking if the user is an admin
    if(!isAdmin()) {
        header("location: welcome.php");
        exit();
    }

    // Defining variables and initializing them with empty values
    $first_name = "";
    $last_name = "";
    $email = "";
    $password = "";
    $conf_password = "";
    $user_type = "";

    $first_name_err = "";
    $last_name_err = "";
    $email_err = "";
    $password_err = "";
    $conf_password_err = "";
    $diff_password_err = "";
    $user_type_err = "";

    // Processing the data of the submitted form

if($_SERVER["REQUEST_METHOD"] == "POST") {

        // Checking the provided values
        $first_name = trim($_POST["first_name"]);
        if(empty($first_name))
            $first_name_err = "Please, enter a first name";

        $last_name = trim($_POST["last_name"]);
        if(empty($last_name))
            $last_name_err = "Please, enter a last name";

        $email = trim($_POST["email"]);
        if(empty($email))
            $email_err = "Please, enter an email";

        $password = $_POST["password"];
        if(empty(trim($password)))
            $password_err = "Please, enter a password";

        if(empty(trim($_POST["conf_password"])))
            $conf_password_err = "Please, confirm the password";

        if(!($password == $_POST["conf_password"]))
            $diff_password_err = "Please, make sure your passwords match";

        $user_type = ($_POST["user_type"] == "employee") ? 1 : 2;
        if($user_type == "empty")
            $user_type_err = "Please, choose the type of the user";


        // All fields have been filled
        if(empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) &&
        empty($conf_password_err) && empty($diff_password_err) && empty($user_type_err)) {

            // Checking if the email already exists
            $query = "SELECT * FROM user WHERE email=?";

            if($statement = mysqli_prepare($connection, $query)) {

                // Binding variables to the prepared statement as parameters
                mysqli_stmt_bind_param($statement, "s", $param_email);

                // Setting the parameters
                $param_email = $email;

                // Attempting to execute the prepared statement
                if(mysqli_stmt_execute($statement)) {

                    // Storing the result
                    mysqli_stmt_store_result($statement);

                    // Checking if the email doesn't exist
                    if(!mysqli_stmt_num_rows($statement)) {
                        $image_url = uploadImage($first_name, $last_name);
                        createUser(
                            $connection,
                            $email,
                            $password,
                            $first_name,
                            $last_name,
                            $user_type,
                            $image_url
                        );

                        header("location: ../admin.php");
                    }
                    else
                        $email_err = "An account already exists with that email";
                }
            }
            else
                echo "Something went wrong. Try again later";
        }

        mysqli_close($connection);
    }

function createUser(mysqli $connection, $email, $password, $first_name, $last_name, $user_type, $image_url)
{
    $hashed_password = hash("sha512", $password);

    $new_query = "INSERT INTO user (email, password, first_name, last_name, user_type, image_url) VALUES (?, ?, ?, ?, ?, ?)";

    if (!($statement = $connection->prepare($new_query)))
        echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;

    if (!$statement->bind_param("ssssis", $email, $hashed_password, $first_name, $last_name, $user_type, $image_url))
        echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;

    if (!$statement->execute())
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
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
                                    <li><a class="" href="../admin.php">Existing Users</a></li>
                                    <li><a class="is-active" style="background-color: #d3d3d3;">Create a User</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <main class="column">
                    <label class="label is-large">Create a User</label>
                    <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <!-- First Name -->
                        <div class="field">
                            <label for="" class="label">First Name</label>
                            <div class="control has-icons-left" <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>>
                                <input type="text" name="first_name" placeholder="e.g. John" class="input" required>
                                <span class="icon is-small is-left">
                                         <i class="fa fa-user"></i>
                                    </span>
                            </div>
                            <p class="help is-danger"> <?php echo $first_name_err;?> </p>
                        </div>

                        <!-- Last Name -->
                        <div class="field">
                            <label for="" class="label">Last Name</label>
                            <div class="control has-icons-left" <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>>
                                <input type="text" name="last_name" placeholder="e.g. Doe" class="input" required>
                                <span class="icon is-small is-left">
                                         <i class="fa fa-user"></i>
                                    </span>
                            </div>
                            <p class="help is-danger"> <?php echo $last_name_err;?> </p>
                        </div>

                        <!-- Email -->
                        <div class="field">
                            <label for="" class="label">Email</label>
                            <div class="control has-icons-left" <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>>
                                <input type="email" name="email" placeholder="e.g. johndoe@mail.com" class="input" required>
                                <span class="icon is-small is-left">
                                         <i class="fa fa-envelope"></i>
                                    </span>
                            </div>
                            <p class="help is-danger"> <?php echo $email_err;?></p>
                        </div>

                        <!-- Password -->
                        <div class="field">
                            <label for="" class="label">Password</label>
                            <div class="control has-icons-left" <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>>
                                <input type="password" name="password" placeholder="*******" class="input" required>
                                <span class="icon is-small is-left">
                                         <i class="fa fa-lock"></i>
                                </span>
                            </div>
                            <p class="help is-danger"> <?php echo $password_err;?> </p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="field">
                            <label for="" class="label">Confirm Password</label>
                            <div class="control has-icons-left" <?php echo (!empty($conf_password_err) || !empty($diff_password_err)) ? 'has-error' : ''; ?>>
                                <input type="password" name="conf_password" placeholder="*******" class="input" required>
                                <span class="icon is-small is-left">
                                         <i class="fa fa-lock"></i>
                                </span>
                            </div>
                            <p class="help is-danger">
                                <?php
                                    if(!empty($conf_password_err))
                                        echo $conf_password_err;
                                    if(!empty($diff_password_err))
                                        echo $diff_password_err;
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
                                        No file chosen
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
                            <div class="control" <?php echo (!empty($user_type_err)) ? 'has-error' : ''; ?>>
                                <div class="select">
                                    <select name="user_type">
                                        <option value="empty">--</option>
                                        <option value="employee">Employee</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <p class="help is-danger"><?php echo $user_type_err;?></p>
                        </div>

                        <br>

                        <!-- Submit & Cancel Buttons -->
                        <div class="field is-grouped is-flex is-justify-content-center">
                            <p class="control">
                                <button type="submit" class="button is-success has-background-link">
                                    Create
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

