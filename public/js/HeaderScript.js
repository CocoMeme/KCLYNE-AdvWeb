document.addEventListener('DOMContentLoaded', function() {
    const userInfo = document.getElementById('user-info');
    const dropdownMenu = document.getElementById('profile-dropdown');
    let isClicked = false;

    // Hover to show/hide dropdown initially
    userInfo.addEventListener('mouseover', function() {
        if (!isClicked) {
            dropdownMenu.style.display = 'block';
        }
    });

    userInfo.addEventListener('mouseout', function() {
        if (!isClicked) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Click to toggle dropdown visibility
    userInfo.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent click event from bubbling up
        isClicked = true; // Set the flag to true after the first click
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Close the dropdown if clicked outside
    document.addEventListener('click', function(event) {
        if (!userInfo.contains(event.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
});
