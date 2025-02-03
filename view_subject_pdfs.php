<?php
require_once "signupdb.php";

if (!isset($_GET['subject_id']) || !is_numeric($_GET['subject_id'])) {
    echo "Invalid subject ID.";
    exit();
}

$subjectId = intval($_GET['subject_id']);

// SQL query to fetch PDF file names for the given subject ID
$sql = "SELECT notes.file_name 
        FROM notes
        JOIN subject_pdfs ON notes.id = subject_pdfs.upload_id 
        WHERE subject_pdfs.subject_id = $subjectId 
        ORDER BY notes.id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching PDFs: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Notes Hub</title>
    <link rel="stylesheet" href="css/sem.css">


</head>

<body>
    <div class="sub-header">
        <h2>Available PDFs</h2>
    </div>
    <main>
        <ul class="pdf-list">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $fileName = $row['file_name'];
                $filePath = __DIR__ . '/uploads/' . $fileName;

                // Check if the file exists and is readable
                if (is_readable($filePath)) {
                    $relativePath = 'uploads/' . rawurlencode($fileName);
                    echo '<li><a class="btn" href="' . $relativePath . '" target="_blank">' . htmlspecialchars($fileName) . '</a></li>';
                } else {
                    echo '<li>' . htmlspecialchars($fileName) . ' (File not accessible)</li>';
                }
            }
            ?>
        </ul>
        <a class="btn" href="semesters.php">Back to Semesters</a>
    </main>
</body>

</html>