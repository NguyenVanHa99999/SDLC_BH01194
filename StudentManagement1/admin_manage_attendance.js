document.getElementById('sessionSelect').addEventListener('change', function () {
    const sessionID = this.value;

    if (!sessionID) {
        document.getElementById('attendanceTableContainer').style.display = 'none';
        return;
    }

    fetch(`admin_attendance_handler.php?sessionID=${sessionID}`)
        .then((response) => response.json())
        .then((data) => {
            const tableBody = document.getElementById('attendanceTableBody');
            tableBody.innerHTML = '';
            document.getElementById('attendanceTableContainer').style.display = 'block';

            data.forEach((student) => {
                const row = `
                    <tr>
                        <td>${student.UserID}</td>
                        <td>${student.FirstName} ${student.LastName}</td>
                        <td>${student.StatusID || 'N/A'}</td>
                        <td>${student.Remarks || 'N/A'}</td>
                        <td>${student.CreatedAt || 'N/A'}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-user-id="${student.UserID}">Edit</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });

            document.querySelectorAll('.edit-btn').forEach((btn) =>
                btn.addEventListener('click', function () {
                    const userID = this.dataset.userId;
                    const row = this.closest('tr');
                    const statusID = row.children[2].innerText;
                    const remarks = row.children[3].innerText;

                    document.getElementById('editUserID').value = userID;
                    document.getElementById('editSessionID').value = sessionID;
                    document.getElementById('editStatus').value = getStatusValue(statusID);
                    document.getElementById('editRemarks').value = remarks;

                    new bootstrap.Modal(document.getElementById('editModal')).show();
                })
            );
        });
});

document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const sessionID = document.getElementById('editSessionID').value;
    const userID = document.getElementById('editUserID').value;
    const statusID = document.getElementById('editStatus').value;
    const remarks = document.getElementById('editRemarks').value;

    fetch('admin_attendance_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            sessionID: sessionID,
            userID: userID,
            statusID: statusID,
            remarks: remarks,
        }),
    })
        .then((response) => response.json())
        .then(() => {
            alert('Attendance updated!');
            location.reload();
        });
});

function getStatusText(statusID) {
    if (statusID === '1') return 'Present';
    if (statusID === '2') return 'Absent';
    if (statusID === '3') return 'Late';
    return '';
}

function getStatusValue(statusText) {
    if (statusText === 'Present') return '1';
    if (statusText === 'Absent') return '2';
    if (statusText === 'Late') return '3';
    return '';
}