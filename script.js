// Once document is ready to go
$(document).ready(function() {

  // Register an override for the form submission
  $("#searchForm").submit(function() {
    selectedCommodity = $("#selectCommodity").val();
    selectedYear = $("#selectYear").val();
    selectedSort = $("#sortOrder").val();
    selectedMeasure = $("#selectMeasure").val();
    selectedOperator = $("#selectOperator").val();
    inputValue = $("#inputValue").val();

    drawGeochart();
    drawTable();
    return false;
  });

  $("#tabs").tabs();

  // Register an event handler for the "on change" event of the commodity drop-down menu
  $("#selectCommodity").change(function() {
    updateControls();
  });

  // Controls for the table pagination
  $("#goFirst").click(function() {
    tableStart = 0;
    tableNumRecords = 10;
    drawTable();
  });

  $("#goBack").click(function() {
    if (tableStart < tableDefaultNum) {
      tableStart = 0;
    } else {
      tableStart = tableStart - tableNumRecords;
    }
    drawTable();
  });

  $("#goNext").click(function() {
    tableStart = tableStart + tableNumRecords;
    drawTable();
  });

  $("#goLast").click(function() {
    tableStart = tableMaxRecords - tableNumRecords;
    drawTable();
  });

  $("#show1000").click(function() {
    tableNumRecords = 1000;
    drawTable();
  });    

  $("#show10").click(function() {
    tableNumRecords = 10;
    drawTable();
  });

  $("#show25").click(function() {
    tableNumRecords = 25;
    drawTable();
  });

  $("#show50").click(function() {
    tableNumRecords = 50;
    drawTable();
  });  
  
  $("#show100").click(function() {
    tableNumRecords = 100;
    drawTable();
  });




  updateControls();
});

// Declare variables
var selectedCommodity;
var selectedYear;
var selectedSort;
var selectedMeasure;
var selectedOperator;
var inputValue;

// Table variables
var tableStart = 0;
var tableDefaultNum = 10;
var tableNumRecords = tableDefaultNum;
var tableMaxRecords = 1000;

// Loads in the Google APIs
google.load('visualization', '1', {packages: ['geochart']});
google.setOnLoadCallback(drawGeochart);
google.load('visualization', '1', {packages: ['table']});
google.setOnLoadCallback(drawTable);

// This function makes an AJAX request to get the data to draw the geochart
function drawGeochart() {
  var jsonData = $.ajax({
    url: "getDataGeochartJSON.php?type="+selectedCommodity+"&year="+selectedYear+"&start=0&num=105&measure="+selectedMeasure+"&op="+selectedOperator+"&val="+inputValue,
    dataType: "json",
    async: false
  }).responseText;

  //alert(jsonData);

  var data = new google.visualization.DataTable(jsonData);  

  var options = {
    resolution: 'provinces',
    region: 'US-KS',
    displayMode: 'markers',
    magnifyingGlass: { 
      enable: false
    },
    width: 960,
       colorAxis: {colors: ['blue', 'green', 'yellow', 'orange', 'red']}
  };

  // This draws the chart, using the Google API
  var geochart = new google.visualization.GeoChart(document.getElementById('geochart'));
  geochart.draw(data, options);
}

// This function makes an AJAX request to get the data to draw the table 
function drawTable() {
  var jsonData = $.ajax({
    url: "getDataTableJSON.php?type="+selectedCommodity+"&year="+selectedYear+"&start=0&num=105&measure="+selectedMeasure+"&start="+tableStart+"&num="+tableNumRecords,
    dataType: "json",
    async: false
  }).responseText;

  //alert(jsonData);
  var options = {
    showRowNumber: true,
    width: "100%"
  }

  var data = new google.visualization.DataTable(jsonData);

  // This draws the table, using the Google API
  var table = new google.visualization.Table(document.getElementById('tablechart'));
  table.draw(data, options);
}

// This table updates the current controls based upon which commodity is selected
function updateControls() {
  var comm = $("#selectCommodity").val();

  // Update the years control
  var years = $.ajax({
    url: "getDataControls.php?comm="+comm+"&control=year",
    dataType: "html",
    async: false
  }).responseText
  $("#selectYear").html(years); 

  // Update the measurements control
  var measures = $.ajax({
    url: "getDataControls.php?comm="+comm+"&control=measurement",
    dataType: "html",
    async: false
  }).responseText
  $("#selectMeasure").html(measures); 

}
