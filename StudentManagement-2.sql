-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th12 09, 2024 lúc 08:54 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `StudentManagement`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Attendance`
--

CREATE TABLE `Attendance` (
  `AttendanceID` int(11) NOT NULL,
  `SessionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `StatusID` enum('Present','Absent','Late') NOT NULL,
  `Remarks` varchar(255) DEFAULT '',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Attendance`
--

INSERT INTO `Attendance` (`AttendanceID`, `SessionID`, `UserID`, `StatusID`, `Remarks`, `CreatedAt`) VALUES
(5, 4, 39, 'Present', 'lambaitap', '2024-12-04 03:13:31'),
(6, 3, 39, 'Absent', 'vang', '2024-12-04 03:15:56'),
(7, 7, 39, 'Present', 'tre5phut', '2024-12-04 01:36:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Classes`
--

CREATE TABLE `Classes` (
  `ClassID` int(11) NOT NULL,
  `ClassName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Classes`
--

INSERT INTO `Classes` (`ClassID`, `ClassName`) VALUES
(6, 'SE6302'),
(2, 'SE6303'),
(3, 'SE6304'),
(4, 'SE6305'),
(5, 'SE6306');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ClassStudents`
--

CREATE TABLE `ClassStudents` (
  `ClassStudentID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ClassStudents`
--

INSERT INTO `ClassStudents` (`ClassStudentID`, `ClassID`, `UserID`) VALUES
(5, 2, 39),
(6, 3, 39),
(7, 4, 39),
(8, 5, 39);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Roles`
--

CREATE TABLE `Roles` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Roles`
--

INSERT INTO `Roles` (`RoleID`, `RoleName`) VALUES
(1, 'Admin'),
(3, 'Student'),
(2, 'Teacher');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Sessions`
--

CREATE TABLE `Sessions` (
  `SessionID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `ClassID` int(11) DEFAULT NULL,
  `TeacherID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Sessions`
--

INSERT INTO `Sessions` (`SessionID`, `SubjectID`, `ClassID`, `TeacherID`, `Date`, `StartTime`, `EndTime`, `CreatedBy`, `CreatedAt`) VALUES
(1, 3, NULL, 0, '2024-12-12', '23:32:00', '23:21:00', 0, '2024-12-03 20:42:15'),
(3, 2, 2, 0, '2024-12-20', '03:01:00', '04:02:00', 0, '2024-12-03 21:13:56'),
(4, 2, 3, 38, '2024-12-13', '23:34:00', '12:43:00', 1, '2024-12-03 23:15:45'),
(5, 2, 4, 38, '2024-12-21', '12:03:00', '02:01:00', 1, '2024-12-04 00:16:27'),
(6, 2, 5, 38, '2024-12-05', '01:03:00', '01:03:00', 1, '2024-12-04 00:16:43'),
(7, 2, 2, 38, '2024-12-28', '03:01:00', '01:03:00', 1, '2024-12-04 00:16:56'),
(8, 3, 3, 38, '2024-12-11', '23:34:00', '01:04:00', 1, '2024-12-04 07:11:40'),
(9, 2, 6, 38, '2024-12-20', '02:03:00', '01:03:00', 1, '2024-12-04 09:11:11'),
(10, 7, 3, 38, '2024-12-19', '21:01:00', '02:02:00', 1, '2024-12-09 07:26:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Subjects`
--

CREATE TABLE `Subjects` (
  `SubjectID` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `TeacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Subjects`
--

INSERT INTO `Subjects` (`SubjectID`, `SubjectName`, `TeacherID`) VALUES
(2, 'LAP TRINH', 38),
(3, 'KINH DOANH', 38),
(5, 'toan', 38),
(6, 'TIENG VIET', 36),
(7, 'SDLC', 38),
(8, 'LY', 38);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `FirstName` varchar(50) DEFAULT 'Unknown',
  `LastName` varchar(50) DEFAULT 'Unknown',
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `RoleID` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `Users`
--

INSERT INTO `Users` (`UserID`, `Username`, `Password`, `FirstName`, `LastName`, `Email`, `Phone`, `RoleID`, `CreatedAt`) VALUES
(9, 'nguyenvanhâdmin', '$2y$10$3rYGfud4kuLgrCOcJ9nY6.fM5GJAaj04RVIXmZSu9zCFxE7ID8xzi', 'nguyenvan', 'ha1', 'nguyenvanha1@gmail.com', 141414421, 1, '2024-12-03 16:50:56'),
(36, 'nguyenvanha', '$2y$10$lCGhXeqwsmhzloapwm02v.M6jmxR9S2SGCd5Ec.X1rWxtXhcAF7fO', 'nguyenvan', 'ha34', 'nguyenvanha@gmaij.com', 421412414, 2, '2024-12-03 18:08:09'),
(37, 'fadad', '$2y$10$VQqGDX9nWmUMp0ycZrwpveiPMZiwwH4u5.ngGtwpvl7B4IsZt79ai', 'van', 'ha45', 'nguyenvana@gmail.com', 41241241, 2, '2024-12-03 22:06:10'),
(38, 'nguyenvanhateachẻ', '$2y$10$Yw1METkdqpZa2w4NVAHgC.5vig6ojFWZjfrYdkm3OvRteCD2EkXgi', 'nguyenvan', 'ha2', 'nguyenvanha2@gmail.com', 412412414, 2, '2024-12-03 22:11:34'),
(39, 'nguyenvanhastudent', '$2y$10$sRj7bDFv9YgxpKV/k/Y61OhZOR6vzrg79ZQ10/eEkwOky7tDzYRL2', 'nguyenvan', 'ha3', 'nguyenvanha3@gmail.com', 41421414, 3, '2024-12-03 23:57:38');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `Attendance`
--
ALTER TABLE `Attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `SessionID` (`SessionID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Chỉ mục cho bảng `Classes`
--
ALTER TABLE `Classes`
  ADD PRIMARY KEY (`ClassID`),
  ADD UNIQUE KEY `ClassName` (`ClassName`);

--
-- Chỉ mục cho bảng `ClassStudents`
--
ALTER TABLE `ClassStudents`
  ADD PRIMARY KEY (`ClassStudentID`),
  ADD KEY `ClassID` (`ClassID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- Chỉ mục cho bảng `Sessions`
--
ALTER TABLE `Sessions`
  ADD PRIMARY KEY (`SessionID`),
  ADD KEY `SubjectID` (`SubjectID`),
  ADD KEY `fk_sessions_classes` (`ClassID`);

--
-- Chỉ mục cho bảng `Subjects`
--
ALTER TABLE `Subjects`
  ADD PRIMARY KEY (`SubjectID`);

--
-- Chỉ mục cho bảng `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `RoleID` (`RoleID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `Attendance`
--
ALTER TABLE `Attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `Classes`
--
ALTER TABLE `Classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `ClassStudents`
--
ALTER TABLE `ClassStudents`
  MODIFY `ClassStudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `Roles`
--
ALTER TABLE `Roles`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `Sessions`
--
ALTER TABLE `Sessions`
  MODIFY `SessionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `Subjects`
--
ALTER TABLE `Subjects`
  MODIFY `SubjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `Attendance`
--
ALTER TABLE `Attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`SessionID`) REFERENCES `Sessions` (`SessionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ClassStudents`
--
ALTER TABLE `ClassStudents`
  ADD CONSTRAINT `classstudents_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `Classes` (`ClassID`) ON DELETE CASCADE,
  ADD CONSTRAINT `classstudents_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `Sessions`
--
ALTER TABLE `Sessions`
  ADD CONSTRAINT `fk_sessions_classes` FOREIGN KEY (`ClassID`) REFERENCES `Classes` (`ClassID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`SubjectID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `Roles` (`RoleID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
