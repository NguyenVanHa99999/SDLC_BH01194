<?php
include 'db_connection.php';

// Lấy danh sách tất cả các lớp học kèm theo khung giờ và tên môn học
$allClasses = $conn->query("
    SELECT 
        c.ClassID, 
        c.ClassName, 
        s.StartTime, 
        s.EndTime, 
        sub.SubjectName
    FROM Classes c
    LEFT JOIN Sessions s ON c.ClassID = s.ClassID
    LEFT JOIN Subjects sub ON s.SubjectID = sub.SubjectID
")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách các lớp mà sinh viên đã đăng ký
$studentID = $_SESSION['UserID'];
$registeredClasses = $conn->prepare("
    SELECT ClassID FROM ClassStudents WHERE UserID = ?
");
$registeredClasses->execute([$studentID]);
$registeredClasses = $registeredClasses->fetchAll(PDO::FETCH_COLUMN);

// Lọc danh sách các lớp chưa được đăng ký
$availableClasses = array_filter($allClasses, function($class) use ($registeredClasses) {
    return !in_array($class['ClassID'], $registeredClasses);
});
?>

<h3>Register for Classes</h3>

<!-- Danh sách các lớp chưa đăng ký -->
<h5>Available Classes</h5>
<table class="table table-bordered">
    <thead>
        <tr style="background-color: black; color: white;">
            <th>Class Name</th>
            <th>Subject Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($availableClasses as $class): ?>
            <tr>
                <td><?= htmlspecialchars($class['ClassName']); ?></td>
                <td><?= htmlspecialchars($class['SubjectName']); ?></td>
                <td><?= htmlspecialchars($class['StartTime']); ?></td>
                <td><?= htmlspecialchars($class['EndTime']); ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="registerClass(<?= $class['ClassID']; ?>)">Register</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
// Đăng ký lớp học
function registerClass(classID) {
    fetch('register_class.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ classID })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Registered successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}
</script>