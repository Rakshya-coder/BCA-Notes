<?php
require("db.php");
session_start();

if (isset($_POST['Login'])) {
    $adminname = $_POST['Adminname'];
    $password = $_POST['Password'];

    // Hash the password
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Store hashed password

    $query = "SELECT * FROM admin_login WHERE Admin_name=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $adminname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        if ($password == $admin['Password']) {
            $_SESSION['AdminloginId'] = $admin['Admin_name'];
            header("location: adminpanel.php");
            exit();
        } else {
            echo "<script>alert('Incorrect username or password');</script>";
        }
    } else {
        echo "<script>alert('Incorrect username or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: lighter;
        }

        .input-field {
            margin-bottom: 15px;
        }

        .input-field input {
            width: calc(100% - 20px);
            padding: 10px;
            padding-left: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #0B7691;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #1b96bf;
        }
    </style>
</head>

<body>
    <div class="container">
        <form method="POST">
            <h1>Admin Login</h1>
            <div class="input-field">
                <input type="text" name="Adminname" placeholder="Admin Name" required>
            </div>
            <div class="input-field">
                <input type="password" name="Password" placeholder="Password" required>
            </div>
            <button type="submit" name="Login">Login</button>
        </form>
    </div>
</body>

</html>