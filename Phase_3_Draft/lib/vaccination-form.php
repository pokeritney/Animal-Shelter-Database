<?php if (! empty($vaccination->errors)) : ?>
    <ul>
        <?php foreach ($vaccination->errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<form method="post">
  <div class="form-group">
    <label for="name">Animal's Name:</label>
    <?= $animal->name; ?>

  </div>

  <div class="form-group">
    <label for="animalID">ID:</label>
    <?= $animal->animalID; ?>
    <div>
      <label for="species_name">Species:</label>
      <?= htmlspecialchars($animal->species_name); ?>
    </div>

    <fieldset>
        <legend>Vaccination:</legend>
    <table>
      <tr>
          <th>Vaccine Name</th>
          <th>Required for Adoption?</th>
      </tr>
            <?php foreach ($vacs as $vac) : ?>
              <tr>
                <td>
                  <input type="radio" name="vaccine_name" value="<?= $vac['vaccine_name'] ?>"
                 id="vac<?= $vac['vaccine_name'] ?>"
                 <?php if ($vaccination->vaccine_name == $vac['vaccine_name']) { echo "checked"; } ?>>
            <label for="vac"<?= $vac['vaccine_name'] ?>><?= htmlspecialchars($vac['vaccine_name']) ?></label>

               </td>
              <td>
                <?php if ($vac['required'] == 0): ?>No<?php else : ?>Yes<?php endif; ?>
              </td>

              </tr>
            <?php endforeach; ?>

   </table>
 </fieldset>





    <div class="form-group">
      <label for="vaccination_number">Vaccination Number:</label>
      <input name="vaccination_number" id="vaccination_number" placeholder="Vaccination Number" value="<?= htmlspecialchars($vaccination->vaccination_number); ?>">
    </div>

<div class="form-group">
    <label for="administer_date">Administer Date:</label>
    <input type="date" id="administer_date" name="administer_date" value="<?= htmlspecialchars($vaccination->administer_date); ?>">
  </div>

  <div class="form-group">
    <label for="expiration_date">Expiration Date:</label>
    <input type="date" id="expiration_date" name="expiration_date" value="<?= htmlspecialchars($vaccination->expiration_date); ?>">
  </div>



    <div>
    <input type="hidden" id="user_name" name="user_name" value=<?= htmlspecialchars($user_name); ?>>
  </div>

  <button>Add</button>
  <input type="reset" value="Reset">
</form>
