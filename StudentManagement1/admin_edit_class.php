<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $classID = $_POST['classID'];
    $className = $_POST['className'];

    $stmt = $conn->prepare("UPDATE Classes SET ClassName = ? WHERE ClassID = ?");
    if ($stmt->execute([$className, $classID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating class']);
    }
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>