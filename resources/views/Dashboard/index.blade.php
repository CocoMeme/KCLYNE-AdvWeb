@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon-product">
                <i class="fas fa-star"></i>
            </div>
            <h2>Product Average Rating</h2>
            <p>{{ number_format($productAverageRating, 2) }}</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon-service">
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h2>Service Average Rating</h2>
            <p>{{ number_format($serviceAverageRating, 2) }}</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon-cart">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Sales</h2>
            <p>{{ $orderCount }}</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon-users">
                <i class="fas fa-users"></i>
            </div>
            <h2>Customer Count</h2>
            <p>{{ $customerCount }}</p>
        </div>
    </div>

    <div class="dashboard-charts">
    <div class="charts-row">
        <div class="chart-card chart-card-products">
            <h3>Products Average Rating</h3>
            <canvas id="productsRatingsChart"></canvas>
        </div>

        <div class="chart-card chart-card-services">
            <h3>Services Average Rating</h3>
            <canvas id="servicesRatingsChart"></canvas>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card chart-card-orders">
            <h3>Orders Per Day</h3>
            <canvas id="ordersPerDayChart"></canvas>
        </div>

        <div class="chart-card chart-card-customers">
            <h3>Customers Per Day</h3>
            <canvas id="customersPerDayChart"></canvas>
        </div>
    </div>
</div>

</div>

<script>
    var productsRatings = {!! json_encode($productsRatings) !!};
    var servicesRatings = {!! json_encode($servicesRatings) !!};
    var ordersPerDay = {!! json_encode($ordersPerDay) !!};
    var customersPerDay = {!! json_encode($customersPerDay) !!};

    console.log('Products Ratings:', productsRatings);
    console.log('Services Ratings:', servicesRatings);
    console.log('Orders Per Day:', ordersPerDay);
    console.log('Customers Per Day:', customersPerDay);
</script>
<script src="{{ asset('js/DashboardScript.js') }}"></script>
@endsection
