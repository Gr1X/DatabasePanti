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

// Function untuk memanggil jumlah anak berdasarkan nama staff
function getJumlahAnak($conn, $staff_name) {
    $query = "SELECT count_anak_by_staff_name(?) AS total_anak";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $staff_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_anak'] ?? 0;
}
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
    </div>
</nav>

<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All Staff</h1>
    </div>
</div>

<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">ID Staff</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Email</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">No Telp</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Alamat</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Bidang</th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Jumlah Anak Diasuh</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $jumlah_anak = getJumlahAnak($conn, $row['nama_lengkap']);
                                echo "<tr class='hover:bg-gray-100 dark:hover:bg-gray-700'>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['id_staff']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['nama_lengkap']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['email']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['no_telp']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['alamat']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>{$row['bidang_pengurus']}</td>";
                                echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>$jumlah_anak</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='p-4 text-center text-gray-500'>No data found</td></tr>";
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
