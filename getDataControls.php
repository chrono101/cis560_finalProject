<?php
  $commodity = $_GET["comm"];     // The type of commodity
  $control = $_GET["control"];
  
  // Connect to the database
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  
  // If we have a good connection, proceed
  if($conn) {
    // Select the database and run the query
    mysql_select_db("colecoop", $conn);
    
    if ($control == 'measurement') {
      // Get the measurments for the given commodity
      $result = mysql_query("SELECT * FROM agcom_get_m WHERE type='" . $commodity . "' GROUP BY measurement");
      while ($array[] = mysql_fetch_array($result, MYSQL_ASSOC));
      foreach ($array as $row) {
         print "<option value=\"" . $row["measurement"] . "\">" . $row["measurement"] . "</option>";
      }

    } else if ($control == 'year') {
      // Get the years for the given commodity
      $result = mysql_query("SELECT * FROM agcom_get_y WHERE type='" . $commodity . "' GROUP BY year");
      while ($array[] = mysql_fetch_array($result, MYSQL_ASSOC));
      foreach($array as $row) {
        print "<option value=\"" . $row["year"] . "\">" . $row["year"] . "</option>";
      }

    } else {
      // Do nothing
    }
  }
  mysql_close($conn);
?>
