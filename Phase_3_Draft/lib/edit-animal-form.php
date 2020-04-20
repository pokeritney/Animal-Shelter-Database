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
    <input name="name" id="name" placeholder="Name" value="<?= htmlspecialchars($animal->name); ?>" disabled>
  </div>

  <div class="form-group">
    <label for="animalID">Animal's ID:</label>
    <input name="animalID" id="animalID" value="<?= htmlspecialchars($animal->animalID); ?>" disabled>
  </div>

    <div class="form-group">
      <label for="species_name">Species:</label>
      <input name="species_name" id="species_name" value="<?= htmlspecialchars($animal->species_name); ?>" disabled>
      <?= $species; ?>
    </div>

    <fieldset>
      <legend>Breed:</legend>

      <?php foreach ($breeds as $breed) : ?>

        <div class="form-check">
          <input type="checkbox" name="breed[]" value="<?= $breed['breed_name'] ?>"
               id="breed<?= $breed['breed_name'] ?>" <?php if(in_array($breed['breed_name'], $breed_ids)) : ?> checked <?php endif; ?>
               <?php if(in_array('Mixed', $breed_ids) or in_array('Unknown', $breed_ids)) : ?>
               <?php else : ?> disabled
              <?php endif; ?>
               >
          <label for="breed"<?= $breed['breed_name'] ?>><?= htmlspecialchars($breed['breed_name']) ?></label>
        </div>

      <?php endforeach; ?>

    </fieldset>



  <div class="form-check">
    <label for="sex">Sex:</label><br>
    <?php if($animal->sex=='unknown') : ?>
    <input type="radio" id="male" name="sex" value="male" <?php if($animal->sex==='male') echo 'checked="checked"'; ?> >
    <label for="male">Male</label><br>
    <input type="radio" id="female" name="sex" value="female" <?php if($animal->sex==='female') echo 'checked="checked"'; ?> >
    <label for="female">Female</label><br>
    <input type="radio" id="unknown" name="sex" value="unknown" <?php if($animal->sex==='unknown') echo 'checked="checked"'; ?> >
    <label for="Unknown">Unknown</label>
  <?php else : ?>

    <input type="radio" id="male" name="sex" value="male" <?php if($animal->sex==='male') echo 'checked="checked"'; ?> disabled>
    <label for="male">Male</label><br>
    <input type="radio" id="female" name="sex" value="female" <?php if($animal->sex==='female') echo 'checked="checked"'; ?> disabled>
    <label for="female">Female</label><br>
    <input type="radio" id="unknown" name="sex" value="unknown" <?php if($animal->sex==='unknown') echo 'checked="checked"'; ?> disabled>
    <label for="unknown">Unknown</label>

  <?php endif; ?>
  </div>

  <div class="form-check">
    <label for="alteration_status">Alteration Status:</label>
    <?php if($animal->alteration_status==0) : ?>
    <input type="hidden" id="alteration_status" name="alteration_status" value="0">
    <input type="checkbox" id="alteration_status" name="alteration_status" value="1"
      <?php if($animal->alteration_status==='1') echo 'checked="checked"'; ?> >

  <?php else : ?>
    <input type="checkbox" id="alteration_status" name="alteration_status" value="1"
    <?php if($animal->alteration_status==='1') echo 'checked="checked"'; ?>  disabled>
  <?php endif; ?>
  <label for="alteration_status"> (check for yes)</label>
  </div>

  <div class="form-group">
    <label for="age_months">Age in Months:</label>
    <input id="age_months" name="age_months" value="<?= htmlspecialchars($animal->age_months); ?>" disabled>
  </div>

  <div class="form-group">
    <label for="description">Description:</label>
    <textarea name="description" rows="5" cols="40" id="description" placeholder="Description" disabled><?= htmlspecialchars($animal->description); ?></textarea>
  </div>

  <div class="form-group">
    <label for="microchip_id">Microchip Id:</label>
    <input name="microchip_id" id="microchip_id" placeholder="Microchip ID" value="<?= htmlspecialchars($animal->microchip_id); ?>">
  </div>

  <div class="form-check">
    <label for="local_control">Surrender by Local Control:</label>
    <input type="checkbox" id="local_control" name="local_control" value="1"
     <?php if($animal->local_control==='1') echo 'checked="checked"'; ?> disabled>
    <label for="local_control">(check for yes)</label>
  </div>

  <div class="form-group">
    <label for="surrender_date">Surrender Date:</label>
    <input name="surrender_date" id="surrender_date" value="<?= htmlspecialchars($animal->surrender_date); ?>"
    disabled>
  </div>

  <div class="form-group">
    <label for="surrender_reason">Surrender Reason:</label>
    <textarea name="surrender_reason" rows="5" cols="40" id="surrender_reason" placeholder="Surrender Reason" disabled><?= htmlspecialchars($animal->surrender_reason); ?></textarea>
  </div>


  <button>Save</button>
  <input type="reset" value="Reset">
</form>
