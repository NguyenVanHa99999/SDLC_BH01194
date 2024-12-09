<?php
session_start();
include 'db_connection.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['RoleID']) || $_SESSION['RoleID'] != 2) {
    header('Location: login.php');
    exit;
}

$teacherID = $_SESSION['UserID']; // ID của giáo viên từ session

// Lấy danh sách các buổi dạy của giáo viên
$querySessions = "
    SELECT s.SessionID, sub.SubjectName, c.ClassName, s.Date, s.StartTime, s.EndTime
    FROM Sessions s
    INNER JOIN Subjects sub ON s.SubjectID = sub.SubjectID
    INNER JOIN Classes c ON s.ClassID = c.ClassID
    WHERE s.TeacherID = ?
    ORDER BY s.Date, s.StartTime";

$stmtSessions = $conn->prepare($querySessions);
$stmtSessions->execute([$teacherID]);
$sessions = $stmtSessions->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
        }

        .container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center mb-4">Session Details</h3>

        <!-- Dropdown chọn buổi học -->
        <div class="mb-3">
            <label for="sessionSelect" class="form-label">Select a Session:</label>
            <select id="sessionSelect" class="form-select">
                <option value="">-- Select Session --</option>
                <?php foreach ($sessions as $session): ?>
                    <option value="<?= $session['SessionID'] ?>">
                        <?= htmlspecialchars($session['Date']) ?> - <?= htmlspecialchars($session['ClassName']) ?>
                        (<?= htmlspecialchars($session['StartTime']) ?> - <?= htmlspecialchars($session['EndTime']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Bảng hiển thị danh sách sinh viên -->
        <div id="studentTableContainer" style="display: none;">
            <h5 id="sessionTitle"></h5>
            <table class="table table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <thead style="background-color: black; color: white;">
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <!-- Nội dung sẽ được tải qua AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('sessionSelect').addEventListener('change', function() {
            const sessionID = this.value;

            if (!sessionID) {
                document.getElementById('studentTableContainer').style.display = 'none';
                return;
            }

            fetch(`teacher_session_handler.php?sessionID=${sessionID}`)
                .then(response => response.json())
                .then(data => {
                    const studentTableBody = document.getElementById('studentTableBody');
                    studentTableBody.innerHTML = '';

                    if (data.students.length > 0) {
                        document.getElementById('studentTableContainer').style.display = 'block';
                        document.getElementById('sessionTitle').innerText = `Session: ${data.session.SubjectName} (${data.session.ClassName})`;

                        data.students.forEach(student => {
                            const row = `
                                <tr>
                                    <td>${student.UserID}</td>
                                    <td>${student.FirstName} ${student.LastName}</td>
                                     <td>${student.Phone || 'N/A'}</td>
                                    <td>${student.StatusID || 'N/A'}</td>
                                    <td>${student.Remarks || 'N/A'}</td>
                                </tr>
                            `;
                            studentTableBody.innerHTML += row;
                        });
                    } else {
                        document.getElementById('studentTableContainer').style.display = 'none';
                        alert('No students found for this session.');
                    }
                });
        });

        function getStatusText(statusID) {
            if (statusID === '1') return 'Present';
            if (statusID === '2') return 'Absent';
            if (statusID === '3') return 'Late';
            return '';
        }
        
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>