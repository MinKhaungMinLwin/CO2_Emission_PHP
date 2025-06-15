<?php
// Process POST and send JSON response if AJAX, otherwise store $result for initial load display
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array(
        "meal_kg" => floatval($_POST["meal_kg"]),
        "meal_type" => $_POST["meal_type"],
        "electricity_kwh" => floatval($_POST["electricity_kwh"]),
        "plastic_kg" => floatval($_POST["plastic_kg"]),
        "distance_km" => floatval($_POST["distance_km"]),
        "vehicle_type" => $_POST["vehicle_type"]
    );

    $jsonData = json_encode($data);

    $ch = curl_init('http://127.0.0.1:5000/predict');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        $result = ["error" => "cURL error: $error"];
    } else {
        $result = json_decode($response, true);
    }

    // If AJAX request, return JSON and exit
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>CO₂ Emission Calculator</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg: #f3f4f6;
            --text: #333;
            --card: #fff;
            --primary: #2ecc71;
            --primary-dark: #27ae60;
            --error: #e74c3c;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: var(--bg);
            color: var(--text);
            transition: all 0.3s ease;
        }

        header {
            background-color: #2c3e50;
            padding: 15px 30px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        nav a {
            color: #fff;
            margin-right: 20px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--primary);
        }

        main {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background-color: var(--card);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #2c3e50;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--primary-dark);
        }

        .result {
            margin-top: 30px;
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 8px;
        }

        #advice-section {
            margin-top: 30px;
            background: #e9f7ef;
            padding: 20px;
            border-radius: 10px;
            border-left: 6px solid var(--primary);
            font-size: 1rem;
        }

        #advice-section ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        #advice-section li {
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #advice-section li::before {
            content: "✅";
            color: var(--primary-dark);
            font-weight: bold;
            margin-right: 6px;
        }

        #chart-container {
            margin-top: 40px;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="index.php">🏠 Home</a>
        <a href="calculate.php">📊 Calculation</a>
        <a href="#advice-section">🤖 AI Advice</a>
    </nav>
</header>

<main>
    <h1>🌍 CO₂ Emission Calculator</h1>

    <form id="co2Form" method="POST" action="calculate.php">
        <label for="meal_kg">Meal amount (kg):</label>
        <input type="number" name="meal_kg" id="meal_kg" step="0.1" required>

        <label for="meal_type">Meal type:</label>
        <select name="meal_type" id="meal_type" required>
            <option value="meat">Meat</option>
            <option value="vegetarian">Vegetarian</option>
            <option value="vegan">Vegan</option>
        </select>

        <label for="electricity_kwh">Electricity usage (kWh):</label>
        <input type="number" name="electricity_kwh" id="electricity_kwh" step="0.1" required>

        <label for="plastic_kg">Plastic used (kg):</label>
        <input type="number" name="plastic_kg" id="plastic_kg" step="0.1" required>

        <label for="distance_km">Distance traveled (km):</label>
        <input type="number" name="distance_km" id="distance_km" step="0.1" required>

        <label for="vehicle_type">Vehicle type:</label>
        <select name="vehicle_type" id="vehicle_type" required>
            <option value="car">Car</option>
            <option value="bus">Bus</option>
            <option value="motorcycle">Motorcycle</option>
            <option value="bicycle">Bicycle</option>
            <option value="electric_car">Electric Car</option>
        </select>

        <button type="submit">Calculate</button>
    </form>

    <div class="result" id="result-block" style="display:none;">
        <h2>📊 CO₂ Emission Result</h2>
        <p><strong>Total Emissions:</strong> <span id="totalEmission"></span> kg CO₂</p>
        <p><strong>Cluster:</strong> <span id="cluster"></span></p>

        <div id="advice-section">
            <h3>🤖 AI Advice</h3>
            <ul id="advice-list"></ul>
        </div>

        <div id="chart-container">
            <canvas id="emissionChart" width="400" height="250"></canvas>
        </div>
    </div>
</main>

<script>
    // Smooth scroll is enabled by CSS scroll-behavior

    // Animate on page load is done via CSS animation fadeInUp

    const form = document.getElementById('co2Form');
    const resultBlock = document.getElementById('result-block');
    const totalEmissionEl = document.getElementById('totalEmission');
    const clusterEl = document.getElementById('cluster');
    const adviceList = document.getElementById('advice-list');
    let chart = null;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // Send POST request via fetch (AJAX)
        try {
            const response = await fetch('calculate.php', {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams(data)
            });

            const result = await response.json();

            if (result.error) {
                alert("Error: " + result.error);
                resultBlock.style.display = 'none';
                return;
            }

            // Show results
            resultBlock.style.display = 'block';
            totalEmissionEl.textContent = result.total ?? 'N/A';
            clusterEl.textContent = result.cluster ?? 'N/A';

            // Update AI advice list
            adviceList.innerHTML = '';
            if (result.advice) {
                const lines = result.advice.split('\n').filter(line => line.trim() !== '');
                lines.forEach(line => {
                    const li = document.createElement('li');
                    li.textContent = line.replace(/^✅\s*/, '');
                    adviceList.appendChild(li);
                });
            }

            // Prepare chart data: Example breakdown (adjust as needed based on API response)
            // Let's assume API returns separate emission values for meal, electricity, plastic, travel
            // If not, fallback to total only
            const chartLabels = ['Meal', 'Electricity', 'Plastic', 'Travel', 'Total'];
            const chartData = [
                parseFloat(result.meal_emission ?? 0),
                parseFloat(result.electricity_emission ?? 0),
                parseFloat(result.plastic_emission ?? 0),
                parseFloat(result.travel_emission ?? 0),
                parseFloat(result.total ?? 0)
            ];

            

            // If no breakdown, just show total emission as a single bar
            if (chartLabels.length === 0) {
                chartLabels.push('Total Emission');
                chartData.push(result.total ?? 0);
            }

            if (chart) {
                chart.destroy();
            }

            const ctx = document.getElementById('emissionChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'kg CO₂',
                        data: chartData,
                        backgroundColor: [
                            'rgba(46, 204, 113, 0.7)', // Meal
                            'rgba(52, 152, 219, 0.7)', // Electricity
                            'rgba(241, 196, 15, 0.7)', // Plastic
                            'rgba(230, 126, 34, 0.7)', // Travel
                            'rgba(231, 76, 60, 0.7)'   // Total
                        ],
                        borderColor: 'rgba(39, 174, 96, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Scroll to result block smoothly
            resultBlock.scrollIntoView({ behavior: 'smooth' });

        } catch (error) {
            alert("Request failed: " + error.message);
            resultBlock.style.display = 'none';
        }
    });

    // If initial page load has a result (non-AJAX), show it + chart
    <?php if ($result && !isset($result['error'])): ?>
        document.addEventListener('DOMContentLoaded', () => {
            resultBlock.style.display = 'block';
            totalEmissionEl.textContent = <?= json_encode($result['total']) ?>;
            clusterEl.textContent = <?= json_encode($result['cluster']) ?>;

            const adviceText = <?= json_encode($result['advice'] ?? '') ?>;
            const lines = adviceText.split('\n').filter(line => line.trim() !== '');
            adviceList.innerHTML = '';
            lines.forEach(line => {
                const li = document.createElement('li');
                li.textContent = line.replace(/^✅\s*/, '');
                adviceList.appendChild(li);
            });

            // Chart data for initial load, same logic as above
            const chartLabels = ['Meal', 'Electricity', 'Plastic', 'Travel', 'Total'];
            const chartData = [
                <?= json_encode(floatval($result['meal_emission'] ?? 0)) ?>,
                <?= json_encode(floatval($result['electricity_emission'] ?? 0)) ?>,
                <?= json_encode(floatval($result['plastic_emission'] ?? 0)) ?>,
                <?= json_encode(floatval($result['travel_emission'] ?? 0)) ?>,
                <?= json_encode(floatval($result['total'] ?? 0)) ?>
            ];
            
            if (chartLabels.length === 0) {
                chartLabels.push('Total Emission');
                chartData.push(<?= json_encode($result['total']) ?>);
            }

            const ctx = document.getElementById('emissionChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'kg CO₂',
                        data: chartData,
                        backgroundColor: [
                            'rgba(46, 204, 113, 0.7)', // Meal
                            'rgba(52, 152, 219, 0.7)', // Electricity
                            'rgba(241, 196, 15, 0.7)', // Plastic
                            'rgba(230, 126, 34, 0.7)', // Travel
                            'rgba(231, 76, 60, 0.7)'   // Total
                        ],
                        borderColor: 'rgba(39, 174, 96, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        });
    <?php endif; ?>
</script>

</body>
</html>
