<?php
    $BASE_URL = getenv('BASE_URL') ? getenv('BASE_URL') : "http://localhost:8080";

    session_start();

    $application_id = fetchApplicationId();
    $reject = "{$BASE_URL}/applications/reject.php?application_id=" .$application_id;
    $approve = "{$BASE_URL}/applications/approve.php?application_id=".$application_id;
    $body = newApplicationMessage($approve, $reject);
    $recipient_mail = 'craig.pelton@mailtrap.io';
    $recipient_name = 'Craig Pelton';

    include_once '../common/email.php';
    sendMail($recipient_mail, $recipient_name, 'Leave of Absence Request', $body);

    echo "<script>window.location.assign('../welcome.php')</script>";

function fetchApplicationId()
{
    // Getting the id of the applications
    if (isset($_GET['application_id'])) {
        $application_id = $_GET['application_id'];
        unset($_GET['application_id']);
    }
    return $application_id;
}

function newApplicationMessage($approveUrl, $rejectUrl)
{
    return "
    <div>
       <p>Dear supervisor,<br><br> employee 
          <span style='font-weight:bold;'>
          " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . "
          </span> 
          requested for some time off, starting on 
          <span style='font-weight:bold;'>
          " . $_SESSION['start'] . "
          </span> 
          and ending on
          <span style='font-weight:bold;'>
          " . $_SESSION['end'] . "
          </span>
          , stating the reason:
       </p>
       <p>
          <q style='font-style:italic;'>
          " . $_SESSION['reason'] . "
          </q>
       </p>
       <p>
          Click on one of the below links to approve or reject the applications:
       </p>
       <p>
       <div style='display: flex; flex-direction: row; justify-content:center;'>
          <div style='display:flex; justify-content:flex-start; padding:5px;'>
             <a class='button' href=" . $approveUrl . " style='border:none; color:white; padding: 10px 20px; text-align:center; text-decoration:none; 
                display:inline-block; font-size:16px; background-color:#4caf50'>Approve</a>
          </div>
          <div style='display:flex; justify-items:flex-end; padding: 5px;'>
             <a class='button' href=" . $rejectUrl . " style='border:none; color:white; padding: 10px 20px; text-align:center; text-decoration:none; 
                display:inline-block; font-size:16px; background-color:#d40000'>Reject</a>
          </div>
       </div>
       </p>
    </div>
    ";
}
