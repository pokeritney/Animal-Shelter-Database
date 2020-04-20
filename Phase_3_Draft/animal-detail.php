<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Vaccination.php';
require 'classes/Animal.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if (isset($_GET['id'])) {
    $animal = Animal::getByID($conn, $_GET['id']);
    $vaccinations = Vaccination::getByID($conn, $_GET['id']);

} else {
    $animal = null;
}


if ( ! isLoggedIn()) {

    die("unauthorised");

}



?>

<?php require 'lib/header.php';?>


<ul class="nav">
<?php if (isLoggedIn()): ?>
    <li class="nav-item">
    <a class = "nav-link" href="logout.php">Log out</a>
  </li>
<?php else: ?>
    <li class="nav-item">
    <a class = "nav-link" href="login.php">Log in</a>
  </li>
<?php endif; ?>
<li class="nav-item">
<a class = "nav-link" href="animal.php">Animal Dashboard</a>
</li>
<li class="nav-item">
<a class = "nav-link" href="edit-animal.php?id=<?= $animal->animalID; ?>">Edit Animal</a>
</li>
<li class="nav-item">
<a class = "nav-link" href="add-vaccination.php?id=<?= $animal->animalID; ?>">Add Vaccination</a>
</li>
<?php if (isEmployee()): ?>
  <?php if($animal->adoptability_status=='Adoptable'): ?>
    <li class="nav-item">
      <a class="nav-link" href="search.php?id=<?= $animal->animalID; ?>">Add Adoption</a>
    </li>
  <?php endif; ?>
<?php endif; ?>

</ul>


<h2>Animal Details</h2>

<?php if ($animal) : ?>

        <h2><?= htmlspecialchars($animal->name); ?></h2>
        <p>ID:  <?= htmlspecialchars($animal->animalID); ?></p>
        <p>Species: <?= htmlspecialchars($animal->species_name); ?></p>
        <p>Breed:  <?= htmlspecialchars($animal->breed_name); ?></p>
        <p>Sex:  <?= htmlspecialchars($animal->sex); ?></p>

        <?php
          if($animal->alteration_status==TRUE){
            $alterationStatus="True";
          }else $alterationStatus="False";

        ?>
        <?php
          if($animal->local_control==TRUE){
            $localControl="Yes";
          }else $localControl="No";

        ?>

        <p>Alteration Status:  <?= htmlspecialchars($alterationStatus); ?></p>
        <p>Age in Months: <?= htmlspecialchars($animal->age_months); ?></p>
        <p>Description:  <?= htmlspecialchars($animal->description); ?></p>
        <p>Microchip Id:  <?= htmlspecialchars($animal->microchip_id); ?></p>
        <p>Surrender Date:  <?= htmlspecialchars($animal->surrender_date); ?></p>
        <p>Surrender Reason:  <?= htmlspecialchars($animal->surrender_reason); ?></p>
        <p>Surrender by Animal Control:  <?= htmlspecialchars($localControl); ?></p>
        <p>Adoptability Status:  <?= htmlspecialchars($animal->adoptability_status); ?></p>




        <h3>Vaccinations</h3>

        <?php if ($vaccinations) : ?>


            <table>
              <colgroup span="5"></colgroup>
              <tr>
                <th>Vaccine Type</th>
                <th>Required</th>
                <th>Administer Date</th>
                <th>Expiration Date</th>
                <th>Vaccination Number</th>
              </tr>
              <?php foreach ($vaccinations as $vaccination): ?>

              <tr>
                <td> <?= htmlspecialchars($vaccination->vaccine_name); ?> </td>
                <td> <?php if($vaccination->required==1) :?>Yes<?php else :?>No<?php endif; ?></td>
                <td> <?= htmlspecialchars($vaccination->administer_date); ?> </td>
                <td> <?= htmlspecialchars($vaccination->expiration_date); ?> </td>
                <td> <?= htmlspecialchars($vaccination->vaccination_number); ?> </td>
              </tr>



              <?php endforeach; ?>

      </table>
    <?php else : ?>
        <p>Vaccination History not found.</p>
    <?php endif; ?>

<?php else : ?>
    <p>Animal not found.</p>
<?php endif; ?>





<?php require 'includes/footer.php'; ?>





<?php require 'lib/footer.php'; ?>
