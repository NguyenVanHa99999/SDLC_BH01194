<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectID = $_POST['subjectID'];
    $classID = $_POST['classID'];
    $teacherID = $_POST['teacherID'];
    $date = $_POST['date'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    $stmt = $conn->prepare("INSERT INTO Sessions (SubjectID, ClassID, TeacherID, Date, StartTime, EndTime, CreatedBy) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $createdBy = 1; // ID của admin
    if ($stmt->execute([$subjectID, $classID, $teacherID, $date, $startTime, $endTime, $createdBy])) {
        header('Location: index.php?page=manage_sessions');
        exit;
    } else {
        echo "Error adding session.";
    }
}
?>