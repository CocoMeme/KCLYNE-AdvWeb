<header>
    <a href="/" class="logo"><img src="/Images/Layouts/Logo White.png" alt="Logo">KCLYNE</a>
    
    <ul class="navmenu">
        <li><a href="/">Home</a></li>
        <li><a href="/shop">Shop</a></li>
        <li><a href="/services">Services</a></li>
        <li><a href="#">About Us</a></li>
    </ul>
    
    <div class="nav-icon">
        @auth
            <div class="search search-compact" id="search-compact" style="display:flex">
                <i class='bx bx-search' id="search-icon"></i>
            </div>

            <a href="#">
                <div class="search search-expanded" id="search-expanded" style="display:none">
                    <input type="text" placeholder="Enter a search query...">
                    <i class='bx bx-search'></i>
                </div>          
            </a>

            <a href="/">
                <i class='bx bx-cart-alt'></i>
            </a>
            <div class="user-info">
                <img src="/Images/Customers/{{ Auth::user()->customer->image }}" alt="Profile Image" class="profile-image">
            </div>
            <div class="logout-button">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="button" class="icon-button" id="logout-button">
                        <i class='bx bx-log-out-circle'></i>
                    </button>
                </form>
                <div id="confirmation-dialog" class="confirmation-dialog" style="display: none;">
                    <div class="message">
                        <p>Are you sure you want to logout?</p>
                    </div>
                    <div class="buttons">
                        <button id="confirm-logout" class="confirmation-button">Yes</button>
                        <button id="cancel-logout" class="confirmation-button">No</button>
                    </div>
                </div>
            </div>
        @else
            <div class="search search-compact" id="search-compact" style="display:flex">
                <i class='bx bx-search' id="search-icon"></i>
            </div>

            <a href="#">
                <div class="search search-expanded" id="search-expanded" style="display:none">
                    <input type="text" placeholder="Enter a search query...">
                    <i class='bx bx-search'></i>
                </div>          
            </a>

            <a href="/">
                <i class='bx bx-cart-alt'></i>
            </a>
            <a href="{{ route('login') }}">
                <i class='bx bx-log-in-circle'></i>
            </a>
        @endauth
    </div>
</header>

