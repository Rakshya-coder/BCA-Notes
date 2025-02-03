<?php
// Database connection
session_start();
require_once "signupdb.php";

// Get semesters
$semester_result = $conn->query("SELECT * FROM semesters");

$selected_semester = '';
$selected_subject = '';
$subjects = [];
$pdf_result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['semester'])) {
        $selected_semester = $_POST['semester'];

        // Fetch subjects based on the selected semester
        $stmt = $conn->prepare("SELECT * FROM subjects WHERE semester_id = ?");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die('Error preparing statement: ' . $conn->error); // Added error handling
        }

        $stmt->bind_param("i", $selected_semester);
        $stmt->execute();
        $subject_result = $stmt->get_result();

        while ($row = $subject_result->fetch_assoc()) {
            $subjects[] = $row;
        }
        $stmt->close();
    }

    if (!empty($_POST['subject'])) {
        $selected_subject = $_POST['subject'];

        // Fetch PDFs based on the selected subject
        $stmt = $conn->prepare("SELECT * FROM questions WHERE subject_id = ?");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die('Error preparing statement: ' . $conn->error); // Added error handling
        }

        $stmt->bind_param("i", $selected_subject);
        $stmt->execute();
        $pdf_result = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>View PDFs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Notes Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

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
            padding: 10px 20px;
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
            padding: 8px 15px;
            font-size: 14px;
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
            background-color: #1b96bf;
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

        /* body section starts */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #0B7691;
            margin: 0;
            padding: 0;
        }

        .main-content {
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 750px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #0B7691;
            padding: 10px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }




        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #0B7691;
        }

        .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-btn button {
            background-color: #0B7691;
            color: white;
            width: 20%;
            padding: 10px 0;
            margin-bottom: 5px;
            text-align: center;
            border-radius: 5px;
            border: none;
        }

        .form-group button {
            width: 100%;
            padding: 10px 0;
            margin-top: 20px;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }

        .form-btn button:hover {
            background-color: #1b96bf;
        }

        .pdf-list {
            margin-top: 20px;
        }

        .pdf-list ul {
            list-style-type: none;
            padding: 0;
        }

        .pdf-list li {
            margin-bottom: 10px;
        }

        .pdf-list a {
            text-decoration: none;
            color: #0B7691;
        }

        .pdf-list a:hover {
            text-decoration: underline;
        }

        /* media query for responsive navbar */
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

</head>

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
                <input type="text" placeholder="Search here" name="search" class="search-box" id="search-box">
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

    <div class="main-content">
        <div class="container">
            <h1>View Uploaded Question PDFs</h1>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="semester">Select Semester:</label>
                    <select name="semester" id="semester" onchange="this.form.submit()">
                        <option value="">Select Semester</option>
                        <?php while ($row = $semester_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $selected_semester) echo 'selected'; ?>>
                                <?php echo $row['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <?php if (!empty($subjects)): ?>
                    <div class="form-group">
                        <label for="subject">Select Subject:</label>
                        <select name="subject" id="subject">
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>" <?php if ($subject['id'] == $selected_subject) echo 'selected'; ?>>
                                    <?php echo $subject['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-btn">
                        <button type="submit">View</button>
                    </div>
                <?php endif; ?>
            </form>

            <?php if ($pdf_result !== null): ?>
                <?php if ($pdf_result->num_rows > 0): ?>
                    <div class="pdf-list">
                        <ul>
                            <?php while ($pdf = $pdf_result->fetch_assoc()): ?>
                                <li>
                                    <a href="uploads/<?php echo urlencode($pdf['file_name']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($pdf['file_name']); ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <p>No PDFs are available for this subject. PDF is not uploaded.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>