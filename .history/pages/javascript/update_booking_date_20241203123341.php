let selectedId = null;

document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', () => {
        selectedId = button.getAttribute('data-id'); // Set the selected booking ID
        document.getElementById('modalName').textContent = button.getAttribute('data-name');
        document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
        document.getElementById('modalPurpose').textContent = button.getAttribute('data-purpose');
        document.getElementById('modalDate').textContent = button.getAttribute('data-date');
        flatpickr("#newDatePicker", {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: "today", // Prevent selecting past dates
        });
    });
});

document.getElementById('confirmRescheduleBtn').addEventListener('click', () => {
    if (!selectedId) {
        alert('No booking selected!');
        return;
    }

    const newDate = document.getElementById('newDatePicker').value;
    if (!newDate) {
        alert('Please select a new date.');
        return;
    }

    fetch('update_booking_date.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: selectedId,
            date: newDate,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Schedule successfully rescheduled to ' + newDate);
            document.getElementById('modalDate').textContent = newDate;
            location.reload(); // Reload to update the table
        } else {
            alert('Failed to reschedule: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rescheduling.');
    });
});
