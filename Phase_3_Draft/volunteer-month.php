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

$monthYears = Volunteer::monthYear($conn);
// echo $monthYears;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  // var_dump($_POST);
  $comb = explode('-', $_POST['comb']);
  $month = $comb[0];
  $year = $comb[1];
  $volunteers = Volunteer::topVolunteer($conn, $year, $month);
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


<h3>Volunteer of the Month</h3>

<table>
  <form method="post">
    <th>Filter by</th>
    <tr>
      <td>
        Months:
      </td>
    </tr>
    <tr>
      <td>
        <div>
          <select size="1" id="comb" name="comb">
            <?php
            foreach ($monthYears as $monthYear) {
            ?>
              <option value="<?php echo $monthYear['comb']; ?>"><?php echo $monthYear['comb']; ?></option>
            <?php
            }
            ?>
          </select>
        </div>
      </td>
    </tr>
    <tr>
      <td><button>Filter</button></td>
    </tr>
</table>

<table width='50%'>
  <tr>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Email Address</th>
    <th>Hours Worked</th>
  </tr>

  <?php foreach ($volunteers as $volunteer) : ?>
    <tr>
      <td><?= $volunteer['first_name']; ?></td>
      <td><?= $volunteer['last_name']; ?></td>
      <td><?= $volunteer['email']; ?></td>
      <td><?= $volunteer['total'] ?></td>
    </tr>

  <?php endforeach; ?>
</table>

</form>

<script type="text/javascript">
  document.getElementById('comb').value = "<?php echo $_POST['comb']; ?>";
</script>
