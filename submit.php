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

$exit = 0; // Pseudo-exit-code thing to compensate for PHP's awful syntax.
try {
    $conn -> query($sql);
} catch(Exception $e) {
    if(str_contains($e, "mysqli_sql_exception: Duplicate entry")) {
        echo "Duplicate ID detected. Updating old entry.";

        $sql = "UPDATE $tablename SET name=\"$name\", surname=\"$surname\", cool=\"false\" WHERE id=\"$id\"";
        $conn -> query($sql); // If this errors, it's _your_ problem. I give up.
    } else {
        echo "Error: " . $e;
    }
    $exit = 1;
}

if($exit == 0) {
    echo "no error :3";
}

/*if ($conn -> query($sql) === TRUE) {
    echo "Data submitted successfully.";
} else if(mysqli_errno() == 1062) {
    
    echo "Duplicate ID detected. Updating:<br>";
    //$sql = "UPDATE $tablename SET (name, surname, cool) VALUES (\"$name\", \"$surname\", \"false\") WHERE id=\"$id\"";
    //echo "Error: " . $sql . "<br>" . $conn -> error;
} else {
    echo "Error: " . $err . "<br>" . $conn -> error; // No clue what $sql would be here. This is just a copy-paste from W3S.
}*/

// Close the connection like the good boy that you are:
$conn -> close();
?>