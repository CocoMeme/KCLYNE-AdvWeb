// public/js/AccountScripts.js

document.getElementById('logout-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the form from submitting immediately
    if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logout-form').submit(); // Submit the form if the user confirms
    }
});
