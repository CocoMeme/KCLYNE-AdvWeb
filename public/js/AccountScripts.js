$(document).ready(function() {
    // Login AJAX
    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/login',
            data: $(this).serialize(),
            success: function(response) {
                alert(response.message);
                window.location.href = '/';
            },
            error: function(response) {
                alert('Login failed: ' + response.responseJSON.message);
            }
        });
    });

    // Registration AJAX
    $('#register-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/register',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.message);
                window.location.href = '/';
            },
            error: function(response) {
                alert('Registration failed: ' + response.responseJSON.message);
            }
        });
    });

    // Logout Confirmation Dialog
    document.getElementById('logout-button').addEventListener('click', function(event) {
        event.preventDefault();
        const confirmationDialog = document.getElementById('confirmation-dialog');
        confirmationDialog.style.display = 'block';
        setTimeout(() => {
            confirmationDialog.classList.add('show');
        }, 10);
    });

    document.getElementById('confirm-logout').addEventListener('click', function() {
        document.getElementById('logout-form').submit();
    });

    document.getElementById('cancel-logout').addEventListener('click', function() {
        const confirmationDialog = document.getElementById('confirmation-dialog');
        confirmationDialog.classList.remove('show');
        setTimeout(() => {
            confirmationDialog.style.display = 'none';
        }, 300);
    });

    // Search Functionality
    document.getElementById('search-icon').addEventListener('click', function(event) {
        event.preventDefault();
        var searchCompact = document.getElementById('search-compact');
        var searchExpanded = document.getElementById('search-expanded');
        searchCompact.style.display = 'none';
        searchExpanded.style.display = 'flex';
        setTimeout(function() {
            searchExpanded.classList.add('visible');
            document.body.classList.add('blackened');
        }, 10); 
    });

    document.addEventListener('click', function(event) {
        var searchCompact = document.getElementById('search-compact');
        var searchExpanded = document.getElementById('search-expanded');
        if (!searchExpanded.contains(event.target) && event.target.id !== 'search-icon') {
            searchExpanded.classList.remove('visible');
            document.body.classList.remove('blackened');
            setTimeout(function() {
                searchExpanded.style.display = 'none';
                searchCompact.style.display = 'flex';
            }, 100);
        }
    });
});
