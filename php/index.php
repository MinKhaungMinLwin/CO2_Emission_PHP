<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CO₂ Info and Calculator</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f9f9f9;
            --text: #333;
            --card: #fff;
        }

        .dark {
            --bg: #1e1e1e;
            --text: #e0e0e0;
            --card: #2c2c2c;
        }

        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            transition: 0.3s ease all;
        }

        header {
            background-color: #2c3e50;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-weight: 600;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .toggle-btn {
            background-color: #34495e;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        section {
            max-width: 900px;
            margin: 60px auto;
            padding: 30px;
            background-color: var(--card);
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            text-align: center;
            opacity: 0;
            transform: translateY(40px);
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-size: 2.4rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.1rem;
            margin: 20px 0;
        }

        .cta-btn {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 14px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .cta-btn:hover {
            background-color: #27ae60;
        }

        .facts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
            text-align: left;
        }

        .fact {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .fact:hover {
            transform: translateY(-5px);
        }

        img.graphic {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .facts {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="#home">🏠 Home</a>
        <a href="calculate.php">📊 Calculation</a>
        <a href="calculate.php#advice-section">🤖 AI Advice</a>
        <a href="#facts">📚 Learn CO₂ Facts</a>
    </nav>
    <button class="toggle-btn" onclick="toggleTheme()">🌗 Toggle Theme</button>
</header>

<section id="home">
    <h1>🌍 Welcome to the CO₂ Awareness Portal</h1>
    <p>Carbon dioxide (CO₂) is a major greenhouse gas. Learn how your choices impact the planet — and what you can do about it!</p>
    <a href="calculate.php">
        <button class="cta-btn">🚀 Start My CO₂ Impact Calculation</button>
    </a>
</section>

<section id="facts">
    <h1>📚 CO₂ Facts You Should Know</h1>
    <div class="facts">
        <div class="fact">
            <strong>🔥 Transportation</strong>
            <p>Vehicles account for over 14% of global greenhouse gas emissions.</p>
        </div>
        <div class="fact">
            <strong>⚡ Electricity</strong>
            <p>Fossil fuels still generate more than 60% of global electricity.</p>
        </div>
        <div class="fact">
            <strong>🥩 Food</strong>
            <p>Meat production releases significantly more CO₂ than plant-based diets.</p>
        </div>
        <div class="fact">
            <strong>🛍️ Plastic</strong>
            <p>Plastic production creates CO₂ and pollutes oceans for centuries.</p>
        </div>
    </div>
    <img class="graphic" src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Carbon_Dioxide_Emissions_by_Sector.png/800px-Carbon_Dioxide_Emissions_by_Sector.png" alt="CO2 by sector">
</section>

<section id="visuals">
    <h1>📈 CO₂ Emission Breakdown</h1>
    <canvas id="co2Chart" width="400" height="400" style="max-width: 600px; margin: auto;"></canvas>

    <h2 style="margin-top: 40px;">🧠 Infographic: Understanding CO₂ Sources</h2>
    <img class="graphic" src="https://climatechange.chicago.gov/sites/default/files/2021-11/ghg_chart_2021.png" alt="CO2 infographic by sector">
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('co2Chart').getContext('2d');
    const co2Chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Transportation', 'Electricity', 'Food', 'Plastic Waste'],
            datasets: [{
                label: 'CO₂ Emissions (kg)',
                data: [30, 25, 35, 10],
                backgroundColor: [
                    '#3498db',
                    '#9b59b6',
                    '#2ecc71',
                    '#e67e22'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    function toggleTheme() {
        document.body.classList.toggle("dark");
        localStorage.setItem("theme", document.body.classList.contains("dark") ? "dark" : "light");
    }

    window.onload = function () {
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark");
        }
    }
</script>

</body>
</html>
