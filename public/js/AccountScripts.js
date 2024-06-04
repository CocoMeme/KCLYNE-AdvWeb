// public/js/AccountScripts.js

document.getElementById('logout-button').addEventListener('click', function(event) {
    event.preventDefault();
    const confirmationDialog = document.getElementById('confirmation-dialog');
    confirmationDialog.style.display = 'block';
    setTimeout(() => {
        confirmationDialog.classList.add('show');
    }, 10); // Slight delay to trigger transition
});

document.getElementById('confirm-logout').addEventListener('click', function() {
    document.getElementById('logout-form').submit();
});

document.getElementById('cancel-logout').addEventListener('click', function() {
    const confirmationDialog = document.getElementById('confirmation-dialog');
    confirmationDialog.classList.remove('show');
    setTimeout(() => {
        confirmationDialog.style.display = 'none';
    }, 300); // Delay to match the transition duration
});
