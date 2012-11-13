<?php
  $tableName = $_GET["table"];    // The name of the table to SELECT from
  $startRecord = $_GET["start"];  // The record to start on
  $numRecords = $_GET["num"];     // The number of records to retrieve

  // Connect to the database
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  
  // If we have a good connection, proceed
  if($conn) {
    // Select the database and run the query
    mysql_select_db("colecoop", $conn);
    $result = mysql_query("SELECT * FROM " . $tableName . " LIMIT " . $startRecord . "," . $numRecords);
    
    // Pull the entire result into a single assosciative array
    while ($array[] = mysql_fetch_array($result, MYSQL_ASSOC));

    // Print out the table in the DataTable JSON format
    // NOTE: To be valid JSON, all fields must be encapsulated in double quotes ""
    // First the columns 
    $i = 0;
    print "{\n\t\"cols\": [";
    $keys = array_keys($array[0]); // Get the keys of the first record
    foreach ($keys as $key) {
      print "{\"id\": \"" . $key . "\", \"label\": \"" . $key . "\", \"type\": \"";
      $type = mysql_field_type($result, $i);
      if ($type == "int" || $type == "real" || $type == "decimal") {
        print "number";
      } else {
        print "string";
      }
      print "\"}";
      if ($key !== end($keys)) print ", ";
      print "\n\t\t";
      $i++;
    }
    print "],\n\t\"rows\": [";
    // Now print the rows
    $i = 0;
    foreach ($array as $row) {
      print "{\"c\":[";
      foreach ($row as $col) {
        print "{\"v\": \"" . $col . "\"}";
        if ($col !== end($row)) print ", ";
      }
      print "]}"; 
      if ($i == ($numRecords - 1 )) break;
      if ($row !== end($array)) print ", ";
      print "\n\t\t   ";
      $i++;
    }
    print "\n\t\t]\n}";
    } else {

    }

  mysql_close($conn);
?>
