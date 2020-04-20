<?php


require 'classes/Database.php';
require 'classes/Animal.php';
require 'classes/Vaccination.php';
require 'lib/url.php';
require 'lib/auth.php';


session_start();

$db = new Database();
$conn = $db->getConn();

$animal = new Animal();
$vaccination = new Vaccination();

if ( ! isLoggedIn()) {

    die("unauthorised");

}
if (isset($_GET['id'])) {
    $animal = Animal::getByID($conn, $_GET['id']);
    $vacs = Vaccination::getAll($conn, $animal->species_name);


} else {
    $vacs = null;
}

$pet_id = $animal->animalID;
$species_name = $animal->species_name;
$user_name =$_SESSION['username'];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = new Database();
  $conn = $db->getConn();

  $vaccination->vaccine_name = $_POST['vaccine_name'] ?? '';

  $vaccination->administer_date = $_POST['administer_date'];
  $vaccination->expiration_date = $_POST['expiration_date'];
  $vaccination->user_name = $user_name;
  $vaccination->species_name = $species_name;
  $vaccination->pet_id = $pet_id;
  $vaccination->vaccination_number = $_POST['vaccination_number'];

  if ($vaccination_number == '') {
      $vaccination->vaccination_number = null;
  }

  if($vaccination->vaccine_name<>'' and $vaccination->administer_date<>''){
      $validate = Vaccination::datevalidate($conn,
      $vaccination->pet_id, $vaccination->vaccine_name, $vaccination->administer_date);
      if($validate['num']>0){
        $vaccination->errors[] = 'Vaccine is not yet due for this animal!';
      }
  }

  if ($vaccination->insertVaccination($conn)) {
      if ($vaccination->insertVacctype($conn)) {
      }
      redirect("/Phase_3/animal-detail.php?id={$vaccination->pet_id}");
  }



}



?>


<?php require 'lib/header.php'; ?>

<?php require 'lib/header.php'; ?>
<ul class="nav">
<?php if (isLoggedIn()) : ?>
  <li class="nav-item">
  <a class="nav-link" href="logout.php">Log out</a>
</li>
<?php else : ?>
  <li class="nav-item">
  <a class="nav-link" href="login.php">Log in</a>
</li>
<?php endif; ?>
 <li class="nav-item">
<a class="nav-link" href="animal.php">Animal Dashboard</a>
</li>
<?php if (isAdminUser()) : ?>
  <li class="nav-item">
  <a class="nav-link" href="animal-detail.php?id=<?= $animal->animalID; ?>">Animal Details</a>
</li>
<?php endif; ?>
</ul>




<h2>Add Vaccination</h2>

<?php require 'lib/vaccination-form.php'; ?>

<?php require 'Phase_3/lib/footer.php'; ?>
