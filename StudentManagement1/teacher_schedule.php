<?php
session_start();
include 'db_connection.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['RoleID']) || $_SESSION['RoleID'] != 2) {
    header('Location: login.php');
    exit;
}

$teacherID = $_SESSION['UserID']; // ID của giáo viên từ session

// Truy vấn danh sách các buổi dạy của giáo viên
$query = "
    SELECT s.SessionID, sub.SubjectName, c.ClassName, s.Date, s.StartTime, s.EndTime
    FROM Sessions s
    INNER JOIN Subjects sub ON s.SubjectID = sub.SubjectID
    INNER JOIN Classes c ON s.ClassID = c.ClassID
    WHERE s.TeacherID = ?
    ORDER BY s.Date, s.StartTime";

$stmt = $conn->prepare($query);
$stmt->execute([$teacherID]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teaching Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
        }
        .container {
            margin-top: 20px;
        }
        .table {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center mb-4">Teaching Schedule</h3>
        <?php if (count($sessions) > 0): ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <thead style="background-color: black; color: white;">
                        <th>Session ID</th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td><?= htmlspecialchars($session['SessionID']) ?></td>
                            <td><?= htmlspecialchars($session['SubjectName']) ?></td>
                            <td><?= htmlspecialchars($session['ClassName']) ?></td>
                            <td><?= htmlspecialchars($session['Date']) ?></td>
                            <td><?= htmlspecialchars($session['StartTime']) ?></td>
                            <td><?= htmlspecialchars($session['EndTime']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No teaching sessions found for your schedule.
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>