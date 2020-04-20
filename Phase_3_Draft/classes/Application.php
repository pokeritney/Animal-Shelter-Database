<?php

/**
 * Application
 *
 * An adoption application
 */
class Application
{

  /**
   * Validation errors
   * @var array
   */

   public $errors = [];

   /**
        * Get all the applications
        *
        * @param object $conn Connection to the database
        *
        * @return array An associative array of all the article records
        */
       public static function getAll($conn)
       {
         $sql = "SELECT A.email, A.primary_first_name, A.primary_last_name, A.phone_number,
                 A.street, A.city, A.state, A.zip_code,
                 B.applicationID, B.application_date, B.co_first_name, B.co_last_name, B.status FROM
                 adopter A JOIN application B ON A.email = B.email
                 WHERE B.status='pending approval'";

           $results = $conn->query($sql);

           return $results->fetchAll(PDO::FETCH_ASSOC);
       }

       /**
            * Get all the approved applications based on search criteria
            *
            * @param object $conn Connection to the database
            *
            * @return array An associative array of all the applicant and co-applicant records
            */
           public static function search($conn, $lastname, $colastname)
           {
             $sql = "SELECT A.email, A.primary_first_name, A.primary_last_name, A.phone_number,
                     A.street, A.city, A.state, A.zip_code,
                     B.applicationID, B.application_date, B.co_first_name, B.co_last_name, B.status
                     FROM
                     adopter A JOIN application B ON A.email = B.email
                     WHERE B.status ='Approved' ";

                     if($lastname==="" and $colastname==="")
                     {

                     }elseif (!empty($lastname) and $colastname===""){
                        $sql .= "AND A.primary_last_name like :name";
                     }elseif ($lastname==="" and !empty($colastname)){
                        $sql .= "AND B.co_last_name like :coname";
                     }elseif (!empty($lastname) and !empty($colastname))
                     {
                       $sql .= "AND A.primary_last_name like :name
                                 AND B.co_last_name like :coname";
                     }else {

                     }



                    $stmt = $conn->prepare($sql);

                    if(!empty($lastname)){
                      $lastname = "%$lastname%";
                      $stmt->bindValue(':name', $lastname, PDO::PARAM_STR);
                    }

                    if (!empty($colastname)){
                      $colastname = "%$colastname%";
                      $stmt->bindValue(':coname', $colastname, PDO::PARAM_STR);
                    }


                    if ($stmt->execute()) {
                         $colastname='';
                         $lastname='';
                         return $stmt->fetchAll(PDO::FETCH_ASSOC);

                     }
          }


  /**
      * Get the application record based on the ID
      *
      * @param object $conn Connection to the database
      * @param integer $id the application ID
      * @param string $columns Optional list of columns for the select, defaults to *
      *
      * @return mixed An object of this class, or null if not found
      */
     public static function getByID($conn, $id, $columns = '*')
     {
         $sql = "SELECT A.email, A.primary_first_name, A.primary_last_name, A.phone_number,
                 A.street, A.city, A.state, A.zip_code,
                 B.applicationID, B.application_date, B.co_first_name, B.co_last_name, B.status FROM
                 adopter A JOIN application B ON A.email = B.email
                 WHERE B.applicationID = :id";

                 $stmt = $conn->prepare($sql);
                 $stmt->bindValue(':id', $id, PDO::PARAM_INT);

                 if ($stmt->execute()) {

                     return $stmt->fetch(PDO::FETCH_ASSOC);

                 }
      }

  /**
   * Insert a new adoption application with its current property values
   *
   * @param object $conn Connection to the database
   *
   * @return boolean True if the insert was successful, false otherwise
   */
  public function createadopter($conn)
  {
      if ($this->validate()) {

          $sql = "INSERT INTO adopter (primary_first_name,primary_last_name,email,
          phone_number,street,city,state, zip_code)
          VALUES (:primary_first_name,:primary_last_name,:email,
          :phone_number,:street,:city, :state, :zip_code)";

          $stmt = $conn->prepare($sql);

          $stmt->bindValue(':primary_first_name', $this->primary_first_name, PDO::PARAM_STR);
          $stmt->bindValue(':primary_last_name', $this->primary_last_name, PDO::PARAM_STR);
          $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
          $stmt->bindValue(':phone_number', $this->phone_number, PDO::PARAM_STR);
          $stmt->bindValue(':street', $this->street, PDO::PARAM_STR);
          $stmt->bindValue(':city', $this->city, PDO::PARAM_STR);
          $stmt->bindValue(':state', $this->state, PDO::PARAM_STR);
          $stmt->bindValue(':zip_code', $this->zip_code, PDO::PARAM_STR);


          if ($stmt->execute()) {
                 return true;
          }

      } else {
             return false;
      }
  }

  public function createapplication($conn)
  {
      if ($this->validate()) {

          $sql = "INSERT INTO application (application_date, co_first_name, co_last_name,
          email)
          VALUES (:application_date, :co_first_name, :co_last_name, :email)";

          $stmt = $conn->prepare($sql);

          $stmt->bindValue(':application_date', $this->application_date, PDO::PARAM_STR);
          $stmt->bindValue(':co_first_name', $this->co_first_name, PDO::PARAM_STR);
          $stmt->bindValue(':co_last_name', $this->co_last_name, PDO::PARAM_STR);
          $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

          if ($stmt->execute()) {
               $this->id = $conn->lastInsertId();
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
      if ($this->primary_first_name == '') {
          $this->errors[] = 'First name is required';
      }
      if ($this->primary_last_name == '') {
          $this->errors[] = 'Last Name is required';
      }

    if ($this->email == '') {
        $this->errors[] = 'Email is required';
    }

    if ($this->phone_number == '') {
      $this->errors[] = 'Phone Number is required';
    }

    if ($this->city == '') {
      $this->errors[] = 'City is required';
    }

    if ($this->state == '') {
      $this->errors[] = 'State is required';
    }

    if ($this->zip_code == '') {
      $this->errors[] = 'Zip Code is required';
    }

    if ($this->application_date == '') {
      $this->errors[] = 'Application Date is required';
    }

    return empty($this->errors);
}


public static function update($conn, $id, $status)
{
    $sql = "UPDATE application
            SET status = :status
            WHERE applicationID = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);

            $stmt->execute();
            return true;


 }





}
