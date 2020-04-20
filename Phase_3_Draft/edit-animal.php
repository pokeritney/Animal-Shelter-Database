<?php

require 'classes/Database.php';
require 'classes/Animal.php';
require 'classes/Breed.php';
require 'lib/url.php';
require 'lib/auth.php';

session_start();

$db = new Database();
$conn = $db->getConn();



if ( ! isLoggedIn()) {

    die("unauthorised");

}



if (isset($_GET['id'])) {
    $animal = Animal::getbyID($conn, $_GET['id']);
    $breed_ids = array_column($animal->getBreed($conn, $_GET['id']), 'breed_name');
    $breeds = Breed::getAll($conn, $animal->species_name);


    if ($animal) {

  } else {
      die("animal not found");
  }

} else {
    die("id not supplied, animal not found");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['sex'])) {
      $animal->sex = $_POST['sex'];
    }else{
      $_POST['sex'] = $animal->sex;
    }

    if (isset($_POST['alteration_status'])) {
    $animal->alteration_status = $_POST['alteration_status'];
  }else {
    $_POST['alteration_status'] = $animal->alteration_status;
  }

   if (isset($_POST['microchip_id'])) {
      if ($_POST['microchip_id']==""){
          $animal->microchip_id = Null;
      }else{
        $animal->microchip_id = $_POST['microchip_id'];
      }
  }else {
    $_POST['microchip_id'] = $animal->microchip_id;
  }

  $breed_ids = $_POST['breed'] ?? [];

var_dump($_POST);

  if ($animal->update($conn)) {
    $animal->delete($conn);
    $animal->updateBreed($conn,$breed_ids);
    redirect("/Phase_3/animal-detail.php?id={$animal->animalID}");

    }

}




?>


<?php require 'lib/header.php'; ?>

<ul class = "nav">
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
</li>
<li class="nav-item">
<a class="nav-link" href="animal-detail.php?id=<?= $animal->animalID ?>">Animal Detail</a>
</li>
</ul>

<h2>Edit an Animal</h2>

<?php require 'lib/edit-animal-form.php'; ?>

<?php require 'Phase_3/lib/footer.php'; ?>
