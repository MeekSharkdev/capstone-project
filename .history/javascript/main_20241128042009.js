function markAsDone(userId) {
  if (confirm("Are you sure you want to mark this user as done?")) {
    // Send an AJAX request to update the status
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var row = document.getElementById("user-" + userId);
        var button = document.getElementById("mark-" + userId);
        row.classList.add("done");
        button.innerHTML = '<i class="fa fa-check"></i> Done';
        button.classList.remove("btn-success");
        button.classList.add("btn-secondary");
        button.disabled = true;
      }
    };
    xhr.send("id=" + userId);
  }
}
