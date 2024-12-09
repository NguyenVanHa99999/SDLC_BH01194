<?php
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-backdrop {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3>Admin - Manage Attendance</h3>

        <!-- Dropdown chọn buổi học -->
        <div class="mb-3">
            <label for="sessionSelect" class="form-label">Select a Session:</label>
            <select id="sessionSelect" class="form-select">
                <option value="">-- Select Session --</option>
                <?php
                // Lấy danh sách buổi học
                $stmt = $conn->query("
                    SELECT s.SessionID, s.Date, s.StartTime, s.EndTime, c.ClassName 
                    FROM Sessions s
                    JOIN Classes c ON s.ClassID = c.ClassID
                ");
                while ($row = $stmt->fetch()) {
                    echo "<option value='{$row['SessionID']}'>
                            {$row['Date']} - {$row['ClassName']} ({$row['StartTime']} - {$row['EndTime']})
                          </option>";
                }
                ?>
            </select>
        </div>

        <!-- Bảng hiển thị danh sách sinh viên -->
        <div id="attendanceTableContainer" style="display: none;">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <thead style="background-color: black; color: white;">
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody">
                    <!-- Dữ liệu sẽ được tải qua AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Attendance -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select id="editStatus" class="form-select">
                                <option value="1">Present</option>
                                <option value="2">Absent</option>
                                <option value="3">Late</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editRemarks" class="form-label">Remarks</label>
                            <textarea id="editRemarks" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="editUserID">
                        <input type="hidden" id="editSessionID">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin_manage_attendance.js"></script>
</body>
</html>