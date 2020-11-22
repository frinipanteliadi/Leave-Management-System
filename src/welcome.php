<?php
    session_start();
    include_once  "common/db.php";

    // Checking if the user is logged in
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false) {
        header("location: index.php");
        exit();
    }

    // Checking if the user is an employee
    if(!($_SESSION["type"] == 1)) {
        header("location: admin.php");
        exit();
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
        <!-- Top section -->
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
                                <a class="is-active" href="welcome.php">Leave of Absence Requests</a>
                                <ul>
                                    <li><a class="is-active" style="background-color: #d3d3d3;" href="welcome.php">Past Applications</a></li>
                                    <li><a href="applications/submit.php">Submit New Application</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <main class="column">
                    <label class="label is-large">Past Applications</label>
                    <div class="has-text-centered">
                        <button id="myButton" class="button is-link" >Submit Request</button>
                        <script type="text/javascript">
                            document.getElementById("myButton").onclick = function () {
                                location.href = "./applications/submit.php";
                            };
                        </script>
                    </div>
                    <br>
                    <div style="overflow-y: scroll; height:600px%;">
                        <?php
                            // Preparing a select statement
                            $query = "SELECT * FROM applications WHERE user_id=? ORDER BY submission_date DESC";

                            if($statement = mysqli_prepare($connection, $query)) {

                                // Binding variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($statement, "i", $_SESSION["user_id"]);

                                // Setting the parameters
                                $param_user_id = $_SESSION["user_id"];

                                // Attempting to execute the prepared statement
                                if(mysqli_stmt_execute($statement)) {

                                    // Storing the result
                                    mysqli_stmt_store_result($statement);

                                    if(mysqli_stmt_num_rows($statement)) {

                                        mysqli_stmt_bind_result($statement, $application_id, $status, $submission_date,
                                            $start_date, $end_date, $requested_days, $user_id, $reason);

                                        $counter = 1;

                                        // Getting each one of the results
                                        while ($result = mysqli_stmt_fetch($statement)) {

                                            echo "<div class='card'>
                                                    <header class='card-header' style='background-color: #3174dc;'>
                                                        <p class='card-header-title' style='color:white;'>
                                                            Application ".$counter."
                                                        </p>
                                                        <a href='#' class='card-header-icon' aria-label='more options'>
                                                            <span class='icon'>
                                                                <i class='fas fa-angle-down' aria-hidden='true'></i>
                                                            </span>
                                                        </a>
                                                    </header>
                                                    <div class='card-content'>
                                                        <div class='content'>
                                                            <ul>
                                                                <li><a style='font-weight:bold; color:black; pointer-events:none;'>Submission Date: </a>".$submission_date."</li>
                                                                <li><a style='font-weight:bold; color:black; pointer-events:none;'>Requested Dates: </a>".$start_date." to ".$end_date."</li>
                                                                <li><a style='font-weight:bold; color:black; pointer-events:none;'>Days Requested: </a>".$requested_days."</li>";

                                            if ($status == "approved")
                                                echo "<li><a style='font-weight:bold; color:black; pointer-events:none;'>Status: </a> 
                                                        <a style='color:green; pointer-events: none;' >
                                                            Approved
                                                        </a>
                                                      </li>";

                                            elseif ($status == "pending")
                                                echo "<li><a style='font-weight:bold; color:black; pointer-events:none;'>Status: </a><a style='color:orange; pointer-events: none;' >Pending</a></li>";

                                            else
                                                echo "<li><a style='font-weight:bold; color:black; pointer-events:none;'>Status: </a><a style='color:red; pointer-events: none;' >Rejected</a></li>";

                                            echo "
                                                            </ul>
                                                        </div>
                                                    </div>
                                                   </div>
                                                   <br>";

                                            $counter = $counter + 1;
                                        }
                                    }
                                    else {
                                        echo "<div class='card'>
                                                <header class='card-header'>
                                                    <p class='card-header-title'>
                                                        No requests have been submitted yet.
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
                                }
                            }
                        ?>
                    </div>
                </main>
            </div>
        </section>
    </body>
</html>