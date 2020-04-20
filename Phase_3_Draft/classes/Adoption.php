<?php

/**
 * Adoption
 *
 * An adoption
 */
class Adoption
{

  /**
   * Validation errors
   * @var array
   */

   public $errors = [];

   public function addAdoption($conn)
   {
       if ($this->validate()) {

           $sql = "INSERT INTO adoption (adoption_date, adoption_fee, application_number,
           pet_id)
           VALUES (:adoption_date, :adoption_fee, :application_number, :pet_id)";

           $stmt = $conn->prepare($sql);

           $stmt->bindValue(':adoption_date', $this->adoption_date, PDO::PARAM_STR);
           $stmt->bindValue(':adoption_fee', $this->adoption_fee, PDO::PARAM_STR);
           $stmt->bindValue(':application_number', $this->application_number, PDO::PARAM_INT);
           $stmt->bindValue(':pet_id', $this->pet_id, PDO::PARAM_INT);

           if ($stmt->execute()) {

                return true;
            }

        } else {
            return false;
        }
   }


   /**
    * Validate the properties, putting any validation error messages in the $errors property
    *
    * @return boolean True if the current properties are valid, false otherwise
    */
   protected function validate()
   {
       if ($this->adoption_date == '') {
           $this->errors[] = 'Application Date is required';
       }
       if ($this->adoption_fee == '') {
           $this->errors[] = 'Adoption Fee is required';
       }


     return empty($this->errors);
  }

   /**
     * Get monthly adoption report
     *
     *@param object $conn Connection to the Database
     *
     *@return array An associative array of number of animals surrendered and adopted each month by species and breed
     */
    public static function monthlyAdoption($conn)
    {
        $sql = "SELECT R.y, R.m, R.month_name, R.species_name, R.breed_name, COALESCE(R.surrender_count, 0) AS surrender_count, COALESCE(R.adoption_count, 0) AS adoption_count
        FROM
        
        (
        (SELECT SC.y, SC.m, SC.month_name, SC.species_name, SC.breed_name, SC.surrender_count, AC.adoption_count
        FROM
        (SELECT YEAR(A.surrender_date) AS y, MONTH(A.surrender_date) as m, MONTHNAME(A.surrender_date) AS month_name, B.species_name, B.breed_name, COUNT(A.animalID) AS surrender_count
        FROM animal A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.animalID = B.pet_id
        WHERE A.surrender_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.surrender_date), MONTH(A.surrender_date), MONTHNAME(A.surrender_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name) AS SC
        
        LEFT JOIN
        
        (SELECT YEAR(A.adoption_date) AS y, MONTH(A.adoption_date) AS m, MONTHNAME(A.adoption_date) AS month_name, B.species_name, B.breed_name, COUNT(A.pet_id) AS adoption_count
        FROM adoption A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.pet_id = B.pet_id
        WHERE A.adoption_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.adoption_date), MONTH(A.adoption_date), MONTHNAME(A.adoption_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name
        ) AS AC
        
        ON (SC.y = AC.y AND SC.m = AC.m AND SC.species_name = AC.species_name AND SC.breed_name = AC.breed_name)
        )
        UNION 
        (
        SELECT AC.y, AC.m, AC.month_name, AC.species_name, AC.breed_name, SC.surrender_count, AC.adoption_count
        FROM
        (SELECT YEAR(A.surrender_date) AS y, MONTH(A.surrender_date) as m, MONTHNAME(A.surrender_date) AS month_name, B.species_name, B.breed_name, COUNT(A.animalID) AS surrender_count
        FROM animal A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.animalID = B.pet_id
        WHERE A.surrender_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.surrender_date), MONTH(A.surrender_date), MONTHNAME(A.surrender_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name) AS SC
        
        RIGHT JOIN
        
        (SELECT YEAR(A.adoption_date) AS y, MONTH(A.adoption_date) AS m, MONTHNAME(A.adoption_date) AS month_name, B.species_name, B.breed_name, COUNT(A.pet_id) AS adoption_count
        FROM adoption A JOIN
        (SELECT pet_id, species_name, GROUP_CONCAT( DISTINCT breed_name 
           ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
           FROM Breed
          GROUP BY pet_id, species_name) as B ON A.pet_id = B.pet_id
        WHERE A.adoption_date >= (DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 12 MONTH)
        GROUP BY YEAR(A.adoption_date), MONTH(A.adoption_date), MONTHNAME(A.adoption_date), B.species_name, B.breed_name
        ORDER BY y, m, species_name, breed_name
        ) AS AC
        
        ON (SC.y = AC.y AND SC.m = AC.m AND SC.species_name = AC.species_name AND SC.breed_name = AC.breed_name)
        )
        ) AS R
        ORDER BY R.y, R.m, R.species_name, R.breed_name;
    ";

        $results = $conn->query($sql);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

}
