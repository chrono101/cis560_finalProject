<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Kansas Counties Agricultural Output</title>

<script type="text/javascript" src="http://www.google.com/jsapi"></script> 
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
google.load('visualization', '1', {packages: ['geochart']});
google.setOnLoadCallback(drawGeochart);

function drawGeochart() {
  var jsonData = $.ajax({
    url: "getDataJSON.php?table=ag_wheatProduction&start=0&num=105",
    dataType: "json",
    async: false
   }).responseText;
  
  var data = new google.visualization.DataTable(jsonData);
  
  
  var options = {
    resolution: 'provinces',
    region: 'US-KS',
    displayMode: 'markers',
    width: 960,
    colorAxis: {colors: ['blue', 'green', 'yellow', 'orange', 'red']}
  };

  var geochart = new google.visualization.GeoChart(document.getElementById('geochart'));
  geochart.draw(data, options);
}

</script>
</head>
<body>
<div align = "center">

<div style="width:960px;border:2px outset black;border-width:10px">
<h1>Kansas Counties Agricultural Output</h1>
</div>

<div id="geochart" style="width:960px;border:2px outset black;border-width:10px"></div>
<div style="width:960px;border:2px outset black;border-width:10px">

Search Database:

<div align = "center" style="width:950px;border:2px outset black;border-width:4px">

<form action="/" method="get">
Select a Year:
<select id="selectYear">
  <option value="2011">2011</option>
  <option value="2012">2012</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Select a Commodity:
<select id="selectCommodity">
  <option value="Wheat">Wheat</option>
  <option value="Corn">Corn</option>
  <option value="Cattle">Cattle</option>
</select> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Sort Output Values From:
<select id="sortOrder">
  <option value="HighToLowWheat">High to Low</option>
  <option value="LowToHigh">Low to High</option>
</select> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input id="submit" type="submit" value="Submit" />
</form>

</div>

</div>

<div id="tablechart" style="width:960px;border:2px outset black;border-width:10px">
List Table:
</div>

</div>
</body>
</html>
