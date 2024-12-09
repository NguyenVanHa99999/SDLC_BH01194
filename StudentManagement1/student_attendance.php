<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost"; // Địa chỉ server
$username = "root"; // Tên đăng nhập
$password = ""; // Mật khẩu
$database = "StudentManagement"; // Tên cơ sở dữ liệu

// Kết nối database
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// ID sinh viên cố định, thay bằng ID thật khi cần
$userId = 39; // Thay bằng UserID sinh viên cần xem

// Truy vấn trạng thái điểm danh
$sql = "
    SELECT 
        sub.SubjectName,
        c.ClassName,
        s.Date AS SessionDate,
        a.StatusID,
        a.Remarks,
        CONCAT(u.FirstName, ' ', u.LastName) AS TeacherName
    FROM Attendance a
    INNER JOIN Sessions s ON a.SessionID = s.SessionID
    INNER JOIN Classes c ON s.ClassID = c.ClassID
    INNER JOIN Subjects sub ON s.SubjectID = sub.SubjectID
    LEFT JOIN Users u ON sub.TeacherID = u.UserID
    WHERE a.UserID = $userId
    ORDER BY s.Date DESC, c.ClassName, sub.SubjectName
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Status</title>
    <!-- Liên kết Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        table {
            background-color: #ffffff;
            border-radius: 5px;
        }
        h1 {
            color: #343a40;
        }
        .status-present {
            color: green;
            font-weight: bold;
        }
        .status-absent {
            color: red;
            font-weight: bold;
        }
        .status-late {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Attendance Status</h1>
        <?php if ($result && $result->num_rows > 0) { ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <thead style="background-color: black; color: white;">
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Session Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { 
                        $statusText = "";
                        $statusClass = "";
                        switch ($row['StatusID']) {
                            case "Present":
                                $statusText = "Present";
                                $statusClass = "status-present";
                                break;
                            case "Absent":
                                $statusText = "Absent";
                                $statusClass = "status-absent";
                                break;
                            case "Late":
                                $statusText = "Late";
                                $statusClass = "status-late";
                                break;
                        }
                    ?>
                        <tr>
                            <td><?php echo $row['SubjectName']; ?></td>
                            <td><?php echo $row['ClassName']; ?></td>
                            <td><?php echo $row['SessionDate']; ?></td>
                            <td><?php echo $row['StatusID']; ?></td>
                            <td><?php echo $row['Remarks']; ?></td>
                            <td><?php echo $row['TeacherName']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center text-danger">No attendance data found.</p>
        <?php } ?>
    </div>
    <!-- Liên kết Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>