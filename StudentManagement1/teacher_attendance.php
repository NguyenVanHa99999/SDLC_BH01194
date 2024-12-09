<?php
include 'db_connection.php';
?>

<div class="container mt-4">
    <h3>Manage Attendance</h3>
    <div class="mb-3">
        <label for="sessionSelect" class="form-label">Select a Session:</label>
        <select id="sessionSelect" class="form-select">
            <option value="">-- Select Session --</option>
            <?php
            $stmt = $conn->query("
                SELECT s.SessionID, s.Date, s.StartTime, s.EndTime, c.ClassName
                FROM Sessions s
                JOIN Classes c ON s.ClassID = c.ClassID
            ");
            while ($row = $stmt->fetch()) {
                echo "<option value='{$row['SessionID']}'>{$row['Date']} - {$row['ClassName']} ({$row['StartTime']} - {$row['EndTime']})</option>";
            }
            ?>
        </select>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <thead style="background-color: black; color: white;">
                <th>Student ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="attendanceTableBody">
            <!-- Content dynamically loaded by JavaScript -->
        </tbody>
    </table>
</div>

<script src="teacher_attendance.js"></script>