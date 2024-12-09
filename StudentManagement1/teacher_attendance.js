document.getElementById('sessionSelect').addEventListener('change', function () {
    const sessionID = this.value;

    if (!sessionID) {
        document.getElementById('attendanceTableBody').innerHTML = '';
        return;
    }

    fetch(`teacher_manage_attendance.php?sessionID=${sessionID}`)
        .then((response) => response.json())
        .then((data) => {
            const tableBody = document.getElementById('attendanceTableBody');
            tableBody.innerHTML = '';

            data.forEach((student) => {
                const row = `
                    <tr>
                        <td>${student.UserID}</td>
                        <td>${student.FirstName} ${student.LastName}</td>
                        <td>
                            <select class="form-select status-select" data-user-id="${student.UserID}">
                                <option value="1" ${student.StatusID == 1 ? 'selected' : ''}>Present</option>
                                <option value="2" ${student.StatusID == 2 ? 'selected' : ''}>Absent</option>
                                <option value="3" ${student.StatusID == 3 ? 'selected' : ''}>Late</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control remarks-input" value="${student.Remarks || ''}" data-user-id="${student.UserID}">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm save-btn" data-user-id="${student.UserID}">Save</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });

            document.querySelectorAll('.save-btn').forEach((btn) =>
                btn.addEventListener('click', function () {
                    const userID = this.dataset.userId;
                    const statusID = document.querySelector(`.status-select[data-user-id="${userID}"]`).value;
                    const remarks = document.querySelector(`.remarks-input[data-user-id="${userID}"]`).value;

                    const formData = new FormData();
                    formData.append('sessionID', sessionID);
                    formData.append('userID', userID);
                    formData.append('statusID', statusID);
                    formData.append('remarks', remarks);

                    fetch('teacher_manage_attendance.php', {
                        method: 'POST',
                        body: formData,
                    })
                        .then((response) => response.json())
                        .then((result) => {
                            alert(result.status === 'inserted' ? 'Attendance added!' : 'Attendance updated!');
                        });
                })
            );
        });
});