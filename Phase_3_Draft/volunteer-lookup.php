<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Volunteer.php';

session_start();

$db = new Database();
$conn = $db->getConn();

if (!isLoggedIn()) {

  die("unauthorised");
}

// $first_name = '';
// $last_name = '';

// $volunteers = Volunteer::searchVolunteer($conn, $last_name, $first_name);


if ($_SERVER["REQUEST_METHOD"] === "POST") {

  // var_dump($_POST);
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $volunteers = Volunteer::searchVolunteer($conn, $last_name, $first_name);
}

?>


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
  <a class="nav-link" href="view-reports.php">View Reports</a>
</li>
<?php endif; ?>
</ul>

<h2>Volunteer Lookup</h2>

<form method="post">
  <!-- ECHO NEEDED-->
  <label for="fname">First name:</label><br>
  <input type="text" name="first_name" value="<?php echo isset($_POST["first_name"]) ? $_POST["first_name"] : ""; ?>"><br>
  <label for="lname">Last name:</label><br>
  <input type="text" name="last_name" value="<?php echo isset($_POST["last_name"]) ? $_POST["last_name"] : ""; ?>"><br>
  <input type="submit" value="Submit">

  <table width='50%'>
    <tr>
      <th>Last Name</th>
      <th>First Name</th>
      <th>Email Address</th>
      <th>Phone Number</th>
    </tr>

    <?php foreach ($volunteers as $volunteer) : ?>
      <tr>
        <td><?= $volunteer['last_name']; ?></td>
        <td><?= $volunteer['first_name']; ?></td>
        <td><?= $volunteer['email']; ?></td>
        <td><?= $volunteer['volunteer_phone_num'] ?></td>
      </tr>

    <?php endforeach; ?>
  </table>

</form>
