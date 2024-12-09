<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectID = $_POST['subjectID'];
    $subjectName = $_POST['subjectName'];
    $teacherID = $_POST['teacherID'];

    $stmt = $conn->prepare("UPDATE Subjects SET SubjectName = ?, TeacherID = ? WHERE SubjectID = ?");
    if ($stmt->execute([$subjectName, $teacherID, $subjectID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update subject']);
    }
}