<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'StudentManagement';

try {
    // Kết nối MySQL
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tạo database
    $sql = "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    echo "Database $db_name created successfully<br>";

    // Chọn database
    $conn->exec("USE $db_name");

    // Tạo bảng Roles
    $sql = "CREATE TABLE IF NOT EXISTS Roles (
        RoleID INT AUTO_INCREMENT PRIMARY KEY,
        RoleName VARCHAR(50) NOT NULL UNIQUE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table Roles created successfully<br>";

    // Tạo bảng Users (hợp nhất Teachers và Students)
    $sql = "CREATE TABLE IF NOT EXISTS Users (
        UserID INT AUTO_INCREMENT PRIMARY KEY,
        Username VARCHAR(50) UNIQUE NOT NULL,
        Password VARCHAR(255) NOT NULL,
        FirstName VARCHAR(50) NOT NULL,
        LastName VARCHAR(50) NOT NULL,
        Email VARCHAR(100) UNIQUE NOT NULL,
        Phone VARCHAR(15),
        RoleID INT NOT NULL,
        IsTeacher BOOLEAN DEFAULT FALSE,  -- Thêm trường IsTeacher
        IsStudent BOOLEAN DEFAULT FALSE,  -- Thêm trường IsStudent
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (RoleID) REFERENCES Roles(RoleID) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table Users created successfully<br>";

    // Tạo bảng Classes
    $sql = "CREATE TABLE IF NOT EXISTS Classes (
        ClassID INT AUTO_INCREMENT PRIMARY KEY,
        ClassName VARCHAR(50) NOT NULL UNIQUE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table Classes created successfully<br>";

    // Tạo bảng Subjects
    $sql = "CREATE TABLE IF NOT EXISTS Subjects (
        SubjectID INT AUTO_INCREMENT PRIMARY KEY,
        SubjectName VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table Subjects created successfully<br>";

    // Tạo bảng SubjectClasses (quan hệ nhiều-nhiều giữa Subjects và Classes)
    $sql = "CREATE TABLE IF NOT EXISTS SubjectClasses (
        SubjectID INT NOT NULL,
        ClassID INT NOT NULL,
        PRIMARY KEY (SubjectID, ClassID),
        FOREIGN KEY (SubjectID) REFERENCES Subjects(SubjectID) ON DELETE CASCADE,
        FOREIGN KEY (ClassID) REFERENCES Classes(ClassID) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table SubjectClasses created successfully<br>";

    // Tạo bảng Sessions
    $sql = "CREATE TABLE IF NOT EXISTS Sessions (
        SessionID INT AUTO_INCREMENT PRIMARY KEY,
        SubjectID INT NOT NULL,
        Date DATE NOT NULL,
        StartTime TIME NOT NULL,
        EndTime TIME NOT NULL,
        FOREIGN KEY (SubjectID) REFERENCES Subjects(SubjectID) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table Sessions created successfully<br>";

    // Tạo bảng AttendanceStatus
    $sql = "CREATE TABLE IF NOT EXISTS AttendanceStatus (
        StatusID INT AUTO_INCREMENT PRIMARY KEY,
        StatusName ENUM('Present', 'Absent', 'Late') NOT NULL
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table AttendanceStatus created successfully<br>";

    // Tạo bảng Attendance
    $sql = "CREATE TABLE IF NOT EXISTS Attendance (
        AttendanceID INT AUTO_INCREMENT PRIMARY KEY,
        SessionID INT NOT NULL,
        UserID INT NOT NULL,  -- Đã thay UserID để thay cho StudentID
        StatusID INT NOT NULL,
        Remarks VARCHAR(255) DEFAULT '',
        FOREIGN KEY (SessionID) REFERENCES Sessions(SessionID) ON DELETE CASCADE,
        FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE,
        FOREIGN KEY (StatusID) REFERENCES AttendanceStatus(StatusID) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table Attendance created successfully<br>";

    // Chèn dữ liệu mẫu vào bảng Roles
    $sql = "INSERT INTO Roles (RoleName) VALUES 
        ('Admin'), 
        ('Teacher'), 
        ('Student')
    ON DUPLICATE KEY UPDATE RoleName=VALUES(RoleName)";
    $conn->exec($sql);
    echo "Sample Roles data inserted successfully<br>";

    // Chèn dữ liệu mẫu vào bảng Users
    $sql = "INSERT INTO Users (Username, Password, FirstName, LastName, Email, Phone, RoleID, IsTeacher, IsStudent) VALUES
        ('admin01', 'password123', 'Nguyen', 'Van A', 'admin01@example.com', '0912345678', 1, FALSE, FALSE),
        ('teacher01', 'password123', 'Tran', 'Van B', 'teacher01@example.com', '0923456789', 2, TRUE, FALSE),
        ('teacher02', 'password123', 'Le', 'Thi C', 'teacher02@example.com', '0934567890', 2, TRUE, FALSE),
        ('student01', 'password123', 'Pham', 'Van D', 'student01@example.com', '0945678901', 3, FALSE, TRUE),
        ('student02', 'password123', 'Hoang', 'Van E', 'student02@example.com', '0956789012', 3, FALSE, TRUE),
        ('student03', 'password123', 'Bui', 'Thi F', 'student03@example.com', '0967890123', 3, FALSE, TRUE),
        ('student04', 'password123', 'Nguyen', 'Van G', 'student04@example.com', '0978901234', 3, FALSE, TRUE)";
    $conn->exec($sql);
    echo "Sample Users data inserted successfully<br>";

    // Xóa bảng Teachers và Students vì đã hợp nhất vào bảng Users
    $sql = "DROP TABLE IF EXISTS Teachers";
    $conn->exec($sql);
    $sql = "DROP TABLE IF EXISTS Students";
    $conn->exec($sql);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

// Đóng kết nối
$conn = null;
?>
