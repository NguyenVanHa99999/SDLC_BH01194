<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $sessionID = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM Sessions WHERE SessionID = ?");
    if ($stmt->execute([$sessionID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>