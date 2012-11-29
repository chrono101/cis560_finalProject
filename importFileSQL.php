<?php

$filePath = $_FILES["inputFile"]["tmp_name"];

// Check to see if the file is of the right type
if ($_FILES["inputFile"]["type"] != "application/vnd.ms-excel" && $_FILES["inputFile"]["type"] != "text/csv") {
  print "Error: $filePath is an invalid file type. File import may not work properly.<br>";
}
else {
  print "Correct file type.<br>";
} 
// Connect to the database
$mysqli = new mysqli("mysql.cis.ksu.edu", "colecoop", "insecurepassword", "colecoop");
$mysqli->autocommit(FALSE); // Turn off autocommits
// Open the file or exit if the file cannot be opened
$file = fopen(($filePath), "r") or exit ("Can't open file.");
// Read the file and insert the data into the mysql database
while (($data = fgetcsv($file, 1000, ",")) != FALSE) {
  echo "Year: " . $data[0] . " County: " . $data[1] . " Value: " . $data[2]. " Measurement: " . $data[3] . "<br>";

  if ($result = $mysqli->query("SELECT mid FROM agcom_measurement WHERE measurement = '$data[3]'"))  {
    $row = $result->fetch_row();
    $mid = $row[0];
  } else {
    $query_ok = TRUE; 
    $mysqli->query("INSERT INTO agcom_measurement (measurement) VALUES ('$data[3]')") ? NULL : $query_ok = FALSE;
    if ($query_ok) {
      $mysqli->commit();
    } else {
      $mysqli->rollback();
      print "Unable to add measurement to the database.<br>";
      continue;
    }

    $result = $mysqli->query("SELECT mid FROM agcom_measurement WHERE measurement = '$data[3]'");
    $row = $result->fetch_row();
    $mid = $row[0];
  }
  $query_ok = TRUE;
  $mysqli->query("INSERT INTO agcom_commodities (year, CountyName, type, mid, value) VALUES('$data[0]', '$data[1]', '$data[2]', $mid, '$data[4]')") ? NULL : $query_ok = FALSE;
  if ($query_ok) {
    $mysqli->commit();
  } else {
    $mysqli->rollback();
    print "Unable to add row to the database.<br>";
    continue;
  }
  print "County data added successfully.<br>";
}

// Close the file and close the connection to the database
$mysqli->close();
fclose($file);

print '<br><a href="./index.php">Home</a>';
?>
