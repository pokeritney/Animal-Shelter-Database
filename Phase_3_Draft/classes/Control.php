<?php

/**
 * Animal
 *
 * Animal in the Animal Shelter
 */

class Control
{

  /**
   * Validation errors
   * @var array
   */

  public $errors = [];


  /**
   * Get animal control surrendered for that month
   *
   *@param object $conn Connection to the Database
   *
   *@return scalar Count
   */
  public static function controlNumber($conn, $month, $year)
  {
    $sql = "SELECT count(animalID) AS animal_count
    FROM Animal
    WHERE YEAR(surrender_date) = :year
    AND MONTH(surrender_date) = :month
    AND local_control = TRUE
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
  }

  /**
   * Get animal adopted after 60 days in the rescue for that month
   *
   *@param object $conn Connection to the Database
   *
   *@return scalar Count
   */
  public static function rescueNumber($conn, $month, $year)
  {
    $sql = "SELECT COUNT(A.pet_id) as rescue_count
    FROM Adoption A JOIN Animal B
    ON A.pet_id = B.animalID
    WHERE MONTH(A.adoption_date) = :month AND YEAR(A.adoption_date) = :year
    AND DATEDIFF(A.adoption_date, B.surrender_date) >= 60
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
  }

  /**
   * Get animal surrendered by control for that month
   *
   *@param object $conn Connection to the Database
   *
   *@return array Animal information
   */
  public static function surrenderAnimals($conn, $month, $year)
  {
    $sql = "SELECT A.animalID, C.species_name, C.breed_name ,A.sex, A.alteration_status, A.microchip_id, A.surrender_date   
    FROM Animal A INNER JOIN 
    (SELECT pet_id, species_name, GROUP_CONCAT(DISTINCT breed_name ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
    FROM Breed
    GROUP BY pet_id, species_name) as C ON A.animalID = C.pet_id
    WHERE YEAR(A.surrender_date) = :year
    AND MONTH(A.surrender_date) = :month
    AND local_control = TRUE
    ORDER BY A.AnimalID
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);


    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Get animal adopted for that month who was in rescue after 60 days
   *
   *@param object $conn Connection to the Database
   *
   *@return array Animal information
   */
  public static function rescueAnimals($conn, $month, $year)
  {
    $sql = "SELECT A.animalID, B.species_name, B.breed_name ,A.sex, A.alteration_status, 
    A.microchip_id, A.surrender_date, DATEDIFF(C.adoption_date, A.surrender_date) AS length
    FROM Animal A INNER JOIN 
    (SELECT pet_id, species_name, GROUP_CONCAT(DISTINCT breed_name ORDER BY breed_name ASC SEPARATOR '/' ) AS breed_name
    FROM Breed
    GROUP BY pet_id, species_name) as B ON A.animalID = B.pet_id
    INNER JOIN Adoption AS C ON A.animalID = C.pet_id
    WHERE YEAR(C.adoption_date) = :year
    AND MONTH(C.adoption_date) = :month
    AND DATEDIFF(C.adoption_date, A.surrender_date) >= 60
    ORDER BY length DESC
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);


    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
