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
    const logoutButton = document.getElementById('logout-button');
    const confirmationDialog = document.getElementById('confirmation-dialog');
    const confirmLogout = document.getElementById('confirm-logout');
    const cancelLogout = document.getElementById('cancel-logout');

    if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
            event.preventDefault();
            confirmationDialog.style.display = 'block';
            setTimeout(() => {
                confirmationDialog.classList.add('show');
            }, 10);
        });
    }

    if (confirmLogout) {
        confirmLogout.addEventListener('click', function() {
            document.getElementById('logout-form').submit();
        });
    }

    if (cancelLogout) {
        cancelLogout.addEventListener('click', function() {
            confirmationDialog.classList.remove('show');
            setTimeout(() => {
                confirmationDialog.style.display = 'none';
            }, 300);
        });
    }

    // Search Functionality
    $('#search-icon').on('click', function(event) {
        event.preventDefault();
        $('#search-compact').hide();
        $('#search-expanded').css('display', 'flex').addClass('visible');
        $('body').addClass('blackened');
    });

    $(document).on('click', function(event) {
        if (!$('#search-expanded').is(event.target) && $('#search-expanded').has(event.target).length === 0 && event.target.id !== 'search-icon') {
            $('#search-expanded').removeClass('visible');
            $('body').removeClass('blackened');
            setTimeout(function() {
                $('#search-expanded').hide();
                $('#search-compact').show();
            }, 100);
        }
    });
});
