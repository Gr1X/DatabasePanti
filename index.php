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
<body>
    <div class="grid grid-cols-3 gap-4 m-4 mb-2">
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-400">User</h3>
            <p class="text-3xl font-bold text-white mt-2">234</p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-400">Anak</h3>
            <p class="text-3xl font-bold text-white mt-2">34</p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-400">Staff</h3>
            <p class="text-3xl font-bold text-white mt-2">13</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6 text-custom-500 bg-custom-400 mx-4">
        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Rp 450.385</h1>
                    <p class="text-sm text-white">Total Donasi Per Bulan</p>
                </div>
            </div>

            <canvas id="salesChart" class="mt-4"></canvas>

            <div class="flex justify-between text-sm text-white mt-4">
                <span>Dalam 1 Bulan Terakhir</span>
                <a href="#" class="text-custom-200 hover:underline"></a>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold text-white">Transaksi</h1>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <img src="https://via.placeholder.com/50" alt="Product" class="w-10 h-10 rounded border border-custom-75">
                        <div>
                            <h2 class="text-sm font-semibold text-white">Nama Program</h2>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-white">Rp 445,467</span>
                </div>
                <div class="flex justify-between text-sm text-white mt-4">
                    <span>Dalam 7 Hari terakhir</span>
                    <a href="#" class="text-custom-200 hover:underline">Lihat selengkapnya</a>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 mx-4 mb-10">
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
          <!-- Header dengan tombol slider -->
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

          <!-- Kontainer tabel dengan slider -->
          <div id="table-container" class="mt-6">
              <!-- Tabel Transactions -->
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

              <!-- Tabel Donations -->
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

              <!-- Tabel Staff -->
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      const tables = ["table-transactions", "table-donations", "table-staff"];
    const titles = ["Transactions", "Donations", "Staff"];
    let currentIndex = 0;

    document.getElementById("btn-prev").addEventListener("click", function () {
        currentIndex = (currentIndex - 1 + tables.length) % tables.length;
        updateTable();
    });

    document.getElementById("btn-next").addEventListener("click", function () {
        currentIndex = (currentIndex + 1) % tables.length;
        updateTable();
    });

    function updateTable() {
        tables.forEach((id, index) => {
            const table = document.getElementById(id);
            table.classList.toggle("hidden", index !== currentIndex);
        });
        document.getElementById("table-title").textContent = titles[currentIndex];
    }

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'],
                datasets: [
                    {
                        label: 'Revenue',
                        data: [6200, 6100, 6050, 6400, 6300, 6200, 6100],
                        borderColor: '#668C64',
                        backgroundColor: 'rgba(102, 140, 100, 0.5)',
                        tension: 0.4,
                    },
                    {
                        label: 'Revenue (previous period)',
                        data: [6600, 6650, 6620, 6500, 6450, 6400, 6350],
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
