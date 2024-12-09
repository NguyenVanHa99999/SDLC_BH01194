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

// Truy vấn danh sách môn học mà sinh viên đã đăng ký
$sql = "
    SELECT 
        sub.SubjectName,
        c.ClassName,
        CONCAT(u.FirstName, ' ', u.LastName) AS TeacherName,
        s.StartTime,
        s.EndTime
    FROM ClassStudents cs
    INNER JOIN Classes c ON cs.ClassID = c.ClassID
    INNER JOIN Subjects sub ON c.ClassID = sub.SubjectID
    LEFT JOIN Users u ON sub.TeacherID = u.UserID
    LEFT JOIN Sessions s ON s.ClassID = c.ClassID AND s.SubjectID = sub.SubjectID
    WHERE cs.UserID = $userId
    ORDER BY c.ClassName, sub.SubjectName
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách môn học</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">List of registered subjects</h1>
        <?php if ($result && $result->num_rows > 0) { ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['SubjectName']; ?></td>
                            <td><?php echo $row['ClassName']; ?></td>
                            <td><?php echo $row['TeacherName']; ?></td>
                            <td><?php echo $row['StartTime']; ?></td>
                            <td><?php echo $row['EndTime']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center text-danger">No subjects found.</p>
        <?php } ?>
    </div>
    <!-- Liên kết Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
