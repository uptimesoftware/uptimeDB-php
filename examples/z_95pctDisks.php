<?php
require 'uptimeDB.php';

// This program uses the uptimeDB.php module to set up calls to the Oracle database.
// This is called from the uptime GUI directory as a web page.
// The uptimeDB.php file must exist in the GUI directory and be readable.


// Turn on reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);


// Set the uptime.conf file location
$UPTIME_CONF = '/usr/local/uptime/uptime.conf';


// Start CSS page setup
print "<html><br><br>";
print "<head><style type \"text/css\">";
print "{font-family: Arial, Helvetica, sans-serif; width:90%; border-collapse:collapse; }";
print "table { margin: 1em; border-collapse: collapse; }";
print "td, th {font-size:.75em; font-weight:normal; width:200px; padding: 3px 7px 2px 7px; border: 1px solid #98bf21; }";
print "th {font-size:.75em; text-align:center; padding-top:5px; padding-bottom: 4px; background-color:#A7C942; color:#ffffff;}";
print "thead {background: #fc9; }";
print "h1 {font-family:Arial,Helvetica,sans-serif}"; 
print "</style></head>";


// Do the database connection
$mydb = new uptimeDB;
$mydb->readUptimeConf($UPTIME_CONF);
$mydb->connectDB();


// SQL to get disks over 95% full
$sql = "SELECT e.display_name
                ,pf.filesystem
                ,pf.percent_used
FROM uptime.entity e
                ,uptime.performance_fscap pf
                ,uptime.performance_sample ps
WHERE ps.id = pf.sample_id
                AND ps.sample_time > sysdate - 1/24
                AND e.entity_id = ps.uptimehost_id
                AND pf.filesystem not like '%.iso'
                AND pf.percent_used > 95
GROUP BY e.display_name
                ,pf.filesystem
                ,pf.percent_used
ORDER BY pf.percent_used desc";
$Myresult = $mydb->execQuery($sql);


// Print header and begin the table for display
print "<body><p><center><h1><font color=\"olivedrab\" size=\"5\">Current Servers With Disks over 95% Full</font><br>";
print "<table><thead<tr><th>Server Name</th><th>Volume Name</th><th>Percent Used</th><tr></thead>";


// Loop through the returned array with the information
foreach ($Myresult as $key=>$list) {
   $server  = $list['DISPLAY_NAME'];
   $drive   = $list['FILESYSTEM'];
   $percent = $list['PERCENT_USED'];
   print "<tr><td>".$server."</td><td>".$drive."</td><td>".$percent."</td></tr>";
}

// Close the table and end the webpage
print"</table>";
print "</html>";
?>
