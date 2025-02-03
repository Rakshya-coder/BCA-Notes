<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        session_start();

        if (isset($_POST["submit"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];

            require_once "signupdb.php";

            if (empty($email) || empty($password)) {
                echo "<div class='alert-danger'>All fields are required</div>";
            } else {
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "<div class='alert-danger'>SQL statement failed</div>";
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        if (isset($row['Password'])) {
                            $passwordCheck = password_verify($password, $row['Password']);
                            if ($passwordCheck == false) {
                                echo "<div class='alert-danger'>Invalid email or password</div>";
                            } elseif ($passwordCheck == true) {
                                // Set session variables
                                $_SESSION['userId'] = $row['id'];
                                $_SESSION['userEmail'] = $row['email'];
                                echo "<div class='alert-success'>Login successful!</div>";
                                // Redirect to a new page
                                header("Location: home.php");
                                exit();
                            } else {
                                echo "<div class='alert-danger'>Invalid email or password</div>";
                            }
                        } else {
                            echo "<div class='alert-danger'>Password key is missing</div>";
                        }
                    } else {
                        echo "<div class='alert-danger'>No user found with this email</div>";
                    }
                }
            }
        }
        ?>
        <form action="login.php" method="post">
            <h1>Login</h1>
            <div class="form_group">
                <input type="text" name="email" placeholder="Email" required>
            </div>
            <div class="form_group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form_group">
                <input type="submit" value="Login" name="submit">
            </div>
            <div class="link">
                <a href="signup.php">Don't have an account? <span>Sign up here</span></a>
            </div>
        </form>
    </div>
</body>

</html>