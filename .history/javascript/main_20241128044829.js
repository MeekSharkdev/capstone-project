// Mark as Done function
function markAsDone(userId) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var row = document.getElementById("user-" + userId);
      var button = document.getElementById("mark-" + userId);

      // Mark as done
      row.classList.add("done");
      button.innerHTML = '<i class="fa fa-check"></i> Done';
      button.classList.remove("btn-success");
      button.classList.add("btn-secondary");
      button.disabled = true;

      // Show the undo button
      var undoButton = document.getElementById("undo-" + userId);
      undoButton.style.display = "inline-block";
    }
  };
  xhr.send("id=" + userId);
}

// Undo Mark as Done function
function undoMarkAsDone(userId) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "undo_status.php", true); // New PHP file for undoing
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var row = document.getElementById("user-" + userId);
      var button = document.getElementById("mark-" + userId);

      // Remove 'done' class and update the button
      row.classList.remove("done");
      button.innerHTML = "Mark as Done";
      button.classList.remove("btn-secondary");
      button.classList.add("btn-success");
      button.disabled = false;

      // Hide the undo button
      var undoButton = document.getElementById("undo-" + userId);
      undoButton.style.display = "none";
    }
  };
  xhr.send("id=" + userId);
}
