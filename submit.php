<!DOCTYPE html>

<!--
    CREATED: 2022-04-17
    AUTHOR: W3C, toydotgame
    submit.php file called by a `<form>` in index.html.
-->

<html>
    <head>
        <title>XAMPP MySQL Response Page</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <?php
        echo "<h1>Response Page</h1><hr>";

        /*
        * LOGIN: apache, 1337
        * PERMISSIONS: INSERT, SELECT, UPDATE
        *     I know the SELECT permission is bad because an 3P1C_H4X0R could get the plaintext password from below and run `SELECT * FROM student_details;`, but OPSEC isn't exactly my 1st coding priority at 12 AM.
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
            die("<h3>Connection failed: " . $conn -> connect_error . "</h3>"); // I find it funny that the exit command is `die`, but software development is like that sometimes.
        }

        // Now that the connection is working, define the variables that we will insert:
        $name = $_POST["name"]; // "name" here comes from `id="name"` in index.html.
        $surname = $_POST["surname"];
        $id = $_POST["id"];
        if(strlen($id) < 6) { // Add leading zeroes so that $id = 6 digits.
            $id = str_pad($id, 6, "0", STR_PAD_LEFT);
        } else if(strlen($id) > 6) { // Truncate $id to always be ≤ 6 digits.
            $id = substr($id, 0, 6);
        } if(intval($id) == 0) { // If the value of the ID is 0, exit.
            die("<h3>No user ID entered.<h3>");
        }
        // TODO: Parse output from HTML form to lowercase "true" or "false" response for SQL.
        $cool = "false"; //$_POST["cool"];

        // SQL command inserts values in to the columns name, surname, id, and cool:
        $sql = "INSERT INTO $tablename (name, surname, id, cool) VALUES (\"$name\", \"$surname\", \"$id\", \"$cool\")";

        $exit = 0; // Pseudo-exit-code thing to compensate for PHP's awful syntax.
        try {
            $conn -> query($sql); // This essentially runs the command stored in $sql through the connection $conn.
        } catch(Exception $e) {
            if(str_contains($e, "mysqli_sql_exception: Duplicate entry")) {
                echo "<h3>Duplicate ID detected. Updating old entry.</h3>";

                $sql = "UPDATE $tablename SET name=\"$name\", surname=\"$surname\", cool=\"$cool\" WHERE id=\"$id\""; // Basically update the exising DB entry with $id to the new $name, $surname, and $cool values.
                $conn -> query($sql); // If this errors, it's _your_ problem. I give up.
            } else {
                echo "Error: " . $e;
                $exit = 1;
            }
        }

        if($exit == 0) {
            echo "<h3>Data entered successfully.</h3>";
            echo "<hr><table>
                      <tr><td><b>Name:</b></td><td style=\"text-align:left\">$name $surname</td></tr>
                      <tr><td><b>Student Number:</b></td><td style=\"text-align:left\">$id</td></tr>
                      <tr><td><b><i>Cool?:</i></b></td><td style=\"text-align:left\">$cool</td></tr>
                  </table><hr>";
        }

        // Close the connection like the good boy that you are:
        $conn -> close();
        ?>
    </body>
</html>