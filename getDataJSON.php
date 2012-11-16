<?php
  $startRecord = $_GET["start"];  // The record to start on
  $numRecords = $_GET["num"];     // The number of records to retrieve
  $commodity = $_GET["comm"];     // The type of commodity
  $county = $_GET["county"];      // The name of the county
  $sort = $_GET["sort"];          // The sorting order (high to low or high to low)
  $measure = $_GET["measure"];    // The measurement type of the commodity
  $year = $_GET["year"];          // The year information is taken from

  // Connect to the database
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  
  // If we have a good connection, proceed
  if($conn) {
    // Select the database and run the query
    mysql_select_db("colecoop", $conn);

    if ($commodity || $county || $sort || $measure || $year) {
      $whereClause = " WHERE agcom_commodities.CountyName=agcom_counties.CountyName AND agcom_commodities.mid=agcom_measurement.mid AND";
      if ($commodity) {
        $whereClause .= " type='" . $commodity . "'";
        if ($county || $measure || $year) {
          $whereClause .= " AND";
        }
      }
      if ($county) {
        $whereClause .= " CountyName='" . $county . "'";
        if ($measure || $year) {
          $whereClause .= " AND";
        }
      }
      if ($measure) {
        $whereClause .= " measurement='" . $measure . "'";
        if ($year) {
          $whereClause .= " AND";
        }
      }
      if ($year) {
        $whereClause .= " year='" . $year . "'";
      }
    }

    if ($sort) {
      $sortClause = "ORDER BY agcom_commodities.value";
      if ($sort == "HighToLow") {
        $sortClause .= " DESC";
      }
      else {
        $sortClause .= " ASC";
      }
    }
    //print "SELECT * FROM agcom_commodities JOIN agcom_counties JOIN agcom_measurement" . $whereClause . $sortClause . " LIMIT " . $startRecord . "," . $numRecords;

    $result = mysql_query("SELECT * FROM agcom_commodities JOIN agcom_counties JOIN agcom_measurement" . $whereClause . $sortClause . " LIMIT " . $startRecord . "," . $numRecords);
    
    // Pull the entire result into a single assosciative array
    while ($array[] = mysql_fetch_array($result, MYSQL_ASSOC));
    
    //print_r ($array);
    
    // Print out the table in the DataTable JSON format
    // NOTE: To be valid JSON, all fields must be encapsulated in double quotes ""
    // First the columns 
    $i = 0;
    print "{\n\t\"cols\": [";
    print "{\"id\": \"LATITUDE\" , \"label\": \"Latitude\" , \"type\": \"number\"},\n\t\t";
    print "{\"id\": \"LONGITUDE\", \"label\": \"Longitude\", \"type\": \"number\"},\n\t\t";
    print "{\"id\": \"COUNTY\", \"label\": \"County\", \"type\": \"string\"},\n\t\t";
    print "{\"id\": \"VALUE\", \"label\": \"" . $measure  . "\", \"type\": \"number\"}\n\t\t";
    print "],\n\t\"rows\": [";

    // Now print the rows
    $i = 0;
    foreach ($array as $row) {
      print "{\"c\":[";
      // Latitude
      print "{\"v\": \"" . $row["latitude"] . "\"},";
      // Logitude 
      print "{\"v\": \"" . $row["longitude"] . "\"},";
      // County Name
      print "{\"v\": \"" . $row["CountyName"] . "\"},";
      // Value
      print "{\"v\": \"" . $row["value"] . "\"}";

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
