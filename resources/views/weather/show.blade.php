<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Gemini</title>
</head>

<body>

    <div class="container py-5">
        <h2 class="mb-4">Weather Forecast</h2>
        <form id="weather-form" class="mb-4">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="city" id="city" class="form-control"
                        placeholder="City (e.g. Lahore)" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="country" id="country" class="form-control"
                        placeholder="Country (e.g. Pakistan)" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Get Weather</button>
                </div>
            </div>
        </form>
        <div id="weather-result" class="mt-4"></div>
        <canvas id="weatherChart" style="max-width: 600px; margin-top: 40px;"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        async function showWeather(city, country) {
            const res = await fetch(`/weather?city=${encodeURIComponent(city)}&country=${encodeURIComponent(country)}`);
            const json = await res.json();
            const resultDiv = document.getElementById('weather-result');
            if (json.weather) {
                resultDiv.innerHTML = `
                <div class='card shadow-lg p-4 mb-3 border-0' style='background: #f8fafc;'>
                    <h4 class='mb-3 text-primary'>${json.location}</h4>
                    <div class='row'>
                        <div class='col-md-6'>
                            <ul class='list-unstyled mb-0'>
                                <li><strong>Temperature:</strong> <span class='badge bg-info'>${json.weather.temperature} °C</span></li>
                                <li><strong>Windspeed:</strong> <span class='badge bg-warning text-dark'>${json.weather.windspeed} m/s</span></li>
                                <li><strong>Wind Direction:</strong> <span class='badge bg-success'>${json.weather.winddirection}°</span></li>
                                <li><strong>Time:</strong> <span class='badge bg-secondary'>${json.weather.time}</span></li>
                            </ul>
                        </div>
                        <div class='col-md-6 d-flex align-items-center justify-content-center'>
                            <canvas id='weatherChart' style='max-width: 300px;'></canvas>
                        </div>
                    </div>
                </div>
            `;
                // Chart
                const ctx = document.getElementById('weatherChart').getContext('2d');
                if (window.weatherChart) window.weatherChart.destroy();
                window.weatherChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Temperature', 'Windspeed', 'Wind Direction'],
                        datasets: [{
                            label: 'Current Weather',
                            data: [json.weather.temperature, json.weather.windspeed, json.weather
                                .winddirection
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
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
            } else {
                resultDiv.innerHTML =
                    `<div class='alert alert-danger'>${json.error || 'Weather data not available.'}</div>`;
                if (window.weatherChart) window.weatherChart.destroy();
            }
        }

        document.getElementById('weather-form').onsubmit = function(e) {
            e.preventDefault();
            const city = document.getElementById('city').value;
            const country = document.getElementById('country').value;
            showWeather(city, country);
        };

        // Show default weather for Lahore, Pakistan on page load
        showWeather('Lahore', 'Pakistan');
    </script>
</body>

</html>
