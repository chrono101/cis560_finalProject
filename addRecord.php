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
<div align = "center">

<div style="width:960px;border:2px outset black;border-width:10px">
<h1>Kansas Counties Agricultural Output</h1>
</div>

<div style="width:960px;border:2px outset black;border-width:10px">

Add New Record:

<div align = "right" style="width:300px;border:2px outset black;border-width:4px">


<form action="addRecordSQL.php" method="post">
Year: <input type="text" name="year"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
County: <select style="width:142px" name="county">
<?php 
  foreach($array as $row) {
    print "<option value=\"" . $row["CountyName"] . "\">" . $row["CountyName"] . "</option>";
  }
?>
</select>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Commodity: <input type="text" name="commodity"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Measurement Unit: <input type="text" name="measurement"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Value: <input type="text" name="value"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<div align="center">
<input type="submit" value="Save Record" href="addRecordSQL.php" value="Cancel">
</form>
<form action="index.php">
<input type="submit" value="Cancel">
</form>
</div>





</div>

</div>


</div>
</body>
</html>
