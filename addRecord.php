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
  <title>Kansas Counties Agricultural Output</title>
<body>
<div align="center">
  <div style="width:960px;border:2px outset black;border-width:10px">
    <h1>Kansas Counties Agricultural Output</h1>
  </div>
  <div style="width:960px;border:2px outset black;border-width:10px;height:400px">
    <div align = "center" style="float:top;width:100%;border:2px outset black;border-width:4px">
      Add New Record:
    </div>

    <div align = "right" style="float:left;width:50%;">
      Year: &nbsp;&nbsp;<br>
      County: &nbsp;&nbsp;<br>
      Commodity: &nbsp;&nbsp;<br>
      Measurement Unit: &nbsp;&nbsp;<br>
      Value: &nbsp;&nbsp;
    </div>

    <div align = "left" style="float:right;width:50%;">
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
      <a href="./index.php">Cancel</a>
    </form>
  </div>
</div>
</div>
</body>
</html>
