<?php

require 'classes/Database.php';
require 'classes/Animal.php';
require 'classes/Breed.php';
require 'lib/url.php';
require 'lib/auth.php';

session_start();

$db = new Database();
$conn = $db->getConn();

$animal = new Animal();
$breed_names = [];

if ( ! isLoggedIn()) {

    die("unauthorised");

}

if (isset($_GET['species'])) {
    $breeds = Breed::getAll($conn, $_GET['species']);
    $species = $_GET['species'];


} else {
    $breeds = null;
}




$user_name =$_SESSION['username'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();
    $conn = $db->getConn();


    $animal->name = $_POST['name'];
    $animal->sex = $_POST['sex'];
    $animal->alteration_status = $_POST['alteration_status'];
    $animal->age_months = $_POST['age_months'];
    $animal->description = $_POST['description'];
    if($_POST['microchip_id']==""){
      $animal->microchip_id = Null;
    }else{
      $animal->microchip_id = $_POST['microchip_id'];
    }

    $animal->local_control = $_POST['local_control'];
    $animal->surrender_date = $_POST['surrender_date'];
    $animal->surrender_reason = $_POST['surrender_reason'];
    $animal->user_name = $user_name;
    $animal->species_name = $species;

    $breed_names = $_POST['breed'] ?? [];

    if ($animal->insertAnimal($conn)) {
        if ($animal->setBreed($conn, $breed_names)) {
        }
        redirect("/Phase_3/animal-detail.php?id={$animal->id}");
    }


}


?>


<?php require 'lib/header.php'; ?>
<ul class="nav">
<?php if (isLoggedIn()): ?>
<li class="nav-item">
<a class="nav-link" href="logout.php">Log out</a>
</li>
<?php else: ?>
<li class="nav-item">
<a class="nav-link" href="login.php">Log in</a>
</li>
<?php endif; ?>
<li class="nav-item">
<a class="nav-link" href="animal.php">Animal Dashboard</a>
</ul>

<h2>Add an Animal</h2>

<?php require 'lib/animal-form.php'; ?>

<?php require 'Phase_3/lib/footer.php'; ?>
