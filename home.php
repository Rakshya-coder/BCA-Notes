<?php
session_start();
require_once "signupdb.php";

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Fetch all semesters and their subjects
$sql = "SELECT semesters.id AS semester_id, semesters.name AS semester_name, GROUP_CONCAT(subjects.name SEPARATOR ', ') AS subjects
        FROM semesters
        LEFT JOIN subjects ON semesters.id = subjects.semester_id
        GROUP BY semesters.id, semesters.name
        ORDER BY semesters.id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching semesters: " . mysqli_error($conn));
}

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $userId = $_SESSION['userId'];

    $sql_insert_comment = "INSERT INTO comments (user_id, comment, created_at) VALUES ('$userId', '$comment', NOW())";
    mysqli_query($conn, $sql_insert_comment);
    header("Location: home.php");
    exit();
}

// Handle comment editing
if (isset($_POST['edit_comment'])) {
    $comment_id = $_POST['comment_id'];
    $new_comment = mysqli_real_escape_string($conn, $_POST['new_comment']);

    $sql_update_comment = "UPDATE comments SET comment='$new_comment', edited=1 WHERE id='$comment_id'";
    mysqli_query($conn, $sql_update_comment);
    header("Location: home.php");
    exit();
}

// Fetch comments including admin replies
$sql_comments = "SELECT comments.id, comments.comment, comments.created_at, comments.edited, comments.admin_reply, users.username, comments.user_id
                 FROM comments 
                 LEFT JOIN users ON comments.user_id = users.id 
                 ORDER BY comments.created_at DESC";
$result_comments = mysqli_query($conn, $sql_comments);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Notes Hub</title>
    <link rel="stylesheet" href="css/stylee.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
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

    <section class="backgroundMain">
        <div class="first-section">
            <div class="home-text">
                <p class="text-big">Elevate Your Learning <br> Potential <br> With Our Resources,</p>
                <p class="text-small">Comprehensive study guides to ace your BCA exams</p>
            </div>
            <div class="home-img">
                <img src="images/elearn.png" alt="picture">
            </div>
        </div>
    </section>

    <main>
        <section class="semesters">
            <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="semester">
                        <h2><?php echo htmlspecialchars($row['semester_name']); ?></h2>
                        <p>This semester course includes <?php echo htmlspecialchars($row['subjects']); ?>.</p>
                        <a href="semester.php?id=<?php echo htmlspecialchars($row['semester_id']); ?>">View Course Details</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No semesters found.</p>
            <?php } ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-left">
                <img src="images/projlogoo.png" alt="logo">
                <div class="footer-content">
                    <p>BCA Notes Hub is an educational website that strives to offer students
                        a comprehensive set of reference notes, syllabi, and past questions.
                    </p>
                </div>
            </div>
            <div class="footer-left">
                <p class="footer-head">Quick Links
                <p>
                <ul>
                    <li><a href="semesters.php">Notes</a></li>
                    <li><a href="viewquestion_pdfs.php">Questions</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-right">
                <div class="comment-form">
                    <form action="home.php" method="post">
                        <textarea name="comment" placeholder="Leave your feedback..." required></textarea>
                        <button type="submit" name="submit_comment">Post</button>
                    </form>
                </div>
                <div class="comments">
                    <?php if (mysqli_num_rows($result_comments) > 0) { ?>
                        <?php while ($row_comments = mysqli_fetch_assoc($result_comments)) { ?>
                            <div class="comment">
                                <div class="comment-username"><?php echo htmlspecialchars($row_comments['username']); ?></div>
                                <div class="comment-date"><?php echo htmlspecialchars($row_comments['created_at']); ?></div>
                                <div class="comment-text"><?php echo htmlspecialchars($row_comments['comment']); ?></div>
                                <?php if ($row_comments['edited']) { ?>
                                    <div class="comment-edited">(Edited)</div>
                                <?php } ?>
                                <?php if ($row_comments['admin_reply']) { ?>
                                    <div class="admin-reply"><strong>Admin Reply:</strong> <?php echo htmlspecialchars($row_comments['admin_reply']); ?></div>
                                <?php } ?>
                                <?php if ($_SESSION['userId'] == $row_comments['user_id']) { ?>
                                    <button class="edit-but" onclick="document.getElementById('editForm<?php echo $row_comments['id']; ?>').style.display='block'">Edit</button>
                                    <div id="editForm<?php echo $row_comments['id']; ?>" class="edit-form" style="display:none;">
                                        <form action="home.php" method="post">
                                            <textarea name="new_comment" required><?php echo htmlspecialchars($row_comments['comment']); ?></textarea>
                                            <input type="hidden" name="comment_id" value="<?php echo $row_comments['id']; ?>">
                                            <button type="submit" name="edit_comment">Update</button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No comments yet.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </footer>
    <script src="javascript/resp.js"></script>
</body>

</html>