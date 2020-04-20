<?php


require 'lib/auth.php';
require 'classes/Database.php';
require 'classes/Application.php';
require 'lib/url.php';

session_start();

$db = new Database();
$conn = $db->getConn();



if ( ! isLoggedIn()) {

    die("unauthorised");

  }

  if (isset($_GET['id'])) {

      $review = Application::update($conn, $_GET['id'], $_GET['status']);

      if ( ! $review) {
          die("Application  not found");
      }

  }


$applications = Application::getAll($conn);


?>

<?php require 'lib/header.php';?>
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
</li>
</ul>


<h2>Review Pending Applications</h2>


<table class="table">
  <tr>
    <th>Application Id</th>
    <th>Application Date</th>
    <th>Name</th>
    <th>Email</th>
    <th>Telephone</th>
    <th>Address</th>
    <th>Co-Applicant Name</th>
    <th>Status</th>
    <th>Approve</th>
    <th>Reject</th>
  </tr>
  <?php foreach ($applications as $application): ?>
  <tr>
    <td><?= $application['applicationID']; ?></td>
    <td><?= $application['application_date']; ?></td>
    <td><?= $application['primary_first_name']; ?> <?= $application['primary_last_name']; ?></td>
    <td><?= $application['email']; ?></td>
    <td><?= $application['phone_number']; ?></td>
    <td><?= $application['street']; ?>
        <?= $application['city']; ?>,
        <?= $application['state']; ?>
        <?= $application['zip_code']; ?>
    </td>
   <td><?= $application['co_first_name']; ?> <?= $application['co_last_name']; ?></td>
   <td><?= $application['status']; ?></td>
   <td><a href="edit-application.php?id=<?= $application['applicationID']; ?>&status=Approved">Approve</a></td>
   <td><a href="edit-application.php?id=<?= $application['applicationID']; ?>&status=Rejected">Reject</a></td>
  </tr>

  <?php endforeach; ?>
</table>


<?php require 'lib/footer.php'; ?>
