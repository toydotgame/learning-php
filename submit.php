<?php

/* 
 * CREATED: 2022-04-17
 * AUTHOR: W3C, toydotgame
 * submit.php file called by a `<form>` in index.html.
 */

/*
 * LOGIN: apache, 1337
 * PERMISSIONS: INSERT
 * DB: learning, TABLE: student_details
 */
$servername = "localhost";
$username = "apache";
$password = "1337";
$dbname = "learning";
$tablename = "student_details";

// Create MySQL connection:
$conn = new mysqli($servername, $username, $password, $dbname);
// Test connection:
if ($conn -> connect_error) {
  die("Connection failed: " . $conn -> connect_error); // I find it funny that the exit command is `die`, but software development is like that sometimes.
}

// Now that the connection is working, define the variables that we will insert:
$name = $_POST["name"]; // "name" here comes from `id="name"` in index.html.
$surname = $_POST["surname"];
$id = $_POST["id"];
// TODO: Parse output from HTML form to lowercase "true" or "false" response for SQL.
//$cool = $_POST["cool"];

// SQL command inserts values in to the columns name, surname, id, and cool:
$sql = "INSERT INTO $tablename (name, surname, id, cool) VALUES (\"$name\", \"$surname\", \"$id\", \"false\")";

if ($conn -> query($sql) === TRUE) {
  echo "Data submitted successfully.";
} else {
  echo "Error: " . $sql . "<br>" . $conn -> error; // No clue what $sql would be here. This is just a copy-paste from W3S.
}

// Close the connection like the good boy that you are:
$conn -> close();
?>