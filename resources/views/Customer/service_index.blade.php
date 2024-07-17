<!DOCTYPE html>
<html>
<head>
    <title>Services</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        .card {
            margin: 0 auto;
            margin-top: 35px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Services</h2>
    <div id="services-container">
        @foreach($services as $service)
            <div class="card w-75 post mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $service->service_name }}</h5>
                    <p class="card-text">{{ $service->description }}</p>
                    <p class="card-text">${{ $service->price }}</p>
                </div>
                <img src="{{ asset('images/Services/' . $service->service_image) }}" class="card-img-bottom" alt="{{ $service->service_name }}">
            </div>
        @endforeach
    </div>
    <input type="hidden" id="start" value="{{ count($services) }}">
    <input type="hidden" id="rowperpage" value="{{ $rowperpage }}">
    <input type="hidden" id="totalrecords" value="{{ $totalrecords }}">
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function fetchData() {
            var start = Number($('#start').val());
            var totalRecords = Number($('#totalrecords').val());
            var rowPerPage = Number($('#rowperpage').val());

            if (start < totalRecords) {
                $.ajax({
                    url: "{{ route('services.getcustomer_service_index') }}",
                    data: { start: start },
                    dataType: 'json',
                    success: function(response) {
                        $('#services-container').append(response.html);
                        $('#start').val(start + rowPerPage);
                        checkWindowSize();
                    }
                });
            }
        }

        function checkWindowSize() {
            if ($(window).height() >= $(document).height()) {
                fetchData();
            }
        }

        checkWindowSize();

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                fetchData();
            }
        });
    });
</script>
</body>
</html>
