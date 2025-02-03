<?php
session_start();
require_once "signupdb.php";

if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit;
}

if (isset($_POST['delete_question'])) {
    $file_name = $_POST['file_name'];

    // Delete file from server
    $filePath = "uploads/" . $file_name;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete file info from the database
    $sql = "DELETE FROM questions WHERE file_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $file_name);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('File deleted successfully');</script>";
        echo "<script>window.location.href = 'adminpanel.php';</script>";
    } else {
        echo "<script>alert('Failed to delete file info from database');</script>";
        echo "<script>window.location.href = 'adminpanel.php';</script>";
    }
}
