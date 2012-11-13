<?php

?>
<!DOCTYPE html>
<html>
<head>
  <title>Kansas Ag Data Record Viewer</title>
  <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
  <script type="text/javascript">
    // Load the Visualization API from Google
    google.load('visualization', '1', {packages: ['table']});
    google.setOnLoadCallback(drawTable);

    var defaultNum=10;
    var maxRecordNum=105;
    var start=0;
    var num=defaultNum;
    var data;
    var table;

    function drawTable() {
      var jsonData = $.ajax({
        url: "getDataJSON.php?table=ag_wheat&start=" + start + "&num=" + num,
        dataType: "json",
        async: false
        }).responseText;

        data = new google.visualization.DataTable(jsonData);

        table = new google.visualization.Table(document.getElementById('visualization'));
        table.draw(data, {showRowNumber: true});
    }
  
    $(document).ready(function() {
      $("#goFirst").click(function() {
        start = 0;
        num = 10;
        drawTable();
        });
  
      $("#goBack").click(function() {
        if (start < defaultNum) {
        start = 0;
        } else {
        start = start - num;
        }
        drawTable();
        });
  
      $("#goNext").click(function() {
        start = start + num;
        drawTable();
        });
  
      $("#goLast").click(function() {
        start = maxRecordNum - num;
        drawTable();
      });
    });
  </script>
  <style>
    span {
      text-decoration: underline;
      cursor: pointer;
      color: blue;
    }
  </style>
</head>
<body>
  <div id="visualization"></div>
  <span id="goFirst">First</span> | <span id="goBack"> &lt;&lt; Back</span> | <span id="goNext">Next &gt;&gt;</span> | <span id="goLast">Last</span>
</body>
</html>
