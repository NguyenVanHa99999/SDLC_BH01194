<?php
include 'db_connection.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$classID = $data['classID'];
$studentID = $_SESSION['UserID'];

// Kiểm tra xem sinh viên đã đăng ký lớp này chưa
$query = $conn->prepare("SELECT * FROM ClassStudents WHERE ClassID = ? AND UserID = ?");
$query->execute([$classID, $studentID]);

if ($query->rowCount() > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Already registered']);
    exit;
}

// Thêm vào bảng ClassStudents
$stmt = $conn->prepare("INSERT INTO ClassStudents (ClassID, UserID) VALUES (?, ?)");
if ($stmt->execute([$classID, $studentID])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to register']);
}
?>