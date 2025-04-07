function markAsDone(userId) {
    // Send an AJAX request to update the status in the database
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the button and row immediately upon success
            var row = document.getElementById("user-" + userId);
            var button = document.getElementById("mark-" + userId);

            // Check the current status and update accordingly
            if (xhr.responseText == "done") {
                // Mark as done (change button and style)
                row.classList.add("done");
                button.innerHTML = '<i class="fa fa-check"></i> Done';
                button.classList.remove("btn-success");
                button.classList.add("btn-secondary");
                button.disabled = true; // Disable the button
            }
        }
    };
    xhr.send("id=" + userId + "&action=done");
}

function markAsUndo(userId) {
    // Send an AJAX request to update the status back to "not done"
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the button and row immediately upon success
            var row = document.getElementById("user-" + userId);
            var button = document.getElementById("mark-" + userId);

            // Check the current status and update accordingly
            if (xhr.responseText == "not_done") {
                // Undo the "done" status (reset button and style)
                row.classList.remove("done");
                button.innerHTML = '<i class="fa fa-undo"></i> Mark as Done';
                button.classList.remove("btn-secondary");
                button.classList.add("btn-success");
                button.disabled = false; // Enable the button
            }
        }
    };
    xhr.send("id=" + userId + "&action=undo");
}
