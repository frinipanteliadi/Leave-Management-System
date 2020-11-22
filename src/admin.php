<?php
    session_start();
    include_once "common/db.php";
    include_once  "common/auth.php";

    // Checking if the user is logged in
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] === false) {
        header("location: index.php");
        exit();
    }

    // Checking if the user is an admin
    if(!isAdmin()) {
        header("location: welcome.php");
        exit();
    }

function isAdminType($type)
{
    return $type == "2";
}

function userCard($user_id, $first_name, $last_name, $email, $color, $display)
{
    return "
            <div class='card'>
               <header class='card-header' style='background-color: #3174dc;'>
                  <a href='users/details.php?id=" . $user_id . "'>
                     <p style='color:white;' class='card-header-title'>" . $first_name . " " . $last_name . "</p>
                  </a>
                  <a href='#' class='card-header-icon' aria-label='more options'>
                  <span class='icon'>
                  <i class='fas fa-angle-down' aria-hidden='true'></i>
                  </span>
                  </a>
               </header>
               <div class='card-content'>
                  <div class='content'>
                     <ul>
                        <li><a style='font-weight:bold; color:black; pointer-events:none;'>Email: </a><a href='mailto:".$email."' style='color:blue;'>" . $email . "</a></li>
                        <li><a style='font-weight:bold; color:black; pointer-events:none;'>Type: </a> 
                           <a style='color:" . $color . "; pointer-events: none;'>" . $display . "</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <br>
            ";
}

function noUsersScreen()
{
    return "<div class='card'>
                <header class='card-header'>
                    <p class='card-header-title'>
                        No users have been created yet.
                    </p>
                    <a href='#' class='card-header-icon' aria-label='more options'>
                        <span class='icon'>
                            <i class='fas fa-angle-down' aria-hidden='true'></i>
                        </span>
                    </a>
                </header>
              </div>
              <br>";
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
</head>
<body>
<?php
include_once 'common/navbar.php';
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
                        <a class="is-active" href="admin.php">User Overview</a>
                        <ul>
                            <li><a class="is-active" style="background-color: #d3d3d3;" href="admin.php">Existing Users</a></li>
                            <li><a href="users/create.php">Create a User</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="column">
            <label class="label is-large">Existing Users</label>
            <div class="has-text-centered">
                <button id="myButton" class="button is-link" >Create a User</button>
                <script type="text/javascript">
                    document.getElementById("myButton").onclick = function () {
                        location.href = "users/create.php";
                    };
                </script>
            </div>
            <br>
            <div style="overflow-y: scroll; height:600px;">
                <?php
                // Preparing a select statement
                $query = "SELECT user_id, email, first_name, last_name, user_type FROM user";

                if ($statement = mysqli_prepare($connection, $query)) {

                    // Attempting to execute the prepared statement
                    if (mysqli_stmt_execute($statement)) {

                        // Storing the result
                        mysqli_stmt_store_result($statement);

                        // Checking if any results were returned
                        if (mysqli_stmt_num_rows($statement)) {
                            mysqli_stmt_bind_result($statement, $user_id, $email, $first_name, $last_name, $type);
                            // Getting each one of the results
                            while ($result = mysqli_stmt_fetch($statement)) {
                                $color = "green";
                                $display = "Employee";
                                if (isAdminType($type)) {
                                    $color = "orange";
                                    $display = "Administrator";
                                }
                                echo userCard($user_id, $first_name, $last_name, $email, $color, $display);
                            }
                        } else {
                            echo noUsersScreen();
                        }
                    }
                }
                ?>
            </div>
        </main>
    </div>
</section>
</body>
</html>