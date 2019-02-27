<?php
    echo "hello<br>";
   $host        = "host = 127.0.0.1";
   $port        = "port = 5432";
   $dbname      = "dbname = group_13";
   $credentials = "user = codebat password=group13";
    echo "now doing connect <br>";
//    $db = pg_connect( "$host $port $dbname $credentials"  );
   $db = pg_connect("host=127.0.0.1 port=5432 dbname=group_13 user=codebat password=group13");
   echo "lets see<br>";
   if(!$db) {
      echo "Error : Unable to open database\n";
   } else {
      echo "Opened database successfully\n";
   }
?>