<?php
include 'db_connection.php';

// Truy vấn danh sách các sessions
$query = "
    SELECT s.SessionID, subj.SubjectName, c.ClassName, u.FirstName, u.LastName, s.Date, s.StartTime, s.EndTime 
    FROM Sessions s
    JOIN Subjects subj ON s.SubjectID = subj.SubjectID
    JOIN Classes c ON s.ClassID = c.ClassID
    JOIN Users u ON s.TeacherID = u.UserID
";
$sessions = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Truy vấn danh sách các môn học, lớp học và giáo viên
$subjects = $conn->query("SELECT * FROM Subjects")->fetchAll(PDO::FETCH_ASSOC);
$classes = $conn->query("SELECT * FROM Classes")->fetchAll(PDO::FETCH_ASSOC);
$teachers = $conn->query("SELECT * FROM Users WHERE RoleID = 2")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Sessions</h3>
<!-- Nút "Add New Session" -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSessionModal">Add New Session</button>

<!-- Bảng danh sách buổi học -->
<table class="table table-bordered">
    <thead>
        <tr>
            <thead style="background-color: black; color: white;">
            <th>ID</th>
            <th>Subject</th>
            <th>Class</th>
            <th>Teacher</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sessions as $session): ?>
            <tr>
                <td><?= $session['SessionID'] ?></td>
                <td><?= htmlspecialchars($session['SubjectName']) ?></td>
                <td><?= htmlspecialchars($session['ClassName']) ?></td>
                <td><?= htmlspecialchars($session['FirstName'] . " " . $session['LastName']) ?></td>
                <td><?= htmlspecialchars($session['Date']) ?></td>
                <td><?= htmlspecialchars($session['StartTime'] . " - " . $session['EndTime']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-session"
                        data-id="<?= $session['SessionID'] ?>"
                        data-subject="<?= $session['SubjectName'] ?>"
                        data-class="<?= $session['ClassName'] ?>"
                        data-teacher="<?= $session['FirstName'] . " " . $session['LastName'] ?>"
                        data-date="<?= $session['Date'] ?>"
                        data-start="<?= $session['StartTime'] ?>"
                        data-end="<?= $session['EndTime'] ?>"
                        data-bs-toggle="modal" data-bs-target="#editSessionModal">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-session" data-id="<?= $session['SessionID'] ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Thêm buổi học -->
<div class="modal fade" id="addSessionModal" tabindex="-1" aria-labelledby="addSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="add_session.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSessionModalLabel">Add New Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subjectID" class="form-label">Subject</label>
                        <select name="subjectID" id="subjectID" class="form-select" required>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['SubjectID'] ?>"><?= htmlspecialchars($subject['SubjectName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="classID" class="form-label">Class</label>
                        <select name="classID" id="classID" class="form-select" required>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ClassID'] ?>"><?= htmlspecialchars($class['ClassName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="teacherID" class="form-label">Teacher</label>
                        <select name="teacherID" id="teacherID" class="form-select" required>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['UserID'] ?>">
                                    <?= htmlspecialchars($teacher['FirstName'] . " " . $teacher['LastName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Start Time</label>
                        <input type="time" name="startTime" id="startTime" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">End Time</label>
                        <input type="time" name="endTime" id="endTime" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Session</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa buổi học -->
<div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="edit_session.php" method="POST">
                <input type="hidden" name="sessionID" id="editSessionID">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSessionModalLabel">Edit Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editSubjectID" class="form-label">Subject</label>
                        <select name="subjectID" id="editSubjectID" class="form-select" required>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['SubjectID'] ?>"><?= htmlspecialchars($subject['SubjectName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editClassID" class="form-label">Class</label>
                        <select name="classID" id="editClassID" class="form-select" required>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['ClassID'] ?>"><?= htmlspecialchars($class['ClassName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editTeacherID" class="form-label">Teacher</label>
                        <select name="teacherID" id="editTeacherID" class="form-select" required>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['UserID'] ?>">
                                    <?= htmlspecialchars($teacher['FirstName'] . " " . $teacher['LastName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editDate" class="form-label">Date</label>
                        <input type="date" name="date" id="editDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editStartTime" class="form-label">Start Time</label>
                        <input type="time" name="startTime" id="editStartTime" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEndTime" class="form-label">End Time</label>
                        <input type="time" name="endTime" id="editEndTime" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Xử lý nút Edit
    document.querySelectorAll('.edit-session').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('editSessionID').value = button.getAttribute('data-id');
            document.getElementById('editSubjectID').value = button.getAttribute('data-subject');
            document.getElementById('editClassID').value = button.getAttribute('data-class');
            document.getElementById('editTeacherID').value = button.getAttribute('data-teacher');
            document.getElementById('editDate').value = button.getAttribute('data-date');
            document.getElementById('editStartTime').value = button.getAttribute('data-start');
            document.getElementById('editEndTime').value = button.getAttribute('data-end');
        });
    });

    // Xử lý nút Delete
    document.querySelectorAll('.delete-session').forEach(button => {
        button.addEventListener('click', () => {
            const sessionID = button.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this session?')) {
                fetch(`delete_session.php?id=${sessionID}`, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Session deleted successfully!');
                            location.reload();
                        } else {
                            alert('Failed to delete session.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>