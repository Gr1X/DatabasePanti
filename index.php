<?php
require_once('db.php');


// Time
// Query untuk mendapatkan total donasi per bulan, termasuk bulan tanpa donasi
$query6 = "
    WITH RECURSIVE AllMonths AS (
        SELECT 
            DATE_FORMAT(MIN(tgl_donasi), '%Y-%m-01') AS month_date
        FROM donasi
        UNION ALL
        SELECT DATE_ADD(month_date, INTERVAL 1 MONTH)
        FROM AllMonths
        WHERE month_date < LAST_DAY((SELECT MAX(tgl_donasi) FROM donasi))
    ),
    MonthlyDonations AS (
        SELECT 
            DATE_FORMAT(tgl_donasi, '%Y-%m') AS month,
            SUM(jumlah_donasi) AS total_donasi
        FROM donasi
        GROUP BY DATE_FORMAT(tgl_donasi, '%Y-%m')
    )
    SELECT 
        DATE_FORMAT(AllMonths.month_date, '%Y-%m') AS month,
        COALESCE(MonthlyDonations.total_donasi, 0) AS total_donasi,
        SUM(COALESCE(MonthlyDonations.total_donasi, 0)) 
            OVER (ORDER BY AllMonths.month_date) AS cumulative_donations
    FROM AllMonths
    LEFT JOIN MonthlyDonations ON DATE_FORMAT(AllMonths.month_date, '%Y-%m') = MonthlyDonations.month
    ORDER BY AllMonths.month_date;
";

$query_transaksi = "
    SELECT 
        dl.id_donasi, 
        dl.jumlah_donasi, 
        dl.log_timestamp, 
        p.nama_program
    FROM donasi_log dl
    JOIN donasi d ON d.id_donasi = dl.id_donasi
    JOIN program p ON d.id_program = p.id_program
    ORDER BY dl.log_timestamp DESC
    LIMIT 5;
";

$query_counts = "
    SELECT 
        (SELECT COUNT(*) FROM user) AS total_user,
        (SELECT COUNT(*) FROM anak) AS total_anak,
        (SELECT COUNT(*) FROM staff) AS total_staff;
";

// View DonasiBulan
$query7 = "SELECT nama_program, bulan, total_donasi FROM DonasiBulanan ORDER BY nama_program, bulan;";
$result = $conn->query($query7);

$data_donasi = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $program = $row['nama_program'];
        $bulan = $row['bulan'];
        $total_donasi = $row['total_donasi'];

        $data_donasi[$program][$bulan] = $total_donasi;
    }
}


$result_counts = $conn->query($query_counts);
$count_data = $result_counts->fetch_assoc();

$result_transaksi = $conn->query($query_transaksi);
$transaksi_data = [];
if ($result_transaksi->num_rows > 0) {
    while ($row = $result_transaksi->fetch_assoc()) {
        $transaksi_data[] = $row;
    }
}

$result = $conn->query($query6);

$total_donations_amount = 0; 
$donation_data = []; 
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $donation_data[] = $row;
        $total_donations_amount += $row['total_donasi'];
    }
}
$conn->close();
?>


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
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    cream: '#FCFBF1',
                    lightGreen: '#C7A59D',
                    yellowAccent: '#788D7C',
                    greenPrimary: '#BC705B',
                    greenDark: '#8BA172',
                }
            }
        }
    }
</script>

<nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Admin Dashboard</span>
            </div>
            <div class="">
                <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <li>
                    <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Dashboard</a>
                </li>
                <li>
                    <a href="userdata.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">User & Donasi</a>
                </li>
                <li>
                    <a href="staff.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Staff</a>
                </li>
                <li>
                    <a href="anak.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Anak</a>
                </li>
                </ul>
            </div>
        </div>
    </nav>

<body>
    <div class="grid grid-cols-3 gap-4 m-4 mb-2">
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-400">User</h3>
            <p class="text-3xl font-bold text-white mt-2">
                <?php echo number_format($count_data['total_user'], 0, ',', '.'); ?>
            </p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-400">Anak</h3>
            <p class="text-3xl font-bold text-white mt-2">
                <?php echo number_format($count_data['total_anak'], 0, ',', '.'); ?>
            </p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-400">Staff</h3>
            <p class="text-3xl font-bold text-white mt-2">
                <?php echo number_format($count_data['total_staff'], 0, ',', '.'); ?>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6 text-custom-500 bg-custom-400 mx-4">
        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Rp <?php echo number_format($total_donations_amount, 0, ',', '.'); ?></h1>
                    <p class="text-sm text-white">Total Donasi</p>
                </div>
            </div>

            <canvas id="salesChart" class="mt-4"></canvas>

            <div class="flex justify-between text-sm text-white mt-4">
                <span>Dalam 1 Tahun Terakhir</span>
                <a href="#" class="text-custom-200 hover:underline"></a>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold text-white">Transaksi Terbaru</h1>
            </div>
            <div class="mt-4">
                <?php if (count($transaksi_data) > 0): ?>
                    <?php foreach ($transaksi_data as $transaksi): ?>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <img src="https://via.placeholder.com/50" alt="Program" class="w-10 h-10 rounded border border-custom-75">
                                <div>
                                    <h2 class="text-sm font-semibold text-white"><?php echo htmlspecialchars($transaksi['nama_program']); ?></h2>
                                    <p class="text-xs text-white"><?php echo date('d M Y, H:i', strtotime($transaksi['log_timestamp'])); ?></p>
                                </div>
                            </div>
                            <span class="text-lg font-bold text-white">Rp <?php echo number_format($transaksi['jumlah_donasi'], 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-white">Tidak ada transaksi terbaru.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-gray-800 rounded-lg shadow mx-4">
    <h1 class="text-lg font-bold text-white">Statistik Donasi Bulanan</h1>
        <div class="mt-4 space-y-4">
            <?php foreach ($data_donasi as $program => $bulan_donasi): ?>
                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($program) ?></h2>
                    <table class="min-w-full mt-2 divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="p-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Bulan</th>
                                <th scope="col" class="p-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Total Donasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            <?php foreach ($bulan_donasi as $bulan => $total): ?>
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="p-3 text-sm text-gray-900 dark:text-white"><?= htmlspecialchars($bulan) ?></td>
                                    <td class="p-3 text-sm text-gray-900 dark:text-white">Rp <?= number_format($total, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 mx-4 mb-10">
        <div class="items-center justify-between lg:flex">
            <div class="mb-4 lg:mb-0">
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Table Panti</h3>
                <span class="text-base font-normal text-gray-500 dark:text-gray-400">ini adalah list dari data-data panti.</span>
            </div>
            <div class="items-center sm:flex">
                <div class="flex items-center"></div>
                <div date-rangepicker class="flex items-center space-x-4">
                    <div class="relative">
                        <input name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 w-1/4" placeholder="Search">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col mt-6">
          <div class="flex justify-between items-center">
              <h3 id="table-title" class="text-xl font-bold text-gray-900 dark:text-white">Transactions</h3>
              <div class="flex space-x-2">
                  <button id="btn-prev" class="bg-gray-300 text-gray-900 px-3 py-1 rounded-md">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                      </svg>
                  </button>
                  <button id="btn-next" class="bg-gray-300 text-gray-900 px-3 py-1 rounded-md">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                  </button>
              </div>
          </div>

          
          <div id="table-container" class="mt-6">
              <div id="table-transactions" class="table-slide">
                  <div class="overflow-x-auto rounded-lg">
                      <div class="inline-block min-w-full align-middle">
                          <div class="overflow-hidden shadow sm:rounded-lg">
                              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                  <thead class="bg-gray-50 dark:bg-gray-700">
                                      <tr>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Transaction</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Date & Time</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Amount</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Reference number</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Payment method</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Status</th>
                                      </tr>
                                  </thead>
                                  <tbody class="bg-white dark:bg-gray-800">
                                      <tr>
                                          <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">Payment from <span class="font-semibold">Bonnie Green</span></td>
                                          <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">Apr 23, 2021</td>
                                          <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">$2300</td>
                                          <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">0047568936</td>
                                          <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">Visa</td>
                                          <td class="p-4 whitespace-nowrap">
                                              <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400">Completed</span>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>

              <div id="table-donations" class="table-slide hidden">
                  <div class="overflow-x-auto rounded-lg">
                      <div class="inline-block min-w-full align-middle">
                          <div class="overflow-hidden shadow sm:rounded-lg">
                              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                  <thead class="bg-gray-50 dark:bg-gray-700">
                                      <tr>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Donor</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Date</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Amount</th>
                                      </tr>
                                  </thead>
                                  <tbody class="bg-white dark:bg-gray-800">
                                      <tr>
                                          <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">John Doe</td>
                                          <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">Mar 12, 2023</td>
                                          <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">$500</td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>

              <div id="table-staff" class="table-slide hidden">
                  <div class="overflow-x-auto rounded-lg">
                      <div class="inline-block min-w-full align-middle">
                          <div class="overflow-hidden shadow sm:rounded-lg">
                              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                  <thead class="bg-gray-50 dark:bg-gray-700">
                                      <tr>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Staff Name</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Role</th>
                                          <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Status</th>
                                      </tr>
                                  </thead>
                                  <tbody class="bg-white dark:bg-gray-800">
                                      <tr>
                                          <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">Alice Smith</td>
                                          <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">Manager</td>
                                          <td class="p-4 whitespace-nowrap">
                                              <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400">Active</span>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    //   const tables = ["table-transactions", "table-donations", "table-staff"];
    // const titles = ["Transactions", "Donations", "Staff"];
    // let currentIndex = 0;

    // document.getElementById("btn-prev").addEventListener("click", function () {
    //     currentIndex = (currentIndex - 1 + tables.length) % tables.length;
    //     updateTable();
    // });

    // document.getElementById("btn-next").addEventListener("click", function () {
    //     currentIndex = (currentIndex + 1) % tables.length;
    //     updateTable();
    // });

    // function updateTable() {
    //     tables.forEach((id, index) => {
    //         const table = document.getElementById(id);
    //         table.classList.toggle("hidden", index !== currentIndex);
    //     });
    //     document.getElementById("table-title").textContent = titles[currentIndex];
    // }


    // Statistik Grafik
// Data hasil query PHP
const donationData = <?php echo json_encode($donation_data); ?>;

// Ekstrak data untuk grafik
const labels = donationData.map(data => data.month); // Bulan
const totalDonations = donationData.map(data => data.total_donasi); // Total donasi
const cumulativeDonations = donationData.map(data => data.cumulative_donations); // Donasi kumulatif

// Statistik Grafik Total dan Kumulatif Donasi
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels, // Label sumbu X
        datasets: [
            {
                label: 'Total Donasi per Bulan',
                data: totalDonations,
                borderColor: '#668C64',
                backgroundColor: 'rgba(102, 140, 100, 0.5)',
                tension: 0.4,
            },
            {
                label: 'Donasi Kumulatif',
                data: cumulativeDonations,
                borderColor: '#DEAE48',
                backgroundColor: 'rgba(222, 174, 72, 0.5)',
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
                    color: '#FFFDF1',
                },
            },
        },
        scales: {
            x: {
                ticks: { color: '#FFFDF1' },
            },
            y: {
                ticks: { color: '#FFFDF1' },
            },
        },
    },
});


    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
</body>
</html>
