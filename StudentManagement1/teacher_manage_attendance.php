<?php
include 'db_connection.php';

// Lấy danh sách sinh viên theo Session
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];
    $stmt = $conn->prepare("
        SELECT cs.UserID, u.FirstName, u.LastName, a.StatusID, a.Remarks
        FROM ClassStudents cs
        JOIN Users u ON cs.UserID = u.UserID
        LEFT JOIN Attendance a ON cs.UserID = a.UserID AND a.SessionID = ?
        WHERE cs.ClassID = (SELECT ClassID FROM Sessions WHERE SessionID = ?)
    ");
    $stmt->execute([$sessionID, $sessionID]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($students);
    exit;
}

// Thêm hoặc sửa điểm danh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = $_POST['sessionID'];
    $userID = $_POST['userID'];
    $statusID = $_POST['statusID'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("SELECT AttendanceID FROM Attendance WHERE SessionID = ? AND UserID = ?");
    $stmt->execute([$sessionID, $userID]);
    $attendance = $stmt->fetch();

    if ($attendance) {
        $updateStmt = $conn->prepare("UPDATE Attendance SET StatusID = ?, Remarks = ? WHERE AttendanceID = ?");
        $updateStmt->execute([$statusID, $remarks, $attendance['AttendanceID']]);
        echo json_encode(['status' => 'updated']);
    } else {
        $insertStmt = $conn->prepare("INSERT INTO Attendance (SessionID, UserID, StatusID, Remarks) VALUES (?, ?, ?, ?)");
        $insertStmt->execute([$sessionID, $userID, $statusID, $remarks]);
        echo json_encode(['status' => 'inserted']);
    }
    exit;
}
?>