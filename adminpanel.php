Utsana Thapa
<?php
session_start();
require_once "signupdb.php";

// Check if admin is logged in
if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit;
}

// Fetch user count
$userCountQuery = "SELECT COUNT(*) AS user_count FROM users";
$userCountResult = mysqli_query($conn, $userCountQuery);
if (!$userCountResult) {
    die('Error fetching user count: ' . mysqli_error($conn));
}
$userCount = mysqli_fetch_assoc($userCountResult)['user_count'];

// Fetch comment count  
$commentCountQuery = "SELECT COUNT(*) AS comment_count FROM comments";
$commentCountResult = mysqli_query($conn, $commentCountQuery);
if (!$commentCountResult) {
    die('Error fetching comment count: ' . mysqli_error($conn));
}
$commentCount = mysqli_fetch_assoc($commentCountResult)['comment_count'];

// Fetch users
$userQuery = "SELECT username, password, email FROM users";
$userResult = mysqli_query($conn, $userQuery);
if (!$userResult) {
    die('Error fetching users: ' . mysqli_error($conn));
}

// Fetch comments
$commentQuery = "SELECT comments.id, comments.comment, comments.created_at, comments.edited, comments.user_id, comments.admin_reply, users.username 
                 FROM comments 
                 JOIN users ON comments.user_id = users.id 
                 ORDER BY comments.created_at DESC";
$commentResult = mysqli_query($conn, $commentQuery);
if (!$commentResult) {
    die('Error fetching comments: ' . mysqli_error($conn));
}

// Fetch subjects for course management
$subjectQuery = "SELECT id, name FROM subjects";
$subjectResult = mysqli_query($conn, $subjectQuery);
if (!$subjectResult) {
    die('Error fetching subjects: ' . mysqli_error($conn));
}

// Fetch semesters for question management
$semesterQuery = "SELECT DISTINCT semester FROM questions ORDER BY semester";
$semesterResult = mysqli_query($conn, $semesterQuery);
if (!$semesterResult) {
    die('Error fetching semesters: ' . mysqli_error($conn));
}

// Handle comment reply
if (isset($_POST['reply_comment'])) {
    $comment_id = $_POST['comment_id'];
    $admin_reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);
    $sql_reply_comment = "UPDATE comments SET admin_reply=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql_reply_comment);
    mysqli_stmt_bind_param($stmt, "si", $admin_reply, $comment_id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        header("Location: adminpanel.php");
        exit();
    } else {
        echo "Error replying to comment.";
    }
}

// Handle comment delete
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $sql_delete_comment = "DELETE FROM comments WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql_delete_comment);
    mysqli_stmt_bind_param($stmt, "i", $comment_id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        header("Location: adminpanel.php");
        exit();
    } else {
        echo "Error deleting comment.";
    }
}

// Handle course upload
if (isset($_POST['upload_course'])) {
    // Logic for uploading a course
    // This can include file upload handling, updating database, etc.
    // Placeholder for the actual implementation
    echo "Course upload functionality goes here.";
}

// Handle course delete
if (isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];
    $sql_delete_course = "DELETE FROM subjects WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql_delete_course);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        header("Location: adminpanel.php");
        exit();
    } else {
        echo "Error deleting course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;

        }

        .sidebar {
            font-family: Arial, sans-serif;
            width: 200px;
            background-color: #0B7691;
            color: #fff;
            padding: 15px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 15px;
            margin: 5px 0;
            cursor: pointer;
        }

        .sidebar a:hover {
            background-color: #1b96bf;
        }

        .main-content {
            margin-left: 230px;
            padding: 20px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .dashboard-cards {
            display: flex;
            justify-content: center;
            margin-top: 35px;
        }

        .dash-card {
            background-color: #ddeaee;
            border-radius: 3px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
            margin-right: 15px;
        }

        .dash-card i {
            width: 50px;
            height: 50px;
            line-height: 50px;
            border-radius: 50%;
            background-color: white;
            margin-left: 10px;
            margin-right: 30px;
            color: #0B7691;
        }

        .dash-card .sub-card .num-txt {
            font-size: 17px;
            font-weight: 700;
        }

        .dash-card .sub-card .text-txt {
            font-size: 17px;
        }

        h1,
        h2 {
            text-align: center;
            font-weight: lighter;
            color: #0B7691;
        }

        .logout-btn {
            display: flex;
            justify-content: flex-end;
            padding: 5px;
        }

        .logout-btn form {
            margin: 0;
        }

        .logout-btn button {
            background-color: #0B7691;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-btn button:hover {
            background-color: #1b96bf;
        }

        .comments {
            margin-top: 20px;
            width: 90%;
            margin-left: 50px;
        }

        .comment {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .comment-username {
            font-weight: bold;
        }

        .comment-date {
            font-style: italic;
            color: #555;
            font-size: 0.7em;
        }

        .comment-text {
            margin-top: 5px;
        }

        .comment-edited {
            color: #ff0000;
            font-size: 0.8em;
        }

        .admin-reply {
            background-color: #f0f0f0;
            padding: 10px;
            margin-top: 5px;
            border-left: 3px solid #0B7691;
            width: fit-content;
        }

        .reply-form {
            margin-top: 10px;
            width: 70%;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .reply-form textarea {
            width: 60%;
            height: 100px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reply-form button {
            background-color: #0B7691;
            color: #fff;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 2px;
        }

        .reply-form button:hover {
            background-color: #1b96bf;
        }

        button {
            background-color: #0b7691;
            color: white;
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 7px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1b96bf;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        /* styling form course management section starts */
        #course_management select,
        #course_management input[type="file"] {
            padding: 3px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        a.file-link {
            color: black;
            text-decoration: none;
        }

        a.file-link:hover {
            text-decoration: underline;
        }

        /* styling for question management section starts */
        #question_management form {
            display: flex;
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 15px;
            margin-left: 90px;
        }

        #question_management label {
            display: block;
            margin-bottom: 5px;
        }

        #question_management select,
        #question_management input[type="file"] {
            margin-bottom: 10px;
            padding: 8px;
            width: 90%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #question_management #delete_question {
            margin-bottom: 10px;
            padding: 8px;
            width: 25%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #question_management p {
            margin-left: 90px;
        }

        #question_management ul {
            list-style: none;
            margin-left: 50px;
        }

        #question_management ul li a {
            color: #0B7691;
        }

        /* responsive media queries */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                text-align: center;
                padding: 15px;
            }

            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .container {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .sidebar a {
                font-size: 14px;
                padding: 10px;
            }

            .main-content {
                padding: 5px;
            }

            .container {
                padding: 10px;
            }

            .logout-btn button {
                padding: 5px 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 566px) {
            .dashboard-cards {
                flex-direction: column;
            }

            .dash-card {
                margin-bottom: 10px;
            }
        }
    </style>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.container');
            sections.forEach(section => section.style.display = 'none');

            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>
</head>

<body onload="showSection('dashboard')">
    <div class="sidebar">
        <a onclick="showSection('dashboard')">Dashboard</a>
        <a onclick="showSection('user_info')">User Information</a>
        <a onclick="showSection('comment_management')">Comment Management</a>
        <a onclick="showSection('course_management')">Course Management</a>
        <a onclick="showSection('question_management')">Question Management</a>
    </div>


    <div class="main-content">
        <div id="dashboard" class="container">
            <div class="logout-btn">
                <form method="POST" action="adminlogout.php">
                    <button type="submit">Logout</button>
                </form>
            </div>
            <h1>Welcome to the Admin Panel</h1>
            <div class="dashboard-cards">
                <div class="dash-card">
                    <i class="fa fa-user" style="font-size:24px"></i>
                    <div class="sub-card">
                        <p class="num-txt"><?php echo $userCount; ?></p>
                        <p class="text-txt">Total Users
                        <p>
                    </div>
                </div>
                <div class="dash-card">
                    <i class="fa fa-comment-o" style="font-size:24px"></i>
                    <div class="sub-card">
                        <p class="num-txt"><?php echo $commentCount; ?></p>
                        <p class="text-txt">Total Comments
                        <p>
                    </div>
                </div>
            </div>
        </div>


        <div id="user_info" class="container">
            <h2>User Information</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($userResult)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['password']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <div id="comment_management" class="container">
            <h2>Comment Management</h2>
            <div class="comments">
                <?php while ($row = mysqli_fetch_assoc($commentResult)) : ?>
                    <div class="comment">
                        <div class="comment-username"><?php echo htmlspecialchars($row['username']); ?></div>
                        <div class="comment-date"><?php echo htmlspecialchars($row['created_at']); ?></div>
                        <div class="comment-text"><?php echo nl2br(htmlspecialchars($row['comment'])); ?></div>
                        <?php if ($row['edited']) : ?>
                            <div class="comment-edited">(Edited)</div>
                        <?php endif; ?>
                        <?php if ($row['admin_reply']) : ?>
                            <div class="admin-reply"><?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></div>
                        <?php endif; ?>
                        <form class="reply-form" method="POST" action="">
                            <textarea name="admin_reply" placeholder="Reply to the comment..." required></textarea>
                            <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="reply_comment">Reply</button>
                        </form>
                        <form method="POST" action="">
                            <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_comment">Delete Comment</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div id="course_management" class="container">
            <h2>Course Management</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="file">Upload PDF:</label>
                <input type="file" name="file" id="file" required>
                <br><br>
                <label for="subject_id">Select Subject:</label>
                <select name="subject_id" id="subject_id">
                    <?php
                    mysqli_data_seek($subjectResult, 0); // Reset pointer for subject dropdown
                    while ($row = mysqli_fetch_assoc($subjectResult)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="upload_course">Upload</button>
            </form>
            <h2>Delete PDF</h2>
            <table>
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT notes.id AS upload_id, notes.file_name, subjects.name AS subject_name
                   FROM notes
                   JOIN subject_pdfs ON notes.id = subject_pdfs.upload_id
                   JOIN subjects ON subject_pdfs.subject_id = subjects.id";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $fileName = htmlspecialchars($row['file_name']);
                        $filePath = "uploads/" . $fileName;
                        echo "<tr>";
                        echo "<td><a href='$filePath' target='_blank' class='file-link'>$fileName</a></td>";
                        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                        echo "<td>
                        <form action='delete.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this file?\");'>
                            <input type='hidden' name='file_name' value='$fileName'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                      </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="question_management" class="container">
            <h2>Question Management</h2>
            <form action="upload_question.php" method="post" enctype="multipart/form-data">
                <label for="file">Upload Question PDF:</label>
                <input type="file" name="file" id="file" required>


                <label for="semester">Select Semester:</label>
                <select name="semester" id="semester">
                    <?php
                    for ($i = 1; $i <= 8; $i++) {
                        echo "<option value='" . $i . "'>Semester " . $i . "</option>";
                    }
                    ?>
                </select>
                <label for="subject_id">Select Subject:</label>
                <select name="subject_id" id="subject_id">
                    <?php
                    mysqli_data_seek($subjectResult, 0); // Reset pointer for subject dropdown
                    while ($row = mysqli_fetch_assoc($subjectResult)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>

                <button type="submit" name="upload_question">Upload</button>
            </form>

            <form action="delete_question.php" method="post">
                <label for="delete_question">Delete Question PDF:</label>
                <input type="text" name="file_name" id="delete_question" placeholder="Enter file name" required>
                <button type="submit" name="delete_question">Delete</button>
            </form>


            <h2>View Uploaded Questions</h2>
            <form action="" method="get">
                <label for="view_semester">Select Semester:</label>
                <select name="view_semester" id="view_semester">
                    <option value="">Select a Semester</option>
                    <?php
                    // Populate semester dropdown
                    $semesterQuery = "SELECT DISTINCT semester FROM questions ORDER BY semester";
                    $semesterResult = mysqli_query($conn, $semesterQuery);
                    while ($row = mysqli_fetch_assoc($semesterResult)) {
                        $semesterValue = htmlspecialchars($row['semester']);
                        echo "<option value='$semesterValue'" . (isset($_GET['view_semester']) && $_GET['view_semester'] == $semesterValue ? ' selected' : '') . ">Semester $semesterValue</option>";
                    }
                    ?>
                </select>

                <label for="view_subject">Select Subject:</label>
                <select name="view_subject" id="view_subject">
                    <?php
                    if (isset($_GET['view_semester'])) {
                        $selectedSemester = intval($_GET['view_semester']);

                        // Fetch subjects based on the selected semester
                        $subjectQuery = "SELECT id, name FROM subjects WHERE semester_id = ?";
                        $stmt = mysqli_prepare($conn, $subjectQuery);
                        mysqli_stmt_bind_param($stmt, 'i', $selectedSemester);
                        mysqli_stmt_execute($stmt);
                        $subjectResult = mysqli_stmt_get_result($stmt);

                        while ($row = mysqli_fetch_assoc($subjectResult)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'" . (isset($_GET['view_subject']) && $_GET['view_subject'] == $row['id'] ? ' selected' : '') . ">" . htmlspecialchars($row['name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Select a Semester first</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="view_questions">View</button>
            </form>

            <?php
            if (isset($_GET['view_questions'])) {
                $selectedSemester = intval($_GET['view_semester']);
                $selectedSubject = intval($_GET['view_subject']);

                $fetchPDFQuery = "SELECT * FROM questions WHERE semester = ? AND subject_id = ?";
                $stmt = mysqli_prepare($conn, $fetchPDFQuery);
                mysqli_stmt_bind_param($stmt, 'ii', $selectedSemester, $selectedSubject);
                mysqli_stmt_execute($stmt);
                $pdfResult = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($pdfResult) > 0) {
                    echo "<ul>";
                    while ($pdfRow = mysqli_fetch_assoc($pdfResult)) {
                        echo "<li><a href='uploads/" . htmlspecialchars($pdfRow['file_name']) . "' target='_blank'>" . htmlspecialchars($pdfRow['file_name']) . "</a></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No PDFs found for the selected semester and subject.</p>";
                }
            }
            ?>
        </div>
    </div>

</body>

</html>