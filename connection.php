<?php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "testing";

   $con = mysqli_connect($servername, $username, $password, $dbname);

   if (mysqli_connect_error()) {
      die("Connection failed: " . mysqli_connect_error());
   }

?>
