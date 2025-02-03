<?php
require_once "signupdb.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid semester ID.";
    exit();
}

$semesterId = intval($_GET['id']);

$sql = "SELECT semesters.name AS semester_name, subjects.id AS subject_id, subjects.name AS subject_name
        FROM semesters
        JOIN subjects ON semesters.id = subjects.semester_id
        WHERE semesters.id = $semesterId
        ORDER BY subjects.name";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching subjects: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "No subjects found for this semester.";
    exit();
}

$semesterName = $row['semester_name'];
?>
<!DOCTYPE html>
<html lang="en">
<title>BCA Notes Hub</title>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($semesterName); ?> BCA Notes Hub</title>
    <link rel="stylesheet" href="css/sem.css" />
</head>

<body>
    <div class="sub-header">
        <h2><?php echo htmlspecialchars($semesterName); ?> Subjects</h2>
    </div>
    <main>
        <ul class="subject-list">+
            <?php
            do {
                echo '<li><a class="btn" href="view_subject_pdfs.php?subject_id=' . htmlspecialchars($row['subject_id']) . '">' . htmlspecialchars($row['subject_name']) . '</a></li>';
            } while ($row = mysqli_fetch_assoc($result));
            ?>
        </ul>
        <a class="btn" href="semesters.php">Back to Semesters</a>
    </main>
</body>

</html>