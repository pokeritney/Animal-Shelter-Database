<?php

/**
  *Breed
  *
**/

class Breed
{

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
       $sql = "SELECT species_name, breed_name
               FROM `breedname`
               WHERE species_name = :species
               ORDER BY species_name, breed_name
               ";


                 $stmt = $conn->prepare($sql);
                 $stmt->bindValue(':species', $species, PDO::PARAM_STR);

                 if ($stmt->execute()) {

                     return $stmt->fetchAll(PDO::FETCH_ASSOC);

                 }
      }






}
