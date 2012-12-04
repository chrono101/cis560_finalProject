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
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">

<script type="text/javascript" src="http://www.google.com/jsapi"></script> 
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript" src="script.js"></script>
</head>
<body>
<div id="container">
  <div id="header">    
    <a href="index.php">Kansas Counties Agricultural Output</a>
  </div>
   
   <a href="addRecord.php">(( Add New Record ))</a>

   <div class="controls">
      <form id="searchForm" action="/" method="post">
        Select a commodity:
        <select id="selectCommodity">
        <?php
          foreach($commoditiesArray as $row) {
            print "<option value=\"" . $row["type"] . "\"";
            print  ">" . $row["type"] . "</option>";
          }
        ?>
        </select> 
        in year
        <select id="selectYear">
        </select>
        where
        <select id="selectMeasure">
        </select>  
        is
        <select id="selectOperator">
          <option value="eq">equal to</option>
          <option value="lt">less than</option>
          <option value="gt">greater than</option>
        </select>
        value:
        <input id="inputValue" type="number" size="12" value="" />
        <input id="searchFormSubmit" type="submit" value="Submit" />
      </form>
    </div>

    
   <div id="tabs"> 
    <ul>
      <li><a href="#tabs-1">Map View</a></li>
      <li><a href="#tabs-2">Table View</a></li>
    </ul>
    <div id="tabs-1">
      <div id="geochart"></div>
      <div class="controls">
        <br>
        <br>
      </div>
    </div>
    <div id="tabs-2">
      <div id="tablechart"></div>  
      <div class="controls" style="text-align:center">
        <span id="goFirst">First</span> | <span id="goBack"> &lt;&lt; Back</span> | <span id="goNext">Next &gt;&gt;</span> | <span id="goLast">Last</span><br>  
        <span id="show10">Show 10</span> | <span id="show25">Show 25</span> | <span id="show50">Show 50</span> | <span id="show100">Show 100</span> | <span id="show1000">Show 1000</span>
      </div>
    </div> 
  </div>

</div> <!-- /#container -->
</body>
</html>
