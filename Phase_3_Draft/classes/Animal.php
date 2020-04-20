<?php
/**
* Animal
*
* Animal in the Animal Shelter
*/

class Animal
{

  /**
   * Validation errors
   * @var array
   */

   public $errors = [];


  /**
  * Get all Animals
  *
  *@param object $conn Connection to the Database
  *
  *@return array An associative array of all the animal records
  */
  public static function getAll($conn, $speciesFilter, $statusFilter)
  {
    $sql = "SELECT * FROM (SELECT A.animalID, A.name, B.species_name, B.breed_name, A.sex, A.alteration_status,
    A.age_months, A.description,
    A.microchip_id, A.surrender_date, A.surrender_reason, A.local_control,
    CASE WHEN A.alteration_status = TRUE AND D.outstanding = 0
    THEN 'Adoptable' ELSE 'Not Adoptable' END AS adoptability_status, E.application_number
    FROM Animal A INNER JOIN (SELECT pet_id,  species_name, GROUP_CONCAT(DISTINCT breed_name
            ORDER BY breed_name ASC SEPARATOR '/'
          ) AS breed_name
          FROM  Breed
    		GROUP BY pet_id, species_name

      ) as B ON A.animalID = B.pet_id
      LEFT JOIN
      (SELECT T.species_name, R.pet_id, T.num_required, R.num_received, T.num_required - R.num_received as outstanding
       FROM
       (SELECT species_name, count(vaccine_name) as num_required
        from vaccinationrequired
        where required = 1
        group by species_name) T join (select DISTINCT A.species_name, a.pet_id, count(distinct a.vaccine_name) as num_received
        from vaccinationtype A  join vaccinationrequired B on a.species_name = b.species_name and a.vaccine_name = b.vaccine_name
        where B.required = 1
         group by a.species_name, a.pet_id) R on T.species_name = R.species_name) AS D ON A.animalID = D.pet_id
    LEFT JOIN adoption E ON A.animalID = E.pet_id
    ) A
    WHERE A.application_number is null
    AND A.species_name like :speciesFilter
    AND A.adoptability_status like :statusFilter";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':speciesFilter', $speciesFilter, PDO::PARAM_STR);
    $stmt->bindValue(':statusFilter', $statusFilter, PDO::PARAM_STR);


    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

  public static function getByID($conn, $id, $columns = '*')
  {
      $sql = "SELECT * FROM (SELECT A.animalID, A.name, B.species_name, B.breed_name, A.sex, A.alteration_status,
      A.age_months, A.description,
      A.microchip_id, A.surrender_date, A.surrender_reason, A.local_control,
      CASE WHEN A.alteration_status = TRUE AND D.outstanding =0
      THEN 'Adoptable' ELSE 'Not Adoptable' END AS adoptability_status
      FROM Animal A INNER JOIN (SELECT pet_id,  species_name, GROUP_CONCAT(DISTINCT breed_name
              ORDER BY breed_name ASC SEPARATOR '/'
            ) AS breed_name
            FROM  Breed
      		GROUP BY pet_id, species_name

        ) as B ON A.animalID = B.pet_id
        LEFT JOIN
        (SELECT T.species_name, R.pet_id, T.num_required, R.num_received, T.num_required - R.num_received as outstanding
         FROM
         (SELECT species_name, count(vaccine_name) as num_required
          from vaccinationrequired
          where required = 1
          group by species_name) T join (select DISTINCT A.species_name, a.pet_id, count(distinct a.vaccine_name) as num_received
         from vaccinationtype A  join vaccinationrequired B on a.species_name = b.species_name and a.vaccine_name = b.vaccine_name
          where B.required = 1
          group by a.species_name, a.pet_id) R on T.species_name = R.species_name) AS D ON A.animalID = D.pet_id
      LEFT JOIN adoption E ON A.animalID = E.pet_id
      AND E.application_number IS NULL) R
      WHERE R.animalID = :id";

      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->setFetchMode(PDO::FETCH_CLASS, 'Animal');

      if ($stmt->execute()) {

          return $stmt->fetch();

      }
  }

  public static function getCapacity($conn)
  {
    $sql = "SELECT a.species_name, count(distinct a.pet_id) as inshelter, capacity
from breed a left join adoption b on a.pet_id = b.pet_id
join species c on a.species_name = c.species_name
where b.pet_id is null
group by a.species_name;";

    $results = $conn->query($sql);
    return $results->fetchAll(PDO::FETCH_ASSOC);

  }

  public static function getSpecies($conn)
  {
    $sql = "SELECT A.species_name, A.capacity, CASE WHEN D.num_adopted IS NULL THEN 0 ELSE D.num_adopted END as num_adopted, CASE WHEN E.num_animals IS NULL THEN 0 ELSE E.num_animals END AS num_animals
    FROM species A LEFT JOIN (SELECT B.species_name, count(B.pet_id) num_adopted
    FROM breed B INNER JOIN Adoption C ON B.pet_id = C.pet_id
    GROUP BY B.species_name) D
    ON A.species_name = D.species_name
    LEFT JOIN (SELECT DISTINCT species_name, count(DISTINCT pet_id) as num_animals FROM breed GROUP BY species_name) E
    ON A.species_name = E.species_name
    HAVING A.capacity-num_animals+num_adopted>0;";

    $results = $conn->query($sql);
    return $results->fetchAll(PDO::FETCH_ASSOC);

  }



/**
   * Insert a new adoption application with its current property values
   *
   * @param object $conn Connection to the database
   *
   * @return boolean True if the insert was successful, false otherwise
   */


public function insertAnimal($conn)
  {
      if ($this->validate()) {

          $sql = "INSERT INTO animal (name,sex,alteration_status,
          age_months,description,microchip_id, local_control, surrender_date, surrender_reason, user_name)
          VALUES (:name,:sex,:alteration_status,
          :age_months,:description,:microchip_id, :local_control, :surrender_date, :surrender_reason, :user_name )";

          $stmt = $conn->prepare($sql);

          $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
          $stmt->bindValue(':sex', $this->sex, PDO::PARAM_STR);
          $stmt->bindValue(':alteration_status', $this->alteration_status, PDO::PARAM_INT);
          $stmt->bindValue(':age_months', $this->age_months, PDO::PARAM_INT);
          $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
          $stmt->bindValue(':microchip_id', $this->microchip_id, PDO::PARAM_STR);
          $stmt->bindValue(':local_control', $this->local_control, PDO::PARAM_INT);
          $stmt->bindValue(':surrender_date', $this->surrender_date, PDO::PARAM_STR);
          $stmt->bindValue(':surrender_reason', $this->surrender_reason, PDO::PARAM_STR);
          $stmt->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);


          if ($stmt->execute()) {
               $this->id = $conn->lastInsertId();
               return true;
           }

       } else {
           return false;
       }
  }

  /**
       * Update the animal with its current property values
       *
       * @param object $conn Connection to the database
       *
       * @return boolean True if the update was successful, false otherwise
       */
      public function update($conn)
      {
             $sql = "UPDATE animal
                     SET sex = :sex,
                        alteration_status = :alteration_status,
                        microchip_id = :microchip_id
                     WHERE animalID = :id";

             $stmt = $conn->prepare($sql);

             $stmt->bindValue(':id', $this->animalID, PDO::PARAM_INT);
             $stmt->bindValue(':sex', $this->sex, PDO::PARAM_STR);
             $stmt->bindValue(':alteration_status', $this->alteration_status, PDO::PARAM_INT);
             $stmt->bindValue(':microchip_id', $this->microchip_id, PDO::PARAM_STR);


             return $stmt->execute();
         }

         /**
              * delete the animal's breed records
              *
              * @param object $conn Connection to the database
              *
              * @return boolean True if the update was successful, false otherwise
              */
       public function delete($conn)
       {
            $sql = "DELETE FROM breed
                    WHERE breed_name in ('Unknown', 'Mixed')
                    AND pet_id = :id";

           $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $this->animalID, PDO::PARAM_INT);

            return $stmt->execute();

        }


  /**
     * Validate the properties, putting any validation error messages in the $errors property
     *
     * @return boolean True if the current properties are valid, false otherwise
     */
    protected function validate()
    {
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
       }

       if ($this->name == '') {
           $this->errors[] = 'Name is required';
      }
        if ($this->sex == '') {
        $this->errors[] = 'Sex selection is required';
      }

      if ($this->description == '') {
        $this->errors[] = 'Description is required';
      }

        if ($this->surrender_date == '') {
        $this->errors[] = 'Surrender Date is required';
      }

      if ($this->surrender_reason == '') {
      $this->errors[] = 'Surrender Reason is required';
    }

    return empty($this->errors);
  }

/**Get the animal's breed
* @param object $conn Connection to the database
*
*
@return array the breed data
*/

public function getBreed($conn, $id)
{
  $sql = "SELECT breed_name, pet_id, species_name
          FROM Breed
          WHERE pet_id = :id";


  $stmt = $conn->prepare($sql);

  $stmt->bindValue(':id', $id, PDO::PARAM_INT);

  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);

}


public function setBreed($conn, $breeds)
{
      if ($breeds) {

         if(in_array('Unknown', $breeds)) {
            $sql = "INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
            VALUES ('Unknown',{$this->id}, :species_name);";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);

            $stmt->execute();

        } elseif (in_array('Mixed', $breeds)) {
            $sql = "INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
            VALUES ('Mixed',{$this->id}, :species_name);";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);

            $stmt->execute();

        } else {
            $sql = "INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
                    VALUES (:breed_name, {$this->id}, :species_name);";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);

            foreach ($breeds as $breed){
              $stmt->bindValue(':breed_name', $breed, PDO::PARAM_STR);
              $stmt->execute();

            }

        }

     }

  }

  public function updateBreed($conn, $breeds)
  {
        if ($breeds) {

           if(in_array('Unknown', $breeds)) {
              $sql = "INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
              VALUES ('Unknown',{$this->animalID}, :species_name);";

              $stmt = $conn->prepare($sql);

              $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);

              $stmt->execute();

          } elseif (in_array('Mixed', $breeds)) {
              $sql = "INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
              VALUES ('Mixed',{$this->animalID}, :species_name);";

              $stmt = $conn->prepare($sql);

              $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);

              $stmt->execute();

          } else {
              $sql = "INSERT IGNORE INTO breed (breed_name, pet_id, species_name)
                      VALUES (:breed_name, {$this->animalID}, :species_name);";

              $stmt = $conn->prepare($sql);
              $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);

              foreach ($breeds as $breed){
                $stmt->bindValue(':breed_name', $breed, PDO::PARAM_STR);
                $stmt->execute();

              }

          }

       }

    }


}
