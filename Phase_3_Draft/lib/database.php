<?php

/**
* Get the database connection
*
* @return  object connection to the database server
*/

function getDB()
{
  $db_host = "localhost";
  $db_name = "cs6400_sp20_team001";
  $db_user = "gatechUser";
  $db_pass = "gatech123";

  $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

  if (mysqli_connect_error()) {
    echo mysqli_connect_error();
    exit;
  }

return $conn;

}

echo "Connected successfully.";
