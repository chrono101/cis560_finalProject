<?php
  $conn = mysql_connect("mysql.cis.ksu.edu", "colecoop", "insecurepassword");
  
  if ($conn) {
    mysql_select_db("colecoop", $conn);
    $result = mysql_query("SELECT CountyName FROM agcom_counties");
    while($array[] = mysql_fetch_array($result, MYSQL_ASSOC));

    mysql_close($conn);
  }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>Add New Record - Kansas Counties Agricultural Output</title>

  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
  <div id="header">
    <a href="index.php">Kansas Counties Agricultural Output</a>
  </div>
    Add New Record:
  <div class="controls">
      <div id="left-controls" class="clearfix">
        Year: &nbsp;&nbsp;<br>
        County: &nbsp;&nbsp;<br>
        Commodity: &nbsp;&nbsp;<br>
        Measurement Unit: &nbsp;&nbsp;<br>
        Value: &nbsp;&nbsp;
        <br><br><br>
      </div>
  
      <div id="right-controls" class="clearfix">
        <form action="addRecordSQL.php" method="post">
          <input id="year" type="text" name="year" style="width:140px"><br>
          <select id="county" style="width:146px" name="county">
          <?php 
            foreach($array as $row) {
              print "<option value=\"" . $row["CountyName"] . "\">" . $row["CountyName"] . "</option>";
            }
          ?>
          </select><br>
          <input id="commodity" type="text" name="commodity" style="width:140px"><br>
          <input id="measurement" type="text" name="measurement" style="width:140px"><br>
          <input id="value" type="text" name="value" style="width:140px"><br>
          <input type="submit" value="Save Record">
          <a href="./index.php">(( Cancel ))</a>
        </form>
      </div>
  </div> <!-- /.controls -->
    Import A File:
  <div class="controls" style="text-align:center">
    <form action="importFileSQL.php" method="post" enctype="multipart/form-data">
      <input id="inputFile" name="inputFile" type="file">
      <input id="importFile" type="submit" value="Import File">
      <a href="./index.php">(( Cancel ))</a>
   </form>
  </div>
</body>
</html>
