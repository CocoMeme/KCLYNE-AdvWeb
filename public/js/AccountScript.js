$(document).ready(function() {
    // Ensure CSRF token is included in every AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#login-form').on('submit', function(e) {
        e.preventDefault();
    
        $.ajax({
            type: 'POST',
            url: '/login',
            data: $(this).serialize(),
            success: function(response) {
                $('.pop-up-message').remove();
                $('<div class="pop-up-message">' + response.message + '</div>').appendTo('body');
                if (response.redirect) {
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            },
            error: function(response) {
                $('.pop-up-message').remove();
                if (response.responseJSON && response.responseJSON.message) {
                    $('<div class="pop-up-message">' + response.responseJSON.message + '</div>').appendTo('body');
                } else {
                    $('<div class="pop-up-message">Login failed: An unknown error occurred</div>').appendTo('body');
                }
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
                $('.pop-up-message').remove();
                $('<div class="pop-up-message">' + response.message + '</div>').appendTo('body');
                setTimeout(function() {
                    window.location.href = '/';
                }, 1000);
            },
            error: function(response) {
                $('.pop-up-message').remove();
                $('<div class="pop-up-message">Registration failed: ' + response.responseJSON.message + '</div>').appendTo('body');
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
