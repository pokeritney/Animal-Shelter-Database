<?php

/**
 * Animal
 *
 * Animal in the Animal Shelter
 */

class Volunteer
{

  /**
   * Validation errors
   * @var array
   */

  public $errors = [];


  /**
   * Get volunteer work month and year combination
   *
   *@param object $conn Connection to the Database
   *
   *@return array An associative array of month and year combination
   */
  public static function monthYear($conn)
  {
    $sql = "SELECT comb
    FROM
    (SELECT YEAR(work_date) as y, MONTH(work_date) as m, DATE_FORMAT(work_date, '%m-%Y') as comb
    FROM daywork
    GROUP BY YEAR(work_date), MONTH(work_date), DATE_FORMAT(work_date, '%m-%Y')
    ORDER BY y, m) AS myc;
    ";

    $results = $conn->query($sql);
    return $results->fetchAll(PDO::FETCH_ASSOC);
  }


  /**
   * Get top Volunteers
   *
   *@param object $conn Connection to the Database
   *
   *@return array An associative array of all the top volunteers
   */
  public static function topVolunteer($conn, $year, $month)
  {
    $sql = "SELECT a.first_name, a.last_name, a.email, b.total
    FROM `user` a JOIN
    (SELECT user_name, SUM(hours_worked) AS total
    FROM daywork
    WHERE YEAR(work_date) = :year and MONTH(work_date) = :month
    GROUP BY user_name
    ORDER BY total DESC
    LIMIT 5) b
    ON a.user_name = b.user_name
    ORDER BY b.total DESC, a.last_name
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);


    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }



  /**
   * Get volunteer information
   *
   *@param object $conn Connection to the Database
   *
   *@return array An associative array of all volunteers with input keyword
   */
  public static function searchVolunteer($conn, $last_name, $first_name)
  {
    $sql = "SELECT a.last_name, a.first_name, a.email, b.volunteer_phone_num
    FROM `User` a INNER JOIN Volunteer b
    ON a.user_name = b.user_name
    WHERE a.last_name LIKE CONCAT('%', :last_name, '%')
    AND a.first_name LIKE CONCAT('%', :first_name, '%')
    ORDER BY a.last_name, a.first_name
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);


    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
