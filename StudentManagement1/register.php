<?php
// Hiển thị lỗi (chỉ bật khi phát triển)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kết nối cơ sở dữ liệu
include 'db_connection.php';

// Xử lý đăng ký
// Xử lý đăng ký
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? ''; // Lấy mật khẩu từ form
    $roleID = $_POST['roleID'] ?? null;
    $username = strtok($email, '@'); // Lấy phần đầu email làm username
    $firstName = $_POST['firstName'] ?? 'Unknown'; // Giá trị mặc định nếu không nhập
    $lastName = $_POST['lastName'] ?? 'Unknown';  // Giá trị mặc định nếu không nhập
    $phone = $_POST['phone'] ?? null;

    // Kiểm tra độ dài mật khẩu
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long!";
    } elseif ($email && $password && $roleID) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash mật khẩu
        try {
            // Chèn dữ liệu vào bảng Users
            $stmt = $conn->prepare("INSERT INTO Users (Username, Email, Password, RoleID, FirstName, LastName, Phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashedPassword, $roleID, $firstName, $lastName, $phone])) {
                header('Location: login.php'); // Điều hướng về trang đăng nhập
                exit;
            } else {
                $error = "Error during registration!";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all required fields!";
    }
}
?>
<style>
body {
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #84fab0, #8fd3f4);
            height: 100vh;
            display: flex;
            flex-direction: column; /* Sắp xếp logo và form theo chiều dọc */
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Logo container */
        .logo-container {
            margin-bottom: 20px; /* Khoảng cách giữa logo và form */
            text-align: center; /* Căn giữa logo */
        }

        .logo {
            max-width: 150px; /* Kích thước logo */
        }

        .container {
            max-width: 600px;
            background: #6c757d; /* Màu nền xám nhạt */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #ffffff; /* Chữ màu trắng */
        }

        h2 {
            color: #ffffff; /* Chữ màu trắng */
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 4px;
            border: 1px solid #cccccc; /* Màu viền xám nhạt */
            background-color: #f7f7f7; /* Màu nền xám nhạt */
            color: #555555; /* Màu chữ xám đậm */
        }

        .btn-primary {
            background-color: #343a40; /* Màu xám đậm */
            border: none;
            border-radius: 4px;
            color: #ffffff; /* Chữ màu trắng trên nút */
        }

        .btn-primary:hover {
            background-color: #228b22; /* Màu xanh lá cây đậm khi hover */
        }

        .btn-link {
            color: #ffffff !important; /* Chữ màu trắng */
            text-decoration: none !important; /* Bỏ gạch chân */
        }

        .btn-link:hover {
            color: #dcdcdc !important; /* Màu trắng xám khi hover */
            text-decoration: underline !important; /* Gạch chân khi hover */
        }

        .alert {
            font-size: 14px;
            margin-bottom: 20px;
            border-radius: 4px;
            padding: 10px;
            background-color: #ffcccb; /* Màu nền đỏ nhạt */
            color: #b22222; /* Màu chữ đỏ đậm */
        }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="logo-container text-center">
        <img src="https://btec.fpt.edu.vn/wp-content/uploads/2024/06/Logo-Btec-2-1-1.png" alt="Logo" class="logo">
    </div>

<div class="container mt-5">
    <h2 class="text-center">Register</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" action="">
    <div class="mb-3">
        <label for="firstName" class="form-label">First Name</label>
        <input type="text" name="firstName" id="firstName" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="lastName" class="form-label">Last Name</label>
        <input type="text" name="lastName" id="lastName" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">phone</label>
        <input type="phone" name="phone" id="phone" class="form-control" required>
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
    <button type="submit" class="btn btn-primary">Register</button>
    <a href="login.php" class="btn btn-link">Login</a>
</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>