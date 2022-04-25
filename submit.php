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
        <h1>Response Page</h1>
        <hr>
        <?php
        /*
        * LOGIN: apache, 1337
        * PERMISSIONS: INSERT, SELECT, UPDATE
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
            die("<h3>Connection failed: " . $conn -> connect_error . "</h3>");
        }

        // Define some variables based on the `name` attributes in index.html:
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $id = $_POST["id"];

        if(strlen($id) < 6) { // Add leading zeroes so that strlen($id) == 6.
            $id = str_pad($id, 6, "0", STR_PAD_LEFT);
        } else if(strlen($id) > 6) { // Truncate $id if strlen($id) > 6.
            $id = substr($id, 0, 6);
        }

        if(intval($id) == 0) { // Exit if $id is not set.
            die("<h3>No user ID entered.<h3>");
        }
        @$cool = $_POST["cool"]; // `@` suppresses errors and warnings.
        if($name == "" || $surname == "" || is_null($cool)) {
            die("<h3>You need to enter data into all fields!</h3>");
        }

        // SQL command inserts values in to their respective columns:
        $sql = "INSERT INTO $tablename (name, surname, id, cool) VALUES (\"$name\", \"$surname\", \"$id\", \"$cool\")";

        /*
         * PHP's pretty awful so I'll explain what's going on here.
         * - `try` tries to run the command stored in $sql through connection $conn.
         * - If there's an error, the exception is caught:
         *     - If the exception output contains "Duplicate entry," seperate code is run:
         *         - An UPDATE command is run, which updates $name, $surname, and $cool in the database to their new values [sent with the HTTP request], based on the fact that $id is the same.
         *     - If the exception _does not contain_ "Duplicate entry," a generic error stack trace is given and the program exits.
         */
        try {
            $conn -> query($sql);
        } catch(Exception $e) {
            if(str_contains($e, "mysqli_sql_exception: Duplicate entry")) {
                echo "<h3>Duplicate ID detected. Updating old entry.</h3>";

                $sql = "UPDATE $tablename SET name=\"$name\", surname=\"$surname\", cool=\"$cool\" WHERE id=\"$id\""; // Basically update the exising DB entry with $id to the new $name, $surname, and $cool values.
                $conn -> query($sql);
            } else {
                die("Error: " . $e);
            }
        }

        echo "<h3>Data entered successfully.</h3>";
        echo "<hr><div align=\"center\"><table>
                  <tr><td><b>Name:</b></td><td style=\"text-align:left\">$name $surname</td></tr>
                  <tr><td><b>Student Number:</b></td><td style=\"text-align:left\">$id</td></tr>
                  <tr><td><b><i>Cool?:</i></b></td><td style=\"text-align:left\">$cool</td></tr>
              </table></div><hr>";

        $conn -> close();
        ?>
        <small>If you think there's not enough CSS or whatever, just go look at <a href="https://iccmc.serveminecraft.net/">what I've already done</a>. (<a href="https://toydotgame.github.io/>Or something else?</a>)</small>
    </body>
</html>
