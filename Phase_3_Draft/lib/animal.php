<?php

/**
 * Get the animal record based on the ID
 *
 * @param object $conn Connection to the database
 * @param integer $id the animal ID
 * @param string $columns Optional list of columns for the select, defaults to *
 *
 * @return mixed An associative array containing the animal with that ID, or null if not found
 */

 function getAnimal($conn, $id, $columns = '*')
{
    $sql = "SELECT A.animalID, A.name, B.species_name, B.breed_name, A.sex, A.alteration_status,
    A.birth_date,timestampdiff(YEAR, A.birth_date, now()) as age_year,
    timestampdiff(MONTH, A.birth_date, now()) % 12 as age_month,
    A.description, A.local_control, A.microchip_id, A.surrender_date, A.surrender_reason,
    CASE WHEN A.alteration_status = TRUE AND D.num_required is NULL
    THEN 'Adoptable' ELSE 'Not Adoptable' END AS adoptability_status
    FROM Animal A INNER JOIN (SELECT pet_id,  species_name, GROUP_CONCAT(DISTINCT breed_name
            ORDER BY breed_name ASC SEPARATOR '/'
          ) AS breed_name
          FROM  Breed
    		GROUP BY pet_id, species_name

      ) as B ON A.animalID = B.pet_id
      LEFT JOIN
      (SELECT DISTINCT B.pet_id, COUNT(A.vaccine_name) num_required
    FROM vaccinationrequired A JOIN breed B ON A.species_name = B.species_name
    LEFT JOIN vaccination C ON A.vaccine_name = C.vaccine_name
    WHERE A.required = TRUE
    AND (C.administer_date IS NULL OR C.expiration_date < now())
    GROUP BY B.pet_id) AS D ON A.animalID = D.pet_id
    LEFT JOIN adoption E ON A.animalID = E.pet_id
    AND E.application_number IS NULL
    AND A.animalID = :id";


    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  }

  
