<?php

// Memanggil koneksi database
require_once('db.php');

// Menentukan kolom dan arah sorting
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id_staff';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Validasi kolom yang diizinkan
$valid_columns = ['id_staff', 'nama_lengkap', 'email', 'no_telp', 'alamat', 'bidang_pengurus'];
if (!in_array($sort_column, $valid_columns)) {
    $sort_column = 'id_staff';
}
$sort_order = ($sort_order == 'DESC') ? 'DESC' : 'ASC';

// Query SQL dengan sorting
$query = "
    WITH staff_data AS (
        SELECT s.id_staff, s.nama_lengkap, s.email, s.no_telp, s.alamat, b.bidang_pengurus
        FROM staff s
        JOIN bidang b ON s.id_bidang = b.id_bidang
    )
    SELECT * FROM staff_data
    ORDER BY $sort_column $sort_order;
";

$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-700">
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

<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All Staff</h1>
        </div>
        <!-- Form untuk pencarian -->
        <form method="GET" class="flex items-center space-x-3">
            <input type="text" name="search" class="block w-full px-4 py-2 border rounded-lg text-gray-900" placeholder="Search staff..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Search</button>
        </form>
    </div>
</div>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=id_staff&sort_order=<?php echo ($sort_column == 'id_staff' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    ID Staff <?php echo ($sort_column == 'id_staff') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=nama_lengkap&sort_order=<?php echo ($sort_column == 'nama_lengkap' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Nama <?php echo ($sort_column == 'nama_lengkap') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=email&sort_order=<?php echo ($sort_column == 'email' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Email <?php echo ($sort_column == 'email') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=no_telp&sort_order=<?php echo ($sort_column == 'no_telp' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    No Telp <?php echo ($sort_column == 'no_telp') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=alamat&sort_order=<?php echo ($sort_column == 'alamat' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Alamat <?php echo ($sort_column == 'alamat') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=bidang_pengurus&sort_order=<?php echo ($sort_column == 'bidang_pengurus' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Bidang <?php echo ($sort_column == 'bidang_pengurus') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-100 dark:hover:bg-gray-700'>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['id_staff']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['nama_lengkap']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['email']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['no_telp']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['alamat']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['bidang_pengurus']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='p-4 text-center text-gray-500'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
