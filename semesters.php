<?php
session_start();
require_once "signupdb.php";

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT semesters.id AS semester_id, semesters.name AS semester_name, GROUP_CONCAT(subjects.name SEPARATOR ', ') AS subjects
        FROM semesters
        LEFT JOIN subjects ON semesters.id = subjects.semester_id
        GROUP BY semesters.id, semesters.name
        ORDER BY semesters.id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching semesters: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Notes Hub</title>
    <link rel="stylesheet" href="css/sem.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* navigation bar design starts */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 20px;
            background-color: white;
            position: sticky;
            width: 100%;
            top: 0;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .2);
        }

        .navbar .logo img {
            height: 35px;
            width: auto;
        }

        .navbar .nav-links {
            display: flex;
            align-items: center;
        }

        .navbar .nav-links li {
            list-style: none;
            padding: 8px 15px;
        }

        .navbar .nav-links li a {
            color: black;
            text-decoration: none;
            padding: 8px 17px;
            font-size: 17px;
        }

        .navbar .nav-links li a:hover {
            color: #0b7691;
        }

        .navbar .right-nav {
            display: flex;
            align-items: center;
        }

        .navbar .search-container {
            position: relative;
            border: 1px solid #0B7691;
            border-radius: 15px;
            padding: 3px 4px;
            background-color: #f6f6f6;
            margin-left: auto;
            margin-right: 5px;
        }

        .navbar .search-box {
            background: transparent;
            border: 0px;
            outline: 0px;
        }

        .navbar .search-container .icon-search {
            border: none;
            background: none;
            color: #0B7691;
            cursor: pointer;
        }

        .navbar .logout-btn {
            border: none;
            border-radius: 10px;
            background-color: #0B7691;
            color: white;
            padding: 3px 4px;
            cursor: pointer;
            margin: 5px;
        }

        .navbar .logout-btn:hover {
            background-color: #005b72;
        }

        /* mobile view burger button */
        .burger {
            display: none;
            position: absolute;
            cursor: pointer;
            right: 0%;
            top: 20px;
        }

        .line {
            width: 30px;
            background-color: black;
            height: 2px;
            margin: 3px 3px;
        }   
        /* media query for respsonsive navbar */
        @media only screen and (max-width: 1126px) {
            .nav-links {
                flex-direction: column;
            }

            .navbar {
                height: 310px;
                flex-direction: column;
                transition: all 0.7s ease-out;
            }

            .right-nav {
                flex-direction: column;
            }

            .search-container {
                width: 90%;
            }

            .navbar .icon-search {
                display: none;
            }

            .burger {
                display: block;
            }

            .h-nav-resp {
                height: 60px;
            }

            .v-class-resp {
                opacity: 0;
            }
        }
</style>
<body>
    <nav class="navbar h-nav-resp">
        <div class="logo"><img src="images/projlogoo.png" alt="logo" /></div>
        <ul class="nav-links v-class-resp">
            <li><a href="home.php">Home</a></li>
            <li><a href="semesters.php">Notes</a></li>
            <li><a href="viewquestion_pdfs.php">Questions</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <div class="right-nav v-class-resp">
            <form method="get" action="search.php" class="search-container">
                <input type="text" placeholder="Search here" name="search" class="search-box" id="search-box" required>
                <button type="submit" class="icon-search"><i class="fa fa-search"></i></button>
            </form>
            <form method="post" style="display: inline;">
                <button class="logout-btn" name="logout">Logout</button>
            </form>
        </div>
        <div class="burger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>
    <section class="header">
        <h1>Semesters</h1>
        <div class="sem-links">
            <p><a href="home.php">home</a> /about</p>
        </div>
    </section>
    <main>
        <section class="semesters">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="semester">';
                echo '<h2>' . htmlspecialchars($row['semester_name']) . '</h2>';
                echo '<p>This semester course includes: ' . htmlspecialchars($row['subjects']) . '.</p>';
                echo '<a class="btn" href="semester.php?id=' . htmlspecialchars($row['semester_id']) . '">View Course Details</a>';
                echo '</div>';
            }
            ?>
        </section>
    </main>
    <script src="javascript/resp.js"></script>
</body>
</html>