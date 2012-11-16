<?php

//print_r($_FILES);
$filePath = $_FILES["inputFile"]["name"];
//print "File path: $filePath" . "<br>";
$fileType = substr($filePath, -3);
//print "Filetype: $fileType" . "<br>";
if ($fileType != "csv"){
  print "Error opening file: $filePath is wrong file type. Please verify that the file you are attempting to import is of a .csv file.";
} else {
  print "Correct file type." . "<br>";

  // Connect to the database
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  if ($conn) {
    mysql_select_db("colecoop", $conn);

    $file = fopen($filePath, 'r');
    if ($file) {
      while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
          echo "Year: " . $data[0] . " County: " . $data[1] . " MID: " . $data[2] . " Value: " . $data[3] . " Measurement: " . $data[4];
//        mysql_query("INSERT INTO ag_counties (year, countyName, mid, value, measurement) VALUES ('$data[0]', '$data[1]', '$data[2]'), '$data[3]'), '$data[4]')");
      }
//    print "County data added successfully." . "<br>";
    } else {
      print "Error opening file: $filePath" . "<br>";
    } 
  } else {
    print "Error connecting to database." . "<br>";
  }
  mysql_close($conn);
  fclose($file);
}
print '<br><a href="./index.php">Cancel</a>';
?>