<?php
  $filePath = $_GET["path"];
  
  $file = fopen($filePath, 'r');

  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
 
  if ($conn) { 
    mysql_select_db("colecoop", $conn);

    if ($file) {
      while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        mysql_query("INSERT INTO ag_counties (county, lat, lon) VALUES ('$data[0]', '$data[1]', '$data[2]')");
      }
      print "County data added successfully.";
    } else {
      print "Error opening file: $filePath";
    } 
  } else {
    print "Error connecting to database!";
  }
  mysql_close($conn);
  fclose($file);
?>
