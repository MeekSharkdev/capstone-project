function markAsDone(userId) {
  // Send an AJAX request to update the status in the database
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        // Parse the response
        const response = JSON.parse(xhr.responseText);

        if (response.success) {
          // Update the button and row immediately upon success
          var row = document.getElementById("user-" + userId);
          var button = document.getElementById("mark-" + userId);

          // Mark as done (update button and row style)
          row.classList.add("done");
          button.innerHTML = '<i class="fa fa-undo"></i> Undo';
          button.classList.remove("btn-success");
          button.classList.add("btn-secondary");
          button.setAttribute("onclick", `markAsUndo(${userId})`);
        } else {
          alert("Error updating status: " + response.message);
        }
      } else {
        alert("An error occurred. Please try again.");
      }
    }
  };
  xhr.send("id=" + userId + "&action=done");
}

function markAsUndo(userId) {
  // Send an AJAX request to revert the status
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        const response = JSON.parse(xhr.responseText);

        if (response.success) {
          // Revert the button and row immediately upon success
          var row = document.getElementById("user-" + userId);
          var button = document.getElementById("mark-" + userId);

          // Undo the "done" status
          row.classList.remove("done");
          button.innerHTML = '<i class="fa fa-check"></i> Mark as Done';
          button.classList.remove("btn-secondary");
          button.classList.add("btn-success");
          button.setAttribute("onclick", `markAsDone(${userId})`);
        } else {
          alert("Error updating status: " + response.message);
        }
      } else {
        alert("An error occurred. Please try again.");
      }
    }
  };
  xhr.send("id=" + userId + "&action=undo");
}
