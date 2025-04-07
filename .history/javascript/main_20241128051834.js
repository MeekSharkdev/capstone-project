function markAsDone(userId) {
  // Send an AJAX request to update the status in the database
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // Parse the response to see if the status update was successful
      var response = JSON.parse(xhr.responseText);
      if (response.status === "done") {
        // Update the row and button to reflect the status change
        var row = document.getElementById("user-" + response.userId);
        var button = document.getElementById("mark-" + response.userId);

        // Add 'done' class to the row to change its appearance
        row.classList.add("done");

        // Change button text to 'Done' and disable it
        button.innerHTML = '<i class="fa fa-check"></i> Done';
        button.classList.remove("btn-success");
        button.classList.add("btn-secondary");
        button.disabled = true;
      }
    }
  };
  xhr.send("id=" + userId);
}
