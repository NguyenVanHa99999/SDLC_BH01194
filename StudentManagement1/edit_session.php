<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = $_POST['sessionID'];
    $subjectID = $_POST['subjectID'];
    $classID = $_POST['classID'];
    $teacherID = $_POST['teacherID'];
    $date = $_POST['date'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    $stmt = $conn->prepare("UPDATE Sessions SET SubjectID = ?, ClassID = ?, TeacherID = ?, Date = ?, StartTime = ?, EndTime = ? WHERE SessionID = ?");
    if ($stmt->execute([$subjectID, $classID, $teacherID, $date, $startTime, $endTime, $sessionID])) {
        header('Location: index.php?page=manage_sessions');
        exit;
    } else {
        echo "Error updating session.";
    }
}
?>