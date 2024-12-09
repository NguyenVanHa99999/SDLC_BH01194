<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $className = $_POST['className'];

    $stmt = $conn->prepare("INSERT INTO Classes (ClassName) VALUES (?)");
    if ($stmt->execute([$className])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding class']);
    }
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>