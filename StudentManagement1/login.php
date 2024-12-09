<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn người dùng dựa trên email
    $stmt = $conn->prepare("SELECT UserID, RoleID, Password FROM Users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Kiểm tra email và mật khẩu
    if ($user && password_verify($password, $user['Password'])) {
        // Lưu thông tin vào session
        $_SESSION['UserID'] = $user['UserID'];
        $_SESSION['RoleID'] = $user['RoleID'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Toàn bộ nền */
        body {
    background: linear-gradient(120deg, #84fab0, #8fd3f4); /* Nền gradient */
    height: 100vh;
    display: flex;
    flex-direction: column; /* Sắp xếp logo và form theo chiều dọc */
    align-items: center;
    justify-content: center;
    margin: 0;
}

.logo-container {
    margin-bottom: 20px; /* Khoảng cách giữa logo và form */
    text-align: center; /* Căn giữa logo */
}

.logo {
    max-width: 150px; /* Kích thước logo */
}

.container {
    width: 600px; /* Kích thước form lớn hơn */
    height: auto;
    background: #6c757d; /* Màu nền form */
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: #ffffff;
}

h2 {
    color: #ffffff;
    margin-bottom: 20px;
    text-align: left; /* Căn lề trái */
}

.form-label {
    text-align: left; /* Căn lề trái */
    display: block; /* Đảm bảo tiêu đề input hiển thị trên ô input */
}

.form-control {
    border-radius: 4px;
    border: 1px solid #cccccc;
    background-color: #f7f7f7;
    color: #555555;
    text-align: left; /* Căn chữ trong ô input sang trái */
}

.btn-primary {
    background-color: #343a40;
    border: none;
    border-radius: 4px;
    color: #ffffff;
    width: 100%; /* Nút login chiếm toàn bộ chiều ngang */
}

.btn-primary:hover {
    background-color: #228b22;
}

.btn-link {
    color: #ffffff;
    text-decoration: none;
    text-align: left; /* Căn lề trái */
    display: block;
}

.btn-link:hover {
    color: #dcdcdc;
    text-decoration: underline;
}

.alert {
    font-size: 14px;
    margin-bottom: 20px;
    border-radius: 4px;
    padding: 10px;
    background-color: #ffcccb;
    color: #b22222;
    text-align: left; /* Căn thông báo lỗi lề trái */
}
       
    </style>
</head>
<body>
<div class="logo-container text-center">
        <img src="https://btec.fpt.edu.vn/wp-content/uploads/2024/06/Logo-Btec-2-1-1.png" alt="Logo" class="logo">
    </div>
<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="register.php" class="btn btn-link">Register</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>