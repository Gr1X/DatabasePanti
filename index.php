<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6 text-custom-500 bg-custom-400">
        <!-- Grafik Penjualan Mingguan -->
        <div class="bg-custom-50 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-custom-500">$45,385</h1>
                    <p class="text-sm text-custom-375">Sales this week</p>
                </div>
                <span class="text-custom-200 text-sm font-semibold">12.5% ↑</span>
            </div>
            <canvas id="salesChart" class="mt-4"></canvas>
            <div class="flex justify-between text-sm text-custom-375 mt-4">
                <span>Last 7 days</span>
                <a href="#" class="text-custom-200 hover:underline">Sales Report</a>
            </div>
        </div>

        <!-- Statistik Bulanan -->
        <div class="bg-custom-50 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold text-custom-500">Statistics this month</h1>
                <span class="text-custom-375 text-sm">Top products</span>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <img src="https://via.placeholder.com/50" alt="Product" class="w-10 h-10 rounded border border-custom-75">
                        <div>
                            <h2 class="text-sm font-semibold text-custom-100">iPhone 14 Pro</h2>
                            <p class="text-xs text-custom-200">↑ 2.5% vs last month</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-custom-300">$445,467</span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <img src="https://via.placeholder.com/50" alt="Product" class="w-10 h-10 rounded border border-custom-75">
                        <div>
                            <h2 class="text-sm font-semibold text-custom-100">Apple iMac 27"</h2>
                            <p class="text-xs text-custom-200">↑ 12.5% vs last month</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-custom-300">$256,982</span>
                </div>
            </div>
            <div class="flex justify-between text-sm text-custom-375 mt-4">
                <span>Last 7 days</span>
                <a href="#" class="text-custom-200 hover:underline">Full Report</a>
            </div>
        </div>
    </div>

    
            <div class="bg-gray-800 p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-400">New Products</h3>
                <p class="text-3xl font-bold text-white mt-2">2,340</p>
                <p class="text-sm text-green-500">↑ 12.5% Since last month</p>
            </div>
            <!-- Card 2 -->
            <div class="bg-gray-800 p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-400">Users</h3>
                <p class="text-3xl font-bold text-white mt-2">2,340</p>
                <p class="text-sm text-green-500">↑ 3.4% Since last month</p>
            </div>
            
        </div>  

        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
        <table id="search-table" class="px-6">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            Company Name
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Ticker
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Stock Price
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Market Capitalization
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Apple Inc.</td>
                    <td>AAPL</td>
                    <td>$192.58</td>
                    <td>$3.04T</td>
                </tr>
                <tr>
                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">Microsoft Corporation</td>
                    <td>MSFT</td>
                    <td>$340.54</td>
                    <td>$2.56T</td>
                </tr>
            </tbody>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'],
                        datasets: [
                            {
                                label: 'Revenue',
                                data: [6200, 6100, 6050, 6400, 6300, 6200, 6100],
                                borderColor: '#668C64', // Hijau muda
                                backgroundColor: 'rgba(102, 140, 100, 0.5)', // Transparan hijau muda
                                tension: 0.4,
                            },
                            {
                                label: 'Revenue (previous period)',
                                data: [6600, 6650, 6620, 6500, 6450, 6400, 6350],
                                borderColor: '#DEAE48', // Oranye
                                backgroundColor: 'rgba(222, 174, 72, 0.5)', // Transparan oranye
                                tension: 0.4,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#FFFDF1', // Putih lebih gelap
                                },
                            },
                        },
                        scales: {
                            x: {
                                ticks: { color: '#FFFDF1' }, // Putih lebih gelap
                            },
                            y: {
                                ticks: { color: '#FFFDF1' }, // Putih lebih gelap
                            },
                        },
                    },
                });
            </script>
        </script>
</body>
</html>