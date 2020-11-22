<?php

function notifyUser($application_id, $status, $connection)
{
$user_id = get_application($application_id, $connection)->user_id;
$user_email = get_user($user_id, $connection)->email;
$user_firstname = get_user($user_id, $connection)->first_name;
$user_lastname = get_user($user_id, $connection)->last_name;
$user_name = $user_firstname." ".$user_lastname;

$submission_date = (get_application($application_id, $connection))->submission_date;

$mailContent = applicationAcceptedMessage($status, $submission_date);
include_once '../common/email.php';
sendMail($user_email, $user_name, 'Leave of Absence Request', $mailContent);
}

function applicationAcceptedMessage($status, $submission_date)
{
    return "
        <div>
            <p>Dear employee, <br><br>
            your supervisor has " . $status . " your application submitted on "
        . $submission_date . ".
            </p>
        </div>
        ";
}