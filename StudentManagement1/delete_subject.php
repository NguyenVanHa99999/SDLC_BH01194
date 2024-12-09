<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $subjectID = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Subjects WHERE SubjectID = ?");
    if ($stmt->execute([$subjectID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete subject']);
    }
}