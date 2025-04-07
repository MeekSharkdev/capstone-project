function markAsDone(userId) {
  // Send an AJAX request to update the status in the database
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // Parse the JSON response
      var response = JSON.parse(xhr.responseText);

      // Check if the status is 'done' in the response
      if (response.status === "done") {
        var row = document.getElementById("user-" + response.userId);
        var button = document.getElementById("mark-" + response.userId);

        // Mark as done (change button and style)
        row.classList.add("done");
        button.innerHTML = '<i class="fa fa-check"></i> Done';
        button.classList.remove("btn-success");
        button.classList.add("btn-secondary");
        button.disabled = true;
      }
    }
  };
  xhr.send("id=" + userId);
}
