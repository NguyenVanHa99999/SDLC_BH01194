<?php

session_start();
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit;
}

include 'db_connection.php'; // File kết nối cơ sở dữ liệu

try {
    // Lấy thông tin người dùng dựa vào UserID trong session
    $userID = $_SESSION['UserID'];
    $stmt = $conn->prepare("SELECT FirstName, LastName FROM Users WHERE UserID = :userID");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $username = $user['FirstName'] . ' ' . $user['LastName'];
    } else {
        $username = 'Guest'; // Trường hợp không tìm thấy người dùng
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

$roleID = $_SESSION['RoleID']; // RoleID từ cơ sở dữ liệu

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            height: 100vh;
            overflow-x: hidden;
            /* Đảm bảo không có thanh cuộn ngang */
        }

        .navbar {
            background-color: #343a40;
            padding: 10px;
        }

        .navbar-brand img {
            width: 40px;
            height: auto;
        }

        .sidebar {
            height: 100vh;
            /* Đảm bảo sidebar kéo dài toàn màn hình */
            background-color: #6c757d;
            padding: 15px;
            margin: 0;
            /* Sát cạnh */
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .content {
            padding: 20px;
            background-color: white;
            border-radius: 0px;
            /* Xóa bo góc để khung sát cạnh */
            box-shadow: none;
            /* Loại bỏ bóng */
            margin: 0;
            /* Sát cạnh */
            height: 100vh;
            /* Chiều cao toàn màn hình */
            overflow-y: auto;
            /* Cuộn nội dung nếu quá dài */
        }

        .content h4 {
            margin: 0;
        }

        .logout {
            text-align: center;
            /* Đặt nội dung trong div ra giữa */
            margin-top: 20px;
            /* Tăng khoảng cách phía trên */
        }

        .logout a {
            padding: 10px 20px;
            /* Tăng padding để nút lớn hơn */
            font-size: 16px;
            /* Kích thước chữ lớn hơn */
            width: 220px;
            /* Đảm bảo nút có độ rộng vừa nội dung */
            text-align: center;
            /* Căn chữ giữa */
            display: inline-block;
            /* Đảm bảo width hoạt động đúng */
        }

        .container-fluid {
            padding: 0;
            /* Xóa padding của container */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark" style="background-color: #6c757d; padding: 20px;">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4K-tjpKQ_HjUjmO_g2VBQqIfvzd59QHPHSg&s" alt="Logo" style="height: 60px; margin-right: 20px;">
                <span style="font-size: 32px; color: white;">Student Management System</span>
            </div>
            <div class="text-white" style="font-size: 18px; margin-left: auto;">
                Hello, <?= htmlspecialchars($username); ?>!
            </div>
        </div>
    </nav>





    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <?php if ($roleID == 1): ?>
                    <h4 class="text-white" style="text-align: center; font-weight: bold; font-size: 3.5em;">Admin</h4>

                    <a href="?page=manage_users">Manage Users</a>
                    <a href="?page=manage_classes">Manage Classes</a>
                    <a href="?page=manage_subjects">Manage Subjects</a>
                    <a href="?page=manage_sessions">Manage Session</a>
                    <a href="?page=manage_attendance">Manage Attendance</a>
                <?php elseif ($roleID == 2): ?>
                    <h4 class="text-white" style="text-align: center; font-weight: bold; font-size: 3.5em;">Teacher</h4>
                    <a href="?page=teaching_schedule">View Teaching Schedule</a>
                    <a href="?page=session_details">View Session Details</a>
                    <a href="?page=attendance_management">Manage Attendance</a>
                <?php elseif ($roleID == 3): ?>
                    <h4 class="text-white" style="text-align: center; font-weight: bold; font-size: 3.5em;">Student</h4>
                    <a href="?page=view_subjects">View Subjects</a>
                    <a href="?page=view_attendance">View Attendance</a>
                    <a href="?page=register_classes">Register for Classes</a>
                <?php endif; ?>
                <div class="logout">
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <div class="content">
                    <?php
                    // Hiển thị nội dung dựa trên vai trò và nút được nhấn
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                        if ($roleID == 1) {
                            if ($page == 'manage_users') {
                                include 'admin_users.php';
                            } elseif ($page == 'manage_classes') {
                                include 'admin_classes.php';
                            } elseif ($page == 'manage_subjects') {
                                include 'admin_subjects.php';
                            } elseif ($page == 'manage_sessions') {
                                include 'admin_sessions.php'; // Tạo file này trong bước tiếp theo

                            } elseif ($page == 'manage_attendance') {
                                include 'admin_attendance.php';
                            }
                        } elseif ($roleID == 2) {
                            if ($page == 'teaching_schedule') {
                                include 'teacher_schedule.php';
                            } elseif ($page == 'session_details') {
                                include 'teacher_session.php';
                            } elseif ($page == 'attendance_management') {
                                include 'teacher_attendance.php';
                            }
                        } elseif ($roleID == 3) {
                            if ($page == 'view_subjects') {
                                include 'student_subjects.php';
                            } elseif ($page == 'view_attendance') {
                                include 'student_attendance.php';
                            } elseif ($page == 'register_classes') {
                                include 'student_register_classes.php';
                            }
                        } else {
                            echo "<h5 class='text-danger'>Access Denied!</h5>";
                        }
                    } else {
                        echo "
                              <div style='position: relative; width: 100%; height: 100vh; margin: 0; padding: 0;'>
                                <img src='img/logo.png' alt='Welcome Image' style='width: 100%; height: 100vh; object-fit: cover; margin: 0; padding: 0;'>
                             <h4 style='position: absolute; top: 10%; left: 50%; transform: translateX(-50%); color: white; background-color: rgba(0, 0, 0, 0.6); padding: 10px 20px; border-radius: 10px; font-size: 2.5em; text-align: center;'>Welcome To Btec School</h4>
                             </div>
                             ";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>