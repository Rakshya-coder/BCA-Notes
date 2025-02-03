<?php
session_start();
require_once "signupdb.php";

if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit();
}

if (isset($_POST['upload'])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . $fileName;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $subjectId = intval($_POST['subject_id']);

    // Check if file is a PDF
    if ($fileType != "pdf") {
        echo "<script>alert('Only PDF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check if directory exists and is writable
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!is_writable($targetDir)) {
        echo "<script>alert('The uploads directory is not writable.');</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Your file was not uploaded.');</script>";
        echo "<script>window.location.href = 'adminpanel.php';</script>";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Insert file info into database
            $sql = "INSERT INTO notes (file_name) VALUES ('$fileName')";
            if (mysqli_query($conn, $sql)) {
                $uploadId = mysqli_insert_id($conn);
                $subjectPdfSql = "INSERT INTO subject_pdfs (upload_id, subject_id) VALUES ($uploadId, $subjectId)";
                if (mysqli_query($conn, $subjectPdfSql)) {
                    echo "<script>alert('The file " . htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8') . " has been uploaded and saved in the database.');</script>";
                    echo "<script>window.location.href = 'adminpanel.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('There was an error while uploading your file.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PDF</title>
    <link rel="stylesheet" href="css/sem.css">
</head>

<body>
    <div class="sub-header">
        <h2>Upload PDF</h2>
    </div>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="subject_id">Select Subject:</label>
            <select name="subject_id" id="subject_id">
                <?php
                // Fetch subjects from database
                $sql = "SELECT id, name FROM subjects";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                }
                ?>
            </select>
            <br><br>
            <label for="file">Choose PDF File:</label>
            <input type="file" name="file" id="file">
            <br><br>
            <button type="submit" name="upload" class="uploadbtn">Upload File</button>
        </form>
    </main>
</body>

</html>