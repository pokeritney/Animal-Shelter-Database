<?php

require 'classes/Database.php';
require 'classes/Application.php';
require 'lib/url.php';
require 'lib/auth.php';

session_start();

if (! isLoggedIn()) {

    die("unauthorised");

}

$application = new Application();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();
    $conn = $db->getConn();

    $application->primary_first_name = $_POST['primary_first_name'];
    $application->primary_last_name = $_POST['primary_last_name'];
    $application->email = $_POST['email'];
    $application->phone_number = $_POST['phone_number'];
    $application->street = $_POST['street'];
    $application->city = $_POST['city'];
    $application->state = $_POST['state'];
    $application->zip_code = $_POST['zip_code'];
    $aaplication->co_first_name = $_POST['co_first_name'];
    $application->co_last_name = $_POST['co_last_name'];
    $application->email = $_POST['email'];
    $application->application_date = $_POST['application_date'];
    $application->status = 'Pending Approval';

    if ($application->createadopter($conn)) {
        if ($application->createapplication($conn)) {
        redirect("/Phase_3/application.php?id={$application->id}");
      }
    }

}



?>
<?php require 'lib/header.php'; ?>
<a href="animal.php">Animal Dashboard</a>
<h2>New Application</h2>

<?php require 'lib/application-form.php'; ?>

<?php require 'lib/footer.php'; ?>
