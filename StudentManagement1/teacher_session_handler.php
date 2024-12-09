<?php
include 'db_connection.php';

if (isset($_GET['sessionID'])) {
    $sessionID = $_GET['sessionID'];

    // Lấy thông tin buổi học
    $querySession = "
        SELECT s.SessionID, sub.SubjectName, c.ClassName, s.Date, s.StartTime, s.EndTime
        FROM Sessions s
        INNER JOIN Subjects sub ON s.SubjectID = sub.SubjectID
        INNER JOIN Classes c ON s.ClassID = c.ClassID
        WHERE s.SessionID = ?";
    $stmtSession = $conn->prepare($querySession);
    $stmtSession->execute([$sessionID]);
    $session = $stmtSession->fetch(PDO::FETCH_ASSOC);

    // Lấy danh sách sinh viên kèm trường Phone
    $queryStudents = "
        SELECT cs.UserID, u.FirstName, u.LastName, u.Phone, a.StatusID, a.Remarks
        FROM ClassStudents cs
        INNER JOIN Users u ON cs.UserID = u.UserID
        LEFT JOIN Attendance a ON cs.UserID = a.UserID AND a.SessionID = ?
        WHERE cs.ClassID = (SELECT ClassID FROM Sessions WHERE SessionID = ?)";
    $stmtStudents = $conn->prepare($queryStudents);
    $stmtStudents->execute([$sessionID, $sessionID]);
    $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

    // Trả dữ liệu JSON
    echo json_encode(['session' => $session, 'students' => $students]);
}
?>