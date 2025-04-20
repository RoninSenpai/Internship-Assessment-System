document.getElementById("myForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let name = document.getElementById("myForm").value;
    console.log("Sending data:", name);

    // Send to the server WITHOUT a page reload!
    fetch("/submit", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name: name })
    }).then(response => response.json())
      .then(data => console.log("Server says:", data));
});

function confirmAction(event) {
    let userResponse = confirm("Are you sure you want to proceed? 😔");
    if (!userResponse) {
        event.preventDefault(); // Prevent the default action if the user clicks 'Cancel'
    }
}