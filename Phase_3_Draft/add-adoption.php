<?php

require 'classes/Database.php';
require 'classes/Adoption.php';
require 'lib/url.php';
require 'lib/auth.php';

session_start();

if (! isLoggedIn()) {

    die("unauthorised");

}

$db = new Database();
$conn = $db->getConn();

if (isset($_GET['petid'])) {

    $pet_id = $_GET['petid'];
}

if (isset($_GET['applicationid'])) {

    $application_number = $_GET['applicationid'];
}

$adoption = new Adoption();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();
    $conn = $db->getConn();

    $adoption->pet_id = $pet_id;
    $adoption->application_number = $application_number;
    $adoption->adoption_date = $_POST['adoption_date'];
    $adoption->adoption_fee = $_POST['adoption_fee'];


    if ($adoption->addAdoption($conn)) {
        redirect("/Phase_3/animal.php");
      }




}


?>

<?php if (isLoggedIn()): ?>

    <p>You are logged in. <a href="logout.php">Log out</a></p>

<?php else: ?>

    <p>You are not logged in. <a href="login.php">Log in</a></p>

<?php endif; ?>


<?php require 'lib/header.php'; ?>

<a href="animal.php">Animal Dashboard</a>

<h2>Add Adoption</h2>

<form method="post">

<p>Pet ID:  <?= htmlspecialchars($pet_id); ?></p>
<p>Application Number: <?= htmlspecialchars($application_number); ?>
<div>
      <label for="adoption_date">Adoption Date: </label>
      <input type="date" id="adoption_date" name="adoption_date" placeholder="mm/dd/yyyy" value="<?php echo date('Y-m-d'); ?>" >
</div>

<div>
  <label for="adoption_fee">Adoption Fee: </label>
  <input type="text" id="adoption_fee" name="adoption_fee" placeholder="Adoption Fee">
</div>

<button>Add</button>
<input type="reset" value="Reset">

</form>

<?php require 'lib/footer.php'; ?>
