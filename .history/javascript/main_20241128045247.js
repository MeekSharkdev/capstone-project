// Mark as Done function
function markAsDone(userId) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var row = document.getElementById("user-" + userId);
      var button = document.getElementById("mark-" + userId);

      // Mark as done (change button and style)
      row.classList.add("done");
      button.innerHTML = '<i class="fa fa-check"></i> Done';
      button.classList.remove("btn-success");
      button.classList.add("btn-secondary");
      button.disabled = true;

      // Show the "Undo" button
      var undoButton = document.getElementById("undo-" + userId);
      undoButton.style.display = "inline-block"; // Show the undo button
    }
  };
  xhr.send("id=" + userId);
}

// Undo Mark as Done function
function undoMarkAsDone(userId) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "undo_status.php", true); // PHP file to undo the status change
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var row = document.getElementById("user-" + userId);
      var button = document.getElementById("mark-" + userId);

      // Revert to "Mark as Done" state
      row.classList.remove("done");
      button.innerHTML = "Mark as Done";
      button.classList.remove("btn-secondary");
      button.classList.add("btn-success");
      button.disabled = false;

      // Hide the "Undo" button
      var undoButton = document.getElementById("undo-" + userId);
      undoButton.style.display = "none"; // Hide the undo button
    }
  };
  xhr.send("id=" + userId);
}
