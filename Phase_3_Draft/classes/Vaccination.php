<?php
/**
 * Vaccination
 *
 * Vaccination History for an animal
 */
class Vaccination
{
    /**
     * Get all the History
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the vaccination records
     */
    public static function getAllVaccinations($conn)
    {
        $sql = "SELECT A.pet_id, A.vaccine_name, A.vaccination_number, A.user_name, A.administer_date, A.expiration_date, C.required
        FROM vaccination A
        JOIN vaccinationtype B ON A.pet_id = B.pet_id AND A.vaccine_name = B.vaccine_name AND A.administer_date = B.administer_date
        JOIN vaccinationrequired C ON B.species_name = C.species_name AND B.vaccine_name = C.vaccine_name;";
        $results = $conn->query($sql);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get the vaccination records based on the ID
     *
     * @param object $conn Connection to the database
     * @param integer $id the Animal ID
     * @param string $columns Optional list of columns for the select, defaults to *
     *
     * @return mixed An associative array containing the article with that ID, or null if not found
     */
    public static function getByID($conn, $id)
    {
      $sql = "SELECT A.pet_id, A.vaccine_name, A.vaccination_number, A.user_name, A.administer_date, A.expiration_date, C.required
      FROM vaccination A
      JOIN vaccinationtype B ON A.pet_id = B.pet_id AND A.vaccine_name = B.vaccine_name AND A.administer_date = B.administer_date
      JOIN vaccinationrequired C ON B.species_name = C.species_name AND B.vaccine_name = C.vaccine_name
      WHERE A.pet_id= :id";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->setFetchMode(PDO::FETCH_CLASS, 'Vaccination');
      if ($stmt->execute()) {
          return $stmt->fetchall();
      }
    }
    /**
        * Get the breed based on the species
        *
        * @param object $conn Connection to the database
        * @param string $species the species_name
        * @param string $columns Optional list of columns for the select, defaults to *
        *
        * @return mixed An object of this class, or null if not found
        */
       public static function getAll($conn, $species, $columns = '*')
       {
         $sql = "SELECT species_name, vaccine_name, required
                 FROM vaccinationrequired
                 WHERE species_name = :species
                 ORDER BY species_name, vaccine_name
                 ";
                   $stmt = $conn->prepare($sql);
                   $stmt->bindValue(':species', $species, PDO::PARAM_STR);
                   if ($stmt->execute()) {
                       return $stmt->fetchAll(PDO::FETCH_ASSOC);
                   }
        }
  public function insertVaccination($conn)
  {
              if ($this->validate()) {
                  $sql = "INSERT INTO vaccination (vaccine_name,vaccination_number,pet_id,
                  administer_date, expiration_date, user_name)
                  VALUES (:vaccine_name,:vaccination_number,:pet_id,
                  :administer_date,:expiration_date,:user_name )";
                  $stmt = $conn->prepare($sql);
                  $stmt->bindValue(':vaccine_name', $this->vaccine_name, PDO::PARAM_STR);
                  $stmt->bindValue(':vaccination_number', $this->vaccination_number, PDO::PARAM_STR);
                  $stmt->bindValue(':pet_id', $this->pet_id, PDO::PARAM_INT);
                  $stmt->bindValue(':administer_date', $this->administer_date, PDO::PARAM_STR);
                  $stmt->bindValue(':expiration_date', $this->expiration_date, PDO::PARAM_STR);
                  $stmt->bindValue(':user_name', $this->user_name, PDO::PARAM_STR);
                  if ($stmt->execute()) {
                       return true;
                   }
               } else {
                   return false;
               }
    }
    public function insertVacctype($conn)
    {
        $sql = "INSERT INTO vaccinationtype (vaccine_name, species_name, pet_id,
        administer_date)
        VALUES (:vaccine_name, :species_name, :pet_id, :administer_date)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':vaccine_name', $this->vaccine_name, PDO::PARAM_STR);
        $stmt->bindValue(':species_name', $this->species_name, PDO::PARAM_STR);
        $stmt->bindValue(':pet_id', $this->pet_id, PDO::PARAM_INT);
        $stmt->bindValue(':administer_date', $this->administer_date, PDO::PARAM_STR);
        if ($stmt->execute()) {
             return true;
         }
    }

  /**
  * Validate the properties, putting any validation error messages in the $errors property
  *
  * @return boolean True if the current properties are valid, false otherwise
  */
  protected function validate()
  {
    if ($this->vaccine_name =='') {
         $this->errors[] = 'Vaccine is required';
    }
    if ($this->administer_date == '') {
        $this->errors[] = 'Administer date is required';
    }
    if (isVolunteer() and $this->administer_date <> '' and date('Y-m-d') > date('Y-m-d', strtotime($this->administer_date))) {
        $this->errors[] = 'Volunteers can not enter vaccination history. Administer date must be on or after Today.';
    }
    if ($this->expiration_date == '') {
        $this->errors[] = 'Expiration Date is required';
    }
    if (($this->administer_date <>'' and $this->expiration_date<> '')
        and $this->expiration_date < $this->administer_date) {
       $this->errors[] = 'Expiration Date must be after Administer Date';
    }
    return empty($this->errors);
  }
  /**
     * Get vaccine reminder report
     *
     *@param object $conn Connection to the Database
     *
     *@return array An associative array of all vaccinations due
     */
    public static function vaccineReminder($conn)
    {
        $sql = "SELECT V.vaccine_name, date(V.expiration_date) AS due_date, V.pet_id AS id, C.species_name, C.breed_name, A.sex, A.alteration_status, A.microchip_id, A.surrender_date, U.first_name, U.last_name
        FROM vaccination V JOIN `user` U ON V.user_name = U.user_name JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT(DISTINCT breed_name ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
        FROM Breed
        GROUP BY pet_id, species_name) as C ON V.pet_id = C.pet_id
        JOIN animal A on A.animalID = V.pet_id
        WHERE YEAR(V.expiration_date) = YEAR(now())
        AND (MONTH(V.expiration_date) - MONTH(now())) >= 0
        AND (MONTH(V.expiration_date) - MONTH(now())) <= 3
        AND V.pet_id NOT IN (SELECT pet_id FROM adoption)
        ORDER BY due_date, id
    ";
        $results = $conn->query($sql);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    public function datevalidate($conn, $pet_id, $vaccine_name, $administer_date)
      {
        $sql = "SELECT count(*) AS num
                FROM vaccination
                WHERE vaccine_name = :vaccine_name
                AND pet_id = :pet_id
                AND expiration_date > :administer_date";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':vaccine_name', $vaccine_name, PDO::PARAM_STR);
        $stmt->bindValue(':pet_id', $pet_id, PDO::PARAM_INT);
        $stmt->bindValue(':administer_date', $administer_date, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
     }
}
