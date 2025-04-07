function markAsDone(userId) {
  // Send an AJAX request to update the status
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (xhr.status === 200 && xhr.responseText.trim() === "success") {
      // Update the button and row upon success
      var row = document.getElementById("user-" + userId);
      var button = document.getElementById("mark-" + userId);

      // Update row and button
      row.classList.add("done");
      button.innerHTML = '<i class="fa fa-check"></i> Done';
      button.classList.replace("btn-success", "btn-secondary");
      button.disabled = true; // Disable the button
    } else {
      alert("Failed to mark as done. Please try again.");
    }
  };

  xhr.onerror = function () {
    alert("Network error. Please check your connection.");
  };

  xhr.send("id=" + userId);
}
