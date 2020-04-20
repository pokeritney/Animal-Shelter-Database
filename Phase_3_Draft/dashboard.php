<?php

require 'lib/database.php';
require 'lib/auth.php';
session_start();

$conn = getDB();

if ( ! isLoggedIn()) {

    die("unauthorised");

}


$sqlCapacity = "SELECT A.species_name, A.capacity, CASE WHEN D.num_adopted IS NULL THEN 0 ELSE D.num_adopted END as num_adopted, CASE WHEN E.num_animals IS NULL THEN 0 ELSE E.num_animals END AS num_animals
FROM species A LEFT JOIN (SELECT B.species_name, count(B.pet_id) num_adopted
FROM breed B INNER JOIN Adoption C ON B.pet_id = C.pet_id
GROUP BY B.species_name) D
ON A.species_name = D.species_name
LEFT JOIN (SELECT DISTINCT species_name, count(DISTINCT pet_id) as num_animals FROM breed GROUP BY species_name) E
ON A.species_name = E.species_name;";


$sqlAnimal = "SELECT A.name, B.species_name, B.breed_name, A.sex, A.alteration_status,
A.birth_date,timestampdiff(YEAR, A.birth_date, now()) as age_year,
timestampdiff(MONTH, A.birth_date, now()) % 12 as age_month,
CASE WHEN A.alteration_status = TRUE AND D.num_required is NULL
THEN 'Adoptable' ELSE 'Not Adoptable' END AS adoptability_status
FROM Animal A INNER JOIN (SELECT pet_id,  species_name, GROUP_CONCAT(DISTINCT breed_name
        ORDER BY breed_name ASC SEPARATOR '/'
      ) AS breed_name
      FROM  Breed
		GROUP BY pet_id, species_name

  ) as B ON A.animalID = B.pet_id
  LEFT JOIN
  (SELECT DISTINCT B.pet_id, COUNT(A.vaccine_name) num_required
FROM vaccinationrequired A JOIN breed B ON A.species_name = B.species_name
LEFT JOIN vaccination C ON A.vaccine_name = C.vaccine_name
WHERE A.required = TRUE
AND (C.administer_date IS NULL OR C.expiration_date < now())
GROUP BY B.pet_id) AS D ON A.animalID = D.pet_id
LEFT JOIN adoption E ON A.animalID = E.pet_id
AND E.application_number IS NULL ";


$resultsCapacity = mysqli_query($conn, $sqlCapacity);
$resultsAnimal =mysqli_query($conn, $sqlAnimal);

if($resultsCapacity === false) {
  echo mysqli_error($conn);
} else {
    $species = mysqli_fetch_all($resultsCapacity, MYSQLI_ASSOC);

}

if($resultsAnimal === false) {
  echo mysqli_error($conn);
} else {
    $animals = mysqli_fetch_all($resultsAnimal, MYSQLI_ASSOC);

}


if ($_SERVER["REQUEST_METHOD"] == "POST"){

  var_dump($_POST);

}


?>

<?php require 'lib/header.php';?>

<?php if (isLoggedIn()): ?>

    <p>You are logged in. <a href="logout.php">Log out</a></p>

<?php else: ?>

    <p>You are not logged in. <a href="login.php">Log in</a></p>

<?php endif; ?>

<?php if (isAdminUser()): ?>
  <p>AdminUser</p>
<?php endif; ?>

<?php if (isEmployee()): ?>
  <p>Employee</p>
<?php endif; ?>

<?php if (isVolunteer()): ?>
  <p>Volunteer</p>
<?php endif; ?>




<h3>Number of Available Spaces</h3>
<ul>
  <?php foreach ($species as $specie): ?>
      <?php
          if ($specie['num_animals']>0) {
            $numAvailable = $specie['capacity'] - $specie['num_animals'] + $specie['num_adopted'];
          }else $numAvailable = $specie['capacity'];

      ?>

      <li>
        <article>
          <h3><?= $specie['species_name'];?>
              <?= $numAvailable ?>
          </h3>

        </article>
      </li>
    <?php endforeach; ?>
</ul>

<h2>Animal Dashboard</h2>

<table>

<form method="post">
  <tr>
    <td>
      Filter by Species:
    </td>
    <td>
      Filter by Adoptability Status:
    </td>
  </tr>
  <tr>
    <td>
    <select name="speciesFilter">
      <option value="%">View All</option>
      <?php foreach ($species as $specie): ?>
      <option value=<?= $specie['species_name']; ?> ><?= $specie['species_name']; ?></option>

      <?php endforeach; ?>

    </select>
  </td>
<td>

    <div>
      <input type = "radio" name="statusFilter" value="All">All</br>
      <input type = "radio" name="statusFilter" value="Adoptable">Adoptable</br>
      <input type = "radio" name="statusFilter" value="Not Adoptable">Not Adoptable</br>
    </div>
  </td>
  </tr>
<tr>
    <td><button>Filter</button></td>
</tr>
</form>
</table>


<table>
  <colgroup span="6"></colgroup>
    <tr>
      <th>Name</th>
      <th>Species</td>
      <th>Breed</td>
      <th>Sex</th>
      <th>Alteration Status</th>
      <th>Age</th>
      <th>Adoptability Status</th>
    </tr>
<?php foreach ($animals as $animal): ?>
      <?php
        if($animal['alteration_status']=TRUE){
          $alterationStatus="True";
        }else $alterationStatus="False";

      ?>
      <tr>
        <td><?= $animal['name']; ?></td>
        <td><?= $animal['species_name']; ?></td>
        <td><?= $animal['breed_name']; ?></td>
        <td><?= $animal['sex']; ?></td>
        <td><?= $alterationStatus ?></td>
        <td><?= $animal['age_year']; ?> Years
            <?= $animal['age_month']; ?> Months Old</td>
        <td><?= $animal['adoptability_status']; ?></td>
      </tr>

<?php endforeach; ?>
</table>




<?php require 'lib/footer.php'; ?>
