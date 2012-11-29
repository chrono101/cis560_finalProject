<?php
  // Connect to the database
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  if ($conn) {
    mysql_select_db("colecoop", $conn);
    $query = "SELECT type FROM agcom_commodities GROUP BY type";
    $result = mysql_query($query);
    while ($commoditiesArray[] = mysql_fetch_array($result, MYSQL_ASSOC));
  }
  mysql_close($conn);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Kansas Counties Agricultural Output</title>

<link rel="stylesheet" type="text/css" href="style.css">

<script type="text/javascript" src="http://www.google.com/jsapi"></script> 
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="script.js"></script>
</head>
<body>
<div id="container">
  <div class="border-big">
    <h1>Kansas Counties Agricultural Output</h1>
  </div>

  <div id="geochart" class="border-big">
  </div>
  
  <div class="border-big">
    Search Database:
    <div class="border-small">
      <form id="searchForm" action="/" method="post">
        Select a Commodity:
        <select id="selectCommodity">
        <?php
          foreach($commoditiesArray as $row) {
            print "<option value=\"" . $row["type"] . "\">" . $row["type"] . "</option>";
          }
        ?>
        </select> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        In Year:
        <select id="selectYear">
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Where:
        <select id="selectMeasure">
        </select>  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Is:
        <select id="selectOperator">
          <option value="eq">equal to</option>
          <option value="lt">less than</option>
          <option value="gt">greater than</option>
        </select>
        Value:
        <input id="inputValue" type="number" value="" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="searchFormSubmit" type="submit" value="Submit" />
      </form>
    </div>
  </div>

  <div id="tablechart" class="border-big">
  </div>
  
  <div class="border-big">
    <a href="addRecord.php">Add Record</a>
  </div>
</div> <!-- /#container -->
</body>
</html>
