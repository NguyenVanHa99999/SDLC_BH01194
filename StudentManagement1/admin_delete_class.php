<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $classID = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Classes WHERE ClassID = ?");
    if ($stmt->execute([$classID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting class']);
    }
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>