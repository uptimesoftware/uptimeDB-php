uptimeDB-php
============

PHP Class to assist running queries against the different up.time datastore types

Handles the task of setting up a connection to the uptime datastore based on the db details in the uptime.conf file.
Leverages either mysqli or odbc drivers depending on the datastore type.

Which greatly simplifies the process for creating simple php pages that display details from up.time.

## Setup & Use

 Place the uptimeDB.php in a subfolder within your monitor stationing's uptime_dir/GUI folder, along with your own custom page. Then include the following to load the class, and setup the intial db connection.

```
include("uptimeDB.php");
$db = new uptimeDB;
$db->connectDB();
```


Running an actually query against the datastore is done like so:

```
$sql = "select name from entity";
$results = $db->execQuery($sql);

foreach ($results as $row)
{
  echo $row['NAME'] . "<\br>";
}
```

Calling execQuery will execute the query, and returns the resultset as a multi-dimeonsional array similar to mysqli's associative array resultsets. Though the names of each column are capitalized to normalize things across Mysql, Oracle, and SQLServer.

