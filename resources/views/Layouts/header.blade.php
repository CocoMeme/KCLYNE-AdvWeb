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
            <div class="user-info" id="user-info">
                <img src="/Images/Customers/{{ Auth::user()->customer->image }}" alt="Profile Image" class="profile-image">
                <div class="dropdown-menu-user" id="profile-dropdown">
                    <a href="/orders/history"><i class="fa-solid fa-cart-shopping"></i>My Orders</a>
                    <a href="/profile"><i class="fa fa-user"></i> My Profile</a>
                </div>
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
            <a href="{{ route('login') }}">
                <i class='bx bx-log-in-circle'></i>
            </a>
        @endauth
    </div>
</header>

<script src="{{ asset('js/HeaderScript.js') }}"></script>
