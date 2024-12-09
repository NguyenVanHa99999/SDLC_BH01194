<?php
include 'db_connection.php';

// Lấy danh sách môn học và giáo viên
$subjects = $conn->query("
    SELECT s.SubjectID, s.SubjectName, 
           IFNULL(CONCAT(u.FirstName, ' ', u.LastName), 'Unknown Teacher') AS TeacherName
    FROM Subjects s
    LEFT JOIN Users u ON s.TeacherID = u.UserID
")->fetchAll(PDO::FETCH_ASSOC);

$teachers = $conn->query("
    SELECT UserID, CONCAT(FirstName, ' ', LastName) AS FullName 
    FROM Users 
    WHERE RoleID = 2
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Subjects</h3>
<!-- Nút "Add New Subject" để mở modal -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add New Subject</button>

<!-- Bảng danh sách môn học -->
<table class="table table-bordered">
    <thead>
        <tr>
            <thead style="background-color: black; color: white;">
            <th>ID</th>
            <th>Subject Name</th>
            <th>Teacher</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?= $subject['SubjectID'] ?></td>
                <td><?= htmlspecialchars($subject['SubjectName']) ?></td>
                <td><?= htmlspecialchars($subject['TeacherName']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-subject"
                            data-id="<?= $subject['SubjectID'] ?>"
                            data-name="<?= htmlspecialchars($subject['SubjectName']) ?>"
                            data-teacher="<?= $subject['TeacherName'] ?>"
                            data-bs-toggle="modal" data-bs-target="#editSubjectModal">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm delete-subject" data-id="<?= $subject['SubjectID'] ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Thêm Môn Học -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSubjectForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subjectName" class="form-label">Subject Name</label>
                        <input type="text" name="subjectName" id="subjectName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherID" class="form-label">Teacher</label>
                        <select name="teacherID" id="teacherID" class="form-control" required>
                            <option value="">Select a Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['UserID'] ?>"><?= htmlspecialchars($teacher['FullName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Môn Học -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSubjectForm">
                <input type="hidden" name="subjectID" id="editSubjectID">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editSubjectName" class="form-label">Subject Name</label>
                        <input type="text" name="subjectName" id="editSubjectName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTeacherID" class="form-label">Teacher</label>
                        <select name="teacherID" id="editTeacherID" class="form-control" required>
                            <option value="">Select a Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['UserID'] ?>"><?= htmlspecialchars($teacher['FullName']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
    // Thêm môn học
    document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('add_subject.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Subject added successfully!');
                location.reload();
            } else {
                alert(data.message || 'Failed to add subject!');
            }
        });
    });

    // Sửa môn học
    document.querySelectorAll('.edit-subject').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('editSubjectID').value = this.dataset.id;
            document.getElementById('editSubjectName').value = this.dataset.name;
            document.getElementById('editTeacherID').value = this.dataset.teacher;
        });
    });

    document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('edit_subject.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Subject updated successfully!');
                location.reload();
            } else {
                alert(data.message || 'Failed to update subject!');
            }
        });
    });

    // Xóa môn học
    document.querySelectorAll('.delete-subject').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this subject?')) {
                fetch('delete_subject.php?id=' + this.dataset.id, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Subject deleted successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to delete subject!');
                    }
                });
            }
        });
    });
</script>