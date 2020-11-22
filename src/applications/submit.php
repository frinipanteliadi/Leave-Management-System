<?php

    // Initializing the session
    session_start();

    // Including the configuration file
    include_once "../common/db.php";
    include_once "../common/auth.php";

    // Checking if the user is logged in
    if(!isLoggedIn()) {
        header("location: index.php");
        exit();
    }

    // Checking if the user is an admin
    if(isAdmin()) {
        header("location: admin.php");
        exit();
    }

    // Defining variables and initializing them with empty values
    $start_date = "";
    $end_date = "";
    $reason = "";

    $start_date_err = "";
    $end_date_err = "";
    $dates_err = "";
    $reason_err = "";

    // Processing the data of the submitted form

if($_SERVER["REQUEST_METHOD"] == "POST") {
    date_default_timezone_set("Europe/Athens");
    $today = date('Y-m-d');

    // Checking the provided values
    // Start date check
    $start_date = trim($_POST["start_date"]);
    $start_date_err = validateStartDate($start_date);

    // End date check
    $end_date = trim($_POST["end_date"]);
    $end_date_err = validateEndDate($end_date);

    // Checking both dates
    if ($start_date > $end_date)
        $end_date_err = "Please, enter valid end date";

    // Checking if an application with those dates already exists
    $query = "SELECT start_date, end_date FROM applications WHERE user_id=? AND status IN ('approved', 'pending')";

    $statement = $connection->prepare($query);
    if (!($statement = $connection->prepare($query)))
        echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;

    if (!$statement->bind_param("i", $_SESSION['user_id'])) {
        echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
    }

    if (!$statement->execute()) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }

    // Storing the result
    $statement->store_result();

    // Getting the number of rows that were returned
    $num_of_rows = $statement->num_rows();

    // Binding the results
    $statement->bind_result($start_date_res, $end_date_res);

    // Fetching every result
    while($statement->fetch()) {

        if(($start_date_res <= $start_date && $start_date <= $end_date_res) || ($start_date_res <= $end_date && $end_date <= $end_date_res)) {
            $start_date_err = "A requested has already been submitted, which includes those dates";
            break;
        }

    }

    // Reason check
    $reason = trim($_POST["reason"]);
    $reason_err = validateReason($reason);


    // No errors were found
    if (empty($start_date_err) && empty($end_date_err) && empty($reason_err)) {
        $requested_days = daysBetween($start_date, $end_date);
        $status = "pending";

        createApplication(
            $connection,
            $status,
            $start_date,
            $end_date,
            $requested_days,
            $reason
        );

        // Getting the id of the applications that was just inserted
        $application_id = $connection->insert_id;

        // Sending an email to the administrator
        if (!isset($_SESSION['start']))
            $_SESSION['start'] = $start_date;
        if (!isset($_SESSION['end']))
            $_SESSION['end'] = $end_date;
        if (!isset($_SESSION['reason']))
            $_SESSION['reason'] = $reason;

        header("location: ./notify_admin.php?application_id=" . $application_id);
    }
    mysqli_close($connection);
}

function daysBetween($start_date, $end_date)
{
    $diff = abs(strtotime($end_date) - strtotime($start_date));
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    return floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24)) +1;
}

function validateStartDate($date) {
    $today = date('Y-m-d');
    if (empty($date))
        return "Please, enter a start date";
    else if ($today > $date)
        return "Please, enter a valid start date";
    return "";
}

function validateEndDate($date) {
    $today = date('Y-m-d');
    if (empty($date))
        return "Please, enter an end date";
    else if ($today > $date)
        return "Please, enter a valid end date";
    return "";
}

function validateReason($reason) {
    if (empty($reason))
        return "Please, enter a reason for requesting a leave of absence";
    return "";
}

function createApplication(mysqli $connection, $status, $start_date, $end_date, $requested_days, $reason)
{
    $now = date('Y-m-d H:i:s');

    $query = "INSERT INTO applications (status, submission_date, start_date, end_date, requested_days, user_id, reason) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $statement = $connection->prepare($query);
    if (!$statement)
        echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;


    // Calculating the total amount of days that were requested

    if (!$statement->bind_param("ssssiis", $status, $now, $start_date, $end_date, $requested_days, $_SESSION["user_id"], $reason)) {
        echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
    }

    if (!$statement->execute()) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,
    initial-scale=1">
    <title>Epignosis - Employee Management Portal</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.epignosishq.com/wp-content/themes/epignosishq/dist/images/favicon/favicon_32x32.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
                            <a class="is-active" href="../welcome.php">Leave of Absence Requests</a>
                            <ul>
                                <li><a class="" href="../welcome.php">Past Applications</a></li>
                                <li><a class="is-active" style="background-color: #d3d3d3;" href="/applications/submit.php">Submit New Application</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </aside>

            <main class="column">
                <label class="label is-large">Submit a New Application</label>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- Start Date -->
                    <div class="field">
                        <label class="label">From</label>
                        <div class="control <?php echo (!empty($start_date_err)) ? 'has-error' : ''; ?>">
                            <input class="input" type="date" name="start_date" required>
                        </div>
                    <p class="help is-danger"> <?php echo $start_date_err;?> </p>
                    </div>

                    <!-- End Date -->
                    <div class="field">
                        <label class="label">To</label>
                        <div class="control">
                            <input class="input" type="date" name="end_date" required>
                        </div>
                    </div>
                    <p class="help is-danger"> <?php echo $end_date_err; ?> </p>

                    <!-- Reason -->
                    <div class="field">
                        <label class="label">Reason</label>
                        <div class="control">
                            <textarea class="textarea" placeholder="Explain your reasons for requesting a leave of absence" spellcheck="false" name="reason" required></textarea>
                        </div>
                    </div>

                     <br>

                    <!-- Submit & Cancel Buttons -->
                    <div class="field is-grouped is-flex is-justify-content-center">
                        <p class="control">
                            <button type="submit" class="button is-success has-background-link">
                                Submit
                            </button>
                        </p>
                        <p class="control">
                            <button class="button is-light" id="myButton">
                                Cancel
                            </button>
                            <script type="text/javascript">
                                document.getElementById("myButton").onclick = function () {
                                    location.href = "../welcome.php";
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