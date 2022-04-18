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
        *     The SELECT permission is bad because an 3P1C_H4X0R could get the plaintext password from below and login with it and run `SELECT * FROM student_details;` and have essentially leaked all the stored information.
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

        // Now that the connection is working, define the variables that we will insert:
        $name = $_POST["name"]; // The POST values here are named based off of the `<input>` `name` attributes in index.html.
        $surname = $_POST["surname"];
        $id = $_POST["id"];
        if(strlen($id) < 6) { // Add leading zeroes so that strlen($id) == 6.
            $id = str_pad($id, 6, "0", STR_PAD_LEFT);
        } else if(strlen($id) > 6) { // Truncate $id if strlen($id) > 6.
            $id = substr($id, 0, 6);
        } if(intval($id) == 0) { // If the value of the ID is 0, exit.
            die("<h3>No user ID entered.<h3>");
        }
        @$cool = $_POST["cool"]; // `@` suppresses errors and warnings.
        if($cool == "") { // Catches if $cool was not set.
            die("<h3>You need to choose an option on if you are cool or not!</h3>");
        }

        // SQL command inserts values in to the columns name, surname, id, and cool:
        $sql = "INSERT INTO $tablename (name, surname, id, cool) VALUES (\"$name\", \"$surname\", \"$id\", \"$cool\")";

        /*
         * PHP's pretty awful so I'll explain what's going on here.
         * - $exit is an exit code. (0 is OK, ≠ 0 is an error) It is set to 1 if the `catch()` is ever run.
         * - `try` tries to run the command stored in $sql through connection $conn.
         * - If there's an error, the exception is caught:
         *     - If the exception output contains "Duplicate entry," seperate code is run:
         *         - The command in $sql is updated to an UPDATE command, which updates $name, $surname, and $cool in the database to their new values [sent with the HTTP request], based on the fact that $id is the same.
         *         - The UPDATE command is run.
         *     - If the exception _does not contain_ "Duplicate entry," a generic error stack trace is given.
         *         - TODO: If there's still an error, just `die()`, instead of using a whole exit code thing. The data response `echo` won't run anyway if the code has exited.
         */
        $exit = 0;
        try {
            $conn -> query($sql);
        } catch(Exception $e) {
            if(str_contains($e, "mysqli_sql_exception: Duplicate entry")) {
                echo "<h3>Duplicate ID detected. Updating old entry.</h3>";

                $sql = "UPDATE $tablename SET name=\"$name\", surname=\"$surname\", cool=\"$cool\" WHERE id=\"$id\""; // Basically update the exising DB entry with $id to the new $name, $surname, and $cool values.
                $conn -> query($sql);
            } else {
                echo "Error: " . $e;
                $exit = 1;
            }
        }

        if($exit == 0) {
            echo "<h3>Data entered successfully.</h3>";
            echo "<hr><div align=\"center\"><table>
                      <tr><td><b>Name:</b></td><td style=\"text-align:left\">$name $surname</td></tr>
                      <tr><td><b>Student Number:</b></td><td style=\"text-align:left\">$id</td></tr>
                      <tr><td><b><i>Cool?:</i></b></td><td style=\"text-align:left\">$cool</td></tr>
                  </table></div><hr>";
        }

        /* For admins: Use a local MySQL/MariaDB client and browse the DB from the commandline:
         * `mysql -u apache -p learning` (The password is "1337")
         * `SHOW TABLES;`
         * `SELECT * FROM student_details;`
         * `SELECT * FROM student_details WHERE id LIKE "<student number>";`
         */

        // Close the connection once we're done:
        $conn -> close();
        ?>
    </body>
</html>