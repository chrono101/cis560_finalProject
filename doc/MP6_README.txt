CIS 560: Machine Problem 6
=============
Dane Miller
Cole Cooper
Blake Koblitz


==========
Forms
==========
Our record viewer is the project_form.php file. It pulls down records from our 
commodities table in our database, and allows you to browse by county.

To view the form in action, please visit:
http://people.cis.ksu.edu/~colecoop/cis560/finalProject/project_form.php

==========
Database I/O
==========
We created a PHP script to programaticaly add our county names and map
locations to our database. This will not be implemented in the final 
design, however, we will adapt this script to import our commodity data.

In the meantime, we used it to quickly organize our location data for 
our counties in the database. 

To file for this script is addCounties.php, and can be run by visiting
the following URL:
http://people.cis.ksu.edu/~colecoop/cis560/finalProject/addCounties.php?path=counties.csv

This loads the file "counties.csv" into our MySQL, one line at a time.

==========
GUI Mock-Up
==========
Our GUI mock-up consists of a thematic map of Kansas agricultural 
data, broken down by county. The GUI will primarily consist of a large map, 
with controls and data filters available in a second panel below.

The map uses Google's Visualization API, which allows us to render an SVG
map and assign data values to the markers on the map. The map gets the data
by making an AJAX call to another PHP script, getDataJSON.php, which passes
the requested data to the map in JSON format.

The source for the map is the file mockup.php, and can be viewed at: 
http://people.cis.ksu.edu/~colecoop/cis560/finalProject/mockup.php
