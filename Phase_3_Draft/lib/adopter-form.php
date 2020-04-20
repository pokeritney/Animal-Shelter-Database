<h3>Search Results</h3>
<table>
  <tr>
    <th>Application Id</th>
    <th>Application Date</th>
    <th>Name</th>
    <th>Email</th>
    <th>Telephone</th>
    <th>Address</th>
    <th>Co-Applicant Name</th>
    <th>Status</th>
    <th>Adopt</th>

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
   <td><a href="add-adoption.php?petid=<?= $animalID?>&applicationid=<?= $application['applicationID'] ?>">Adopt</a></td>

  </tr>

  <?php endforeach; ?>
</table>
