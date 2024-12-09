<?php
include 'db_connection.php';

// Kiểm tra yêu cầu GET và xóa người dùng
if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
    if ($stmt->execute([$userID])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting user']);
    }
    exit;
}

// Trả về lỗi nếu truy cập sai phương thức
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>