<?php
session_start();
include('connection_db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'email' and 'password' are set in $_POST
    if (isset($_POST['email'], $_POST['password'])) {
        $email = trim($_POST['email']);
        $enteredPassword = $_POST['password'];

        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $storedPassword = $row['password'];

                

                // Check the entered password against the stored password
                if ($enteredPassword === $storedPassword) {
                    // Successful login
                    $_SESSION['email_username'] = $email;
                    header("Location: users_dashboard.php"); // Redirect to the admin dashboard or any other page
                    exit;
                } else {
                    // Invalid password
                    echo '<script>alert("Invalid password.");</script>';
                }
            } else {
                // Invalid username
                echo '<script>alert("Invalid username.");</script>';
            }

            $stmt->close();
        } else {
            // Handle database connection error
            echo '<script>alert("Database connection error.");</script>';
        }
    } else {

        header("Location: users_login_form.php"); 
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        fieldset {
            width: 300px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        legend {
            font-size: 20px;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: #333;
            margin-top: 10px;
        }
        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .button-container button {
            margin: 0 10px;
            padding: 8px 16px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    </head>
<body>
    <div id="UsersForm">
    <fieldset>
        <legend>Users Login</legend>
        <form method="post" action="">
            <label for="email">email:</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register</a></p>
    </fieldset>
    </div>

</body>
</html>