<?php
  // Connect to the database
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  
  if ($conn) {
    mysql_select_db("colecoop", $conn);
    
    $year = $_POST["year"];
    $commodity = $_POST["commodity"];
    $county = $_POST["county"];
    $measurement = $_POST["measurement"];
    $value = $_POST["value"];
    
    mysql_query("INSERT INTO agcom_measurement (measurement) VALUES ('$measurement')");
    print mysql_error() . "<br>";
    $midResult = mysql_query("SELECT mid FROM agcom_measurement WHERE measurement='$measurement'");
    $array = mysql_fetch_array($midResult, MYSQL_ASSOC);
    $mid = $array["mid"];

    mysql_query("INSERT INTO agcom_commodities (year, CountyName, type, mid, value) VALUES ($year, '$county', '$commodity', $mid, $value)"); 
    print mysql_error() . "<br>";


    print "Query submitted <a href=\"index.php\">Go Back</a>";

    mysql_close($conn);
  }
?>
