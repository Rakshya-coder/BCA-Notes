<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <style>
        body {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form h1 {
            color: black;
            font-weight: lighter;
        }

        .form_group {
            margin: 10px;
            width: 100%;
            padding-left: 10px;
        }

        .form_group input[type="text"],
        .form_group input[type="email"],
        .form_group input[type="password"] {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form_group input[type="submit"] {
            background-color: #0B7691;
            color: white;
            border: none;
            padding: 8px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            width: calc(100% - 10px);
        }

        .form_group input[type="submit"]:hover {
            background-color: #1b96bf;
        }

        .alert-danger {
            color: red;
            margin-bottom: 15px;
        }

        .alert-success {
            color: green;
            margin-bottom: 15px;
        }

        .link a {
            text-decoration: none;
            color: black;
            font-size: 14px;
        }

        .link a span {
            color: #0B7691;
        }

        .link a span:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            $errors = array();

            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password !== $confirm_password) {
                array_push($errors, "Passwords do not match");
            }
            require_once "signupdb.php";
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                array_push($errors, "SQL statement failed");
            } else {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) > 0) {
                    array_push($errors, "Email already exists!");
                }
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert-danger'>$error</div>";
                }
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert-success'>You are registered successfully.</div>";
                    header("Location: login.php");
                    exit();
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="signup.php" method="post">
            <h1>Sign Up</h1>
            <div class="form_group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form_group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form_group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form_group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="form_group">
                <input type="submit" value="Sign Up" name="submit">
            </div>
            <div class="link">
                <a href="login.php">Already have an account? <span>Login here</span></a>
            </div>
        </form>
    </div>
    </div>
</body>

</html>