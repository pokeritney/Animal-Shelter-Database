<?php if (! empty($animal->errors)) : ?>
    <ul>
        <?php foreach ($animal->errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
  <div class="form-group">
    <label for="name">Animal's Name:</label>
    <input name="name" id="name" placeholder="Name" value="<?= htmlspecialchars($animal->name); ?>">
  </div>


    <div class="form-group">
      <label for="species_name">Species:</label>
      <input type="hidden" id="species_name" name="species_name" value=<?= htmlspecialchars($species); ?>>
      <?= $species; ?>
    </div>


    <fieldset>
      <legend>Breed:</legend>

      <?php foreach ($breeds as $breed) : ?>
        <div class="form-check">
          <input type="checkbox" name="breed[]" value="<?= $breed['breed_name'] ?>"
               id="breed<?= $breed['breed_name'] ?>"
             <?php if(in_array($breed['breed_name'], $breed_names)) : echo 'checked="checked"'?>
             <?php endif; ?> >
          <label for="breed"<?= $breed['breed_name'] ?>><?= htmlspecialchars($breed['breed_name']) ?></label>

        </div>

      <?php endforeach; ?>

    </fieldset>


  <div class="form-check">
    <label for="sex">Sex:</label><br>
    <input type="radio" id="male" name="sex" value="male" <?php if($animal->sex==='Male') echo 'checked="checked"'; ?> >
    <label for="male">Male</label><br>
    <input type="radio" id="female" name="sex" value="female" <?php if($animal->sex==='Female') echo 'checked="checked"'; ?> >
    <label for="female">Female</label><br>
    <input type="radio" id="unknown" name="sex" value="unknown" <?php if($animal->sex==='Unknown') echo 'checked="checked"'; ?> >
    <label for="Unknown">Unknown</label>
  </div>

  <div class="form-check">
    <label for="alteration_status">Alteration Status:</label>
    <input type="hidden" id="alteration_status" name="alteration_status" value="0">
    <input type="checkbox" id="alteration_status" name="alteration_status" value="1"
      <?php if($animal->alteration_status==='1') echo 'checked="checked"'; ?> >
    <label for="alteration_status"> (check for yes)</label>
  </div>

  <div class="form-group">
    <label for="age_months">Age in Months:</label>
    <input type="number" id="age_months" name="age_months" value="<?= htmlspecialchars($animal->age_months); ?>">
  </div>

  <div class="form-group">
    <label for="description">Description:</label>
    <textarea name="description" rows="5" cols="40" id="description" placeholder="Description"><?= htmlspecialchars($animal->description); ?></textarea>
  </div>

  <div>
    <label for="microchip_id">Microchip Id:</label>
    <input name="microchip_id" id="microchip_id" placeholder="Microchip ID" value="<?= htmlspecialchars($animal->microchip_id); ?>">
  </div>

  <div class="form-check">
    <label for="local_control">Surrender by Local Control:</label>
    <input type="hidden" id="local_control" name="local_control" value="0">
    <input type="checkbox" id="local_control" name="local_control" value="1"
     <?php if($animal->local_control==='1') echo 'checked="checked"'; ?> >
    <label for="local_control">(check for yes)</label>
  </div>

  <div class="form-group">
    <label for="surrender_date">Surrender Date:</label>
    <input type="date" name="surrender_date" id="surrender_date" value="<?= htmlspecialchars($animal->surrender_date); ?>"
    >
  </div>

  <div class="form-group">
    <label for="surrender_reason">Surrender Reason:</label>
    <textarea name="surrender_reason" rows="5" cols="40" id="surrender_reason" placeholder="Surrender Reason"><?= htmlspecialchars($animal->surrender_reason); ?></textarea>
  </div>

  <div>
    <input type="hidden" id="user_name" name="user_name" value=<?= htmlspecialchars($user_name); ?>>
  </div>

  <button>Add</button>
  <input type="reset" value="Reset">
</form>
