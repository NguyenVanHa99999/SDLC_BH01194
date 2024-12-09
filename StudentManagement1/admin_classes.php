<?php
include 'db_connection.php';

// Truy vấn danh sách lớp học
$stmt = $conn->query("SELECT ClassID, ClassName FROM Classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Manage Classes</h3>
<!-- Nút "Add New Class" để mở modal -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClassModal">Add New Class</button>

<!-- Bảng danh sách lớp học -->
<table class="table table-bordered">
    <thead>
        <tr>
            <thead style="background-color: black; color: white;">
            <th>ID</th>
            <th>Class Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($classes as $class): ?>
            <tr>
                <td><?= $class['ClassID'] ?></td>
                <td><?= htmlspecialchars($class['ClassName']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm editClassBtn" data-id="<?= $class['ClassID'] ?>" data-name="<?= htmlspecialchars($class['ClassName']) ?>">Edit</button>
                    <button class="btn btn-danger btn-sm deleteClassBtn" data-id="<?= $class['ClassID'] ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Add Class -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addClassForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="className" class="form-label">Class Name</label>
                        <input type="text" name="className" id="className" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Class -->
<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editClassForm">
                <input type="hidden" name="classID" id="editClassID">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Edit Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editClassName" class="form-label">Class Name</label>
                        <input type="text" name="className" id="editClassName" class="form-control" required>
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
    // Thêm lớp học
    document.getElementById('addClassForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('admin_add_class.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Class added successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error adding class!');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Hiển thị modal Edit Class
    document.querySelectorAll('.editClassBtn').forEach(button => {
        button.addEventListener('click', () => {
            const classID = button.getAttribute('data-id');
            const className = button.getAttribute('data-name');

            document.getElementById('editClassID').value = classID;
            document.getElementById('editClassName').value = className;

            new bootstrap.Modal(document.getElementById('editClassModal')).show();
        });
    });

    // Cập nhật lớp học
    document.getElementById('editClassForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('admin_edit_class.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Class updated successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error updating class!');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Xóa lớp học
    document.querySelectorAll('.deleteClassBtn').forEach(button => {
        button.addEventListener('click', () => {
            const classID = button.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this class?')) {
                fetch(`admin_delete_class.php?id=${classID}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Class deleted successfully!');
                            location.reload();
                        } else {
                            alert(data.message || 'Error deleting class!');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>