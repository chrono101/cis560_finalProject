<?php
// Set the max execution time of this script to 300 seconds, or 5 minutes
ini_set('max_execution_time', 600); 

$filePath = $_FILES["inputFile"]["tmp_name"];
$log = "";

// Check to see if a file was uploaded and the file is of the right type
if ($_FILES["inputFile"]["name"] == "") {
  $log .= "Error: No file was selected.<br>";
} else if ($_FILES["inputFile"]["type"] != "application/vnd.ms-excel" && $_FILES["inputFile"]["type"] != "text/csv") {
  $log .= "Warning: File '" . $_FILES["inputFile"]["name"] . "' has an invalid file type of '". $_FILES["inputFile"]["type"] . "'. File import may not work properly.<br>";
} else {
  $log .= "File type is ok.<br>";
} 
// Open the file or exit if the file cannot be opened
if ($_FILES["inputFile"]["name"] == "") {
  // Do nothing
} else if ($file = fopen(($filePath), "r")) {
  // Connect to the database
  $mysqli = new mysqli("mysql.cis.ksu.edu", "colecoop", "insecurepassword", "colecoop");
  $mysqli->autocommit(FALSE); // Turn off autocommits

  // Get the column positions from the first line and store them in an array
  $headerData = fgetcsv($file, 1000, ",", '"');
  // Check to see if the file has headers. NOTE: USDA spells "Program" as "Progam"
  if ($headerData[0] == "Progam") {
    // Save the column poisitions
    $colPos = array_flip($headerData);

    // Start reading the file and insert the data into the database
    while (($data = fgetcsv($file, 1000, ",", '"')) != FALSE) {

      // Pull out the values we want and make sure all words look like "This" and not "THIS" or "this"  
      $year = ucfirst(strtolower($data[$colPos["Year"]]));
      $county = ucfirst(strtolower($data[$colPos["County"]]));
      // We need to split the "Data Item" column into type and measurment 
      $hyphenPos = strripos($data[$colPos["Data Item"]], "-");
      $type = substr($data[$colPos["Data Item"]], 0, $hyphenPos);
      $measurement = substr($data[$colPos["Data Item"]], $hyphenPos+2);
      // Now make them Proper case
      $type = ucfirst(strtolower($type));
      $measurement = ucfirst(strtolower($measurement));
      $value = str_replace(",", "", $data[$colPos["Value"]]);

      
      $log .= "Adding row with data Year: " . $year . " County: " . $county . " Value: " . $value. " Measurement: " . $measurement . "<br>";

      // Lookup the MID for the input measurement. If it doesn't exist, add a new measurement to 
      // the database and get its MID
      $result = $mysqli->query("SELECT mid FROM agcom_measurement WHERE measurement = '$measurement'");
      if ($row = $result->fetch_assoc())  {
        $mid = $row["mid"];
        $log .= "Measurement '$data[3]' with MID '$mid' pulled from database.<br>";
      } else {
        $query_ok = TRUE; 
        $mysqli->query("INSERT INTO agcom_measurement (measurement) VALUES ('$measurement')") ? NULL : $query_ok = FALSE;
        if ($query_ok) {
          $log .= "Measurement '$measurement' added to database.<br>";
          $mysqli->commit();
        } else {
          $mysqli->rollback();
          $log .= "Unable to add measurement '$measurement' to the database.<br>";
          continue;
        }
        $result = $mysqli->query("SELECT mid FROM agcom_measurement WHERE measurement = '$measurement'");
        $row = $result->fetch_row();
        $mid = $row[0];
      }
      // Now insert the values for the commodity into the database
      $query_ok = TRUE;
      $mysqli->query("INSERT INTO agcom_commodities (year, CountyName, type, mid, value) VALUES('$year', '$county', '$type', $mid, '$value')") ? NULL : $query_ok = FALSE;
      if ($query_ok) {
        $mysqli->commit();
      } else {
        $mysqli->rollback();
        $log .= "Unable to add row to the database.<br>";
        continue;
      }
      $log .= "County data added successfully.<br>";
    }
  } else {
    $log .= "Error: File contains no header information.<br>";
  }
  // Close the file and close the connection to the database
  $mysqli->close();
  fclose($file);
} else {
  $log .= "Error: Unable to open the file.<br>";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Import File - Kansas Counties Agricultural Output</title>

  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
  <div id="header">
    <a href="index.php">Kansas Counties Agricultural Output</a>
  </div>
  
  <a href="addRecord.php">(( Add Another Record ))</a>&nbsp;&nbsp;
  <a href="index.php">(( Home ))</a><br>
  <div class="controls">
    <br><br>
  </div>
  <?php
    print $log;
  ?>
  <div class="controls">
    <br><br>
  </div>
</div>
</body>
