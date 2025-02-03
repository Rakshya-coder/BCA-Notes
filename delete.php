<?php
session_start();
require_once "signupdb.php";

if (!isset($_SESSION['AdminloginId'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['delete']) && !empty($_POST['file_name'])) {
    $fileName = basename($_POST['file_name']);
    $filePath = "uploads/" . $fileName;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // First, delete from the subject_pdfs table
            $sql1 = "DELETE FROM subject_pdfs WHERE upload_id IN (SELECT id FROM notes WHERE file_name = '$fileName')";
            if (mysqli_query($conn, $sql1)) {
                // Then, delete from the notes table
                $sql2 = "DELETE FROM notes WHERE file_name = '$fileName'";
                if (mysqli_query($conn, $sql2)) {
                    $message = "The file " . htmlspecialchars($fileName) . " has been deleted.";
                    echo "<script>alert('" . $message . "');</script>";
                    echo "<script>window.location.href = 'adminpanel.php';</script>";
                    exit();
                } else {
                    $message = "Error: Could not delete the file record from the notes table.";
                    echo "<script>alert('" . $message . "');</script>";
                }
            } else {
                $message = "Error: Could not delete the file record from the subject_pdfs table.";
                echo "<script>alert('" . $message . "');</script>";
            }
        } else {
            $message = "Error deleting the file from the filesystem.";
            echo "<script>alert('" . $message . "');</script>";
        }
    } else {
        $message = "File does not exist.";
        echo "<script>alert('" . $message . "');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
}
