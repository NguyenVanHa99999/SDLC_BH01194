<?php
include 'db_connection.php';

// Truy vấn danh sách người dùng
$stmt = $conn->query("SELECT UserID, Username, Email, Phone, RoleID FROM Users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý thêm người dùng qua AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone']; // Thêm trường Phone
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $roleID = $_POST['roleID'];

    $stmt = $conn->prepare("INSERT INTO Users (Username, Email, Phone, Password, RoleID) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $phone, $password, $roleID])) {
        echo json_encode(['status' => 'success']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding user']);
        exit;
    }
}
?>

<h3>Manage Users</h3>
<!-- Nút "Add New User" -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>

<!-- Bảng danh sách người dùng -->
<table class="table table-bordered">
    <thead>
        <tr>
            <thead style="background-color: black; color: white;">
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['UserID'] ?></td>
                <td><?= htmlspecialchars($user['Username']) ?></td>
                <td><?= htmlspecialchars($user['Email']) ?></td>
                <td><?= htmlspecialchars($user['Phone']) ?></td>
                <td>
                    <?= $user['RoleID'] == 1 ? 'Admin' : ($user['RoleID'] == 2 ? 'Teacher' : 'Student') ?>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm editUserBtn" 
                            data-id="<?= $user['UserID'] ?>" 
                            data-username="<?= htmlspecialchars($user['Username']) ?>" 
                            data-email="<?= htmlspecialchars($user['Email']) ?>" 
                            data-phone="<?= htmlspecialchars($user['Phone']) ?>" 
                            data-roleid="<?= $user['RoleID'] ?>">Edit</button>
                    <button class="btn btn-danger btn-sm deleteUserBtn" data-id="<?= $user['UserID'] ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Thêm User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addUserForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="roleID" class="form-label">Role</label>
                        <select name="roleID" id="roleID" class="form-control" required>
                            <option value="1">Admin</option>
                            <option value="2">Teacher</option>
                            <option value="3">Student</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm">
                <input type="hidden" name="editUserID" id="editUserID">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="editPhone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleID" class="form-label">Role</label>
                        <select name="roleID" id="editRoleID" class="form-control" required>
                            <option value="1">Admin</option>
                            <option value="2">Teacher</option>
                            <option value="3">Student</option>
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
    // Xử lý thêm người dùng qua AJAX
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('admin_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('User added successfully!');
                location.reload();
            } else {
                alert(data.message || 'Error adding user!');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Hiển thị modal chỉnh sửa
    document.querySelectorAll('.editUserBtn').forEach(button => {
        button.addEventListener('click', () => {
            const userID = button.getAttribute('data-id');
            const username = button.getAttribute('data-username');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');
            const roleID = button.getAttribute('data-roleid');

            document.getElementById('editUserID').value = userID;
            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editRoleID').value = roleID;

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });
    });

    // Xử lý chỉnh sửa người dùng
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('admin_edit_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('User updated successfully!');
                location.reload();
            } else {
                alert(data.message || 'Error updating user!');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Xử lý xóa người dùng
    document.querySelectorAll('.deleteUserBtn').forEach(button => {
        button.addEventListener('click', () => {
            const userID = button.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`admin_delete_user.php?id=${userID}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('User deleted successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Error deleting user!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>