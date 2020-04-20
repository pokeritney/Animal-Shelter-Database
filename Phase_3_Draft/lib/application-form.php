<?php if (! empty($application->errors)) : ?>
    <ul>
        <?php foreach ($application->errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">

  <div class="form-group">
      <label for="primary_first_name">Applicant First Name</label>
      <input name="primary_first_name" id="primary_first_name" placeholder="First Name" required value="<?= htmlspecialchars($application->primary_first_name); ?>">
  </div>
  <div class="form-group">
      <label for="primary_last_name">Applicant Last Name</label>
      <input name="primary_last_name" id="primary_last_name" placeholder="Last Name" required value="<?= htmlspecialchars($application->primary_last_name); ?>">
  </div>
  <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="email" size="30" required value="<?= htmlspecialchars($application->email); ?>">
  </div>

  <div class="form-group">
      <label for="phone_number">Telephone Number </label>
      <input type="phone_number" id="phone_number" name="phone_number" required value="<?= htmlspecialchars($application->phone_number); ?>">
  </div>

<div class="form-group">
  Address:
</div>
  <div class="form-group">
      <label  for="street">Street</label>
      <input name="street" id="street" placeholder="Street" required size=30 value="<?= htmlspecialchars($application->street); ?>">
  </div>
  <div class="form-group">
      <label for="city">City</label>
      <input name="city" id="city" placeholder="City" required value="<?= htmlspecialchars($application->city); ?>">
  </div>
  <div class="form-group">
      <label for="state">State</label>
      <input name="state" id="state" placeholder="State" required value="<?= htmlspecialchars($application->state); ?>">
  </div>
  <div class="form-group">
      <label for="zip_code">Zip Code</label>
      <input name="zip_code" id="zip_code" placeholder="Zip Code" required value="<?= htmlspecialchars($application->zip_code); ?>">
  </div>
  <div class="form-group">
      <label for="co_first_name">Co-Applicant First Name</label>
      <input name="co_first_name" id="co_first_name" placeholder="Co App First Name" value="<?= htmlspecialchars($application->co_first_name); ?>">
  </div>
  <div class="form-group">
      <label for="co_last_name">Co-Applicant Last Name</label>
      <input name="co_last_name" id="co_last_name" placeholder="Co App Last Name" value="<?= htmlspecialchars($application->co_last_name); ?>">
  </div>
  <div class="form-group">
      <label for="application_date">Application Date</label>
      <input type="datetime-local" id="application_date" name="application_date" value="<?= htmlspecialchars($application->application_date); ?>">
  </div>

  <button>Add</button>
<input type="reset" value="Reset">
</form>
