<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Kansas Ag Data Map Example</title>
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
<h1>Kansas Counties Ag Data Mockup</h1>
<div id="geochart"></div>
</body>
</html>
