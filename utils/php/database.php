<?php 
// mysql connection
$hostname = "localhost";
$username = "root";
$password = "";
$database = "sociotime";
$conn = mysqli_connect($hostname, $username, $password, $database);

// query function
function query($query) {
  global $conn;
  $result = mysqli_query($conn, $query);

  // insert all row fetched to an array
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}
?>