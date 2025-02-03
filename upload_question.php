<?php
session_start();
require_once "signupdb.php";

if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit;
}

if (isset($_POST['upload_question'])) {
    $file = $_FILES['file'];
    $subject_id = $_POST['subject_id'];
    $semester = $_POST['semester'];

    $fileName = basename($file['name']);
    $targetDir = "uploads/";
    $targetFilePath = $targetDir . $fileName;

    // Upload file to server
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        // Insert file info into the database
        $sql = "INSERT INTO questions (file_name, subject_id, semester) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sii', $fileName, $subject_id, $semester);
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("File uploaded successfully.");</script>';
        } else {
            echo '<script>alert("Failed to insert file info into database.");</script>';
        }
    } else {
        echo '<script>alert("Failed to upload file.");</script>';
    }
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Notes Hub</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            background-color: #f0f0f0;
            border-radius: 4px;
        }

        button {
            background-color: #0b7691;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0b7691;
        }

        .back-button {
            background-color: 0b7691;
        }

        .back-button:hover {
            background-color: 0b7691;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Return to Admin Panel</h2>
        <br>
        <button onclick="goToCourseManagement()">Go back </button>
    </div>

    <script>
        function uploadPdf() {
            // Assume some upload process here
            setTimeout(function() {
                alert('PDF uploaded successfully!');
                document.getElementById('uploadForm').reset();
            }, 2000); // Simulate a 2-second upload delay
        }

        function goToCourseManagement() {
            // Redirect to course management page
            window.location.href = 'adminpanel.php';
        }
    </script>

</body>

</html>