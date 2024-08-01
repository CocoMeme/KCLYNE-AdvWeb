document.addEventListener('DOMContentLoaded', function () {
    var productsRatings = window.productsRatings;
    var servicesRatings = window.servicesRatings;
    var ordersPerDay = window.ordersPerDay;
    var customersPerDay = window.customersPerDay;

    console.log('Products Ratings:', productsRatings);
    console.log('Services Ratings:', servicesRatings);
    console.log('Orders Per Day:', ordersPerDay);
    console.log('Customers Per Day:', customersPerDay);

    function transformChartData(data, key, value) {
        let labels = [];
        let values = [];
        data.forEach(item => {
            labels.push(item[key]);
            values.push(item[value]);
        });
        return { labels, data: values };
    }

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    var productsRatingsData = transformChartData(productsRatings, 'product_name', 'avg_rating');
    var ctx1 = document.getElementById('productsRatingsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: productsRatingsData.labels,
            datasets: [{
                label: 'Ratings',
                data: productsRatingsData.data,
                backgroundColor: productsRatingsData.labels.map(() => getRandomColor())
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var servicesRatingsData = transformChartData(servicesRatings, 'service_name', 'avg_rating');
    var ctx2 = document.getElementById('servicesRatingsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: servicesRatingsData.labels,
            datasets: [{
                label: 'Ratings',
                data: servicesRatingsData.data,
                backgroundColor: servicesRatingsData.labels.map(() => getRandomColor())
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Orders Per Day Doughnut Chart
    var ordersPerDayData = {
        labels: Object.keys(ordersPerDay).map(date => new Date(date).toLocaleDateString()),
        datasets: [{
            label: 'Orders',
            data: Object.values(ordersPerDay),
            backgroundColor: Object.keys(ordersPerDay).map(() => getRandomColor())
        }]
    };
    var ctx3 = document.getElementById('ordersPerDayChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: ordersPerDayData,
        options: {
            responsive: true
        }
    });

    // Customers Per Day Line Chart
    var customersPerDayData = {
        labels: Object.keys(customersPerDay).map(date => new Date(date).toLocaleDateString()),
        datasets: [{
            label: 'Customers',
            data: Object.values(customersPerDay),
            borderColor: '#FFCE56',
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            fill: true
        }]
    };
    var ctx4 = document.getElementById('customersPerDayChart').getContext('2d');
    new Chart(ctx4, {
        type: 'line',
        data: customersPerDayData,
        options: {
            scales: {
                x: {
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
