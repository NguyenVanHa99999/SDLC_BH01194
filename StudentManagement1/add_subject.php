<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectName = $_POST['subjectName'];
    $teacherID = $_POST['teacherID'];

    $stmt = $conn->prepare("INSERT INTO Subjects (SubjectName, TeacherID) VALUES (?, ?)");
    if ($stmt->execute([$subjectName, $teacherID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add subject']);
    }
}