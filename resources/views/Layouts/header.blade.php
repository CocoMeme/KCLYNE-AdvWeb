<header>
    <a href="/" class="logo"><img src="/Images/Layouts/Logo White.png" alt=""></a>
    
    <ul class="navmenu">
        <li><a href="/">Home</a></li>
        <li><a href="#">Shop</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">About Us</a></li>
    </ul>

    <div class="search">
        <form action="#" method="GET">
            <input type="text" name="query" placeholder="Search a Product" value="">
        </form>
    </div>
    
    <div class="nav-icon">
        @auth
            <a href="/">
                <i class='bx bx-cart-alt'></i>
            </a>
            <a href="/">
                <i class='bx bx-bell'></i>
            </a>
            <div class="user-info">
                <img src="/Images/Customers/{{ Auth::user()->customer->image }}" alt="Profile Image" class="profile-image">
            </div>
            <div class="logout-button">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="icon-button" id="logout-button">
                        <i class='bx bx-log-out-circle'></i>
                    </button>
                </form>
            </div>
        @else
            <a href="/">
                <i class='bx bx-cart-alt'></i>
            </a>
            <a href="/">
                <i class='bx bx-bell'></i>
            </a>
            <a href="{{ route('login') }}">
                <i class='bx bxs-user-circle'></i>
            </a>
            <div class="logout-button">
                <a href="{{ route('register') }}">
                    <i class='bx bx-log-in-circle'></i>
                </a>
            </div>
        @endauth
    </div>
</header>

<script src="{{ asset('js/AccountScripts.js') }}"></script>
