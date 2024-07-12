<div class="admin-header">
    <div class="side-bar">

        <div class="top-side-bar">
            <a href="/" class="logo"><img src="/Images/Layouts/Logo White.png" alt="Logo"></a>
            <p>KCLYNE</p>
            <p>ADMINS</p>  
        </div>

        <ul class="admin-navmenu">
            <li><a href="/admin/dashboard"><i class='bx bx-bar-chart-square'></i>Dashboard</a></li>
            <li><a href="/admin/users"><i class='bx bxs-user-rectangle'></i>Customers</a></li>
            <li><a href="/product"><i class='bx bxs-purchase-tag-alt'></i>Products</a></li>
            <li><a href="/admin/orders"><i class='bx bxs-wrench'></i>Services</a></li>
            <li><a href="/employee"><i class='bx bxs-user-detail'></i>Employees</a></li>
        </ul>

        <div class="log-out">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class='bx bx-log-out-circle'></i>Logout
            </a>
        </div>
    </div>
</div>
