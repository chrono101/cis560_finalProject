<?php
  $startRecord = $_GET["start"];  // The record to start on
  $numRecords = $_GET["num"];     // The number of records to retrieve
  $commodity = $_GET["comm"];     // The type of commodity
  $county = $_GET["county"];      // The name of the county
  $sort = $_GET["sort"];          // The sorting order (high to low or high to low)
  $measure = $_GET["measure"];    // The measurement type of the commodity
  $year = $_GET["year"];          // The year information is taken from
  $operator = $_GET["op"];        // The operator (less than, greater than, etc.)
  $value = $_GET["val"];          // The value for the operator

  // Connect to the database and the memcached service
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  $memcache = new Memcached();
  $memcache->addServer("127.0.0.1", 11211);
  
  // If we have a good connection, proceed
  if($conn) {
    // Select the database and run the query
    mysql_select_db("colecoop", $conn);

    if ($commodity || $county || $value || $measure || $year) {
      $whereClause = " WHERE agcom_commodities.CountyName=agcom_counties.CountyName AND agcom_commodities.mid=agcom_measurement.mid AND";
      if ($commodity) {
        $whereClause .= " type='" . $commodity . "'";
        if ($county || $value || $measure || $year) {
          $whereClause .= " AND";
        }
      }
      if ($value) {
        switch ($operator) {
          case "eq":
            $opLiteral = "=";
            break;
          case "lt":
            $opLiteral = "<";
            break;
          case "gt":
            $opLiteral = ">";
            break;
          default:
            $opLiteral = "=";
        }
        $whereClause .= " value" . $opLiteral . $value;
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

    // Generate the query and its memcache key
    $query = "SELECT * FROM agcom_commodities JOIN agcom_counties JOIN agcom_measurement" . $whereClause . $sortClause . " LIMIT " . $startRecord . "," . $numRecords;
    $queryKey = "KEY" . md5($query);
    // See if this is already in memcache
    $result = $memcache->get($queryKey);
    // If not, get it from the database
    if (!$result) {    
      $result = mysql_query($query);

      // Pull the entire result into a single assosciative array
      while ($array[] = mysql_fetch_array($result, MYSQL_ASSOC));

      // Print out the table in the DataTable JSON format
      // NOTE: To be valid JSON, all fields must be encapsulated in double quotes ""
      // First the columns 
      $i = 0;
      $json = "{\n\t\"cols\": [";
      $json .= "{\"id\": \"YEAR\", \"label\": \"Year\", \"type\": \"string\"},\n\t\t";
      $json .= "{\"id\": \"COUNTY\", \"label\": \"County\", \"type\": \"string\"},\n\t\t"; 
      $json .= "{\"id\": \"COMMODITY\", \"label\": \"Commodity\", \"type\": \"string\"},\n\t\t";
      $json .= "{\"id\": \"VALUE\", \"label\": \"" . $measure  . "\", \"type\": \"number\"}\n\t\t";
      $json .= "],\n\t\"rows\": [";

      // Now print the rows
      $i = 0;
      foreach ($array as $row) {
        $json .= "{\"c\":[";
        // Year
        $json .= "{\"v\": \"" . $row["year"] . "\"},";
        // County Name
        $json .= "{\"v\": \"" . $row["CountyName"] . "\"},";
        // Commodity Type
        $json .= "{\"v\": \"" . $row["type"] . "\"},";
        // Value
        $json .= "{\"v\": \"" . $row["value"] . "\"}";

        $json .= "]}"; 
        if ($i == ($numRecords - 1 )) break;
        if ($row !== end($array)) $json .= ", ";
        $json .= "\n\t\t   ";
        $i++;
      }
      $json .= "\n\t\t]\n}";
      // Put the JSON string into memcache, and then print it out
      $memcache->set($queryKey, $json);
      print $json;
    } else {
      // Otherwise, this exists in memcache, so just print it out
      print $result;
    }

  mysql_close($conn);
}
?>
