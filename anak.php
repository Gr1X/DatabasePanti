<?php
require_once('db.php');

// Menentukan kolom dan arah sorting
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id_anak';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Validasi kolom dan arah pengurutan
$valid_columns = ['id_anak', 'nama_anak', 'tanggal_lahir_anak', 'umur', 'jenis_kelamin_anak', 'pendidikan_anak'];
if (!in_array($sort_column, $valid_columns)) {
    $sort_column = 'id_anak';
}
$sort_order = ($sort_order == 'DESC') ? 'DESC' : 'ASC';

// Query SQL dengan umur dan sorting
$query = "
    WITH anak_data AS (
        SELECT 
            id_anak, 
            nama_anak, 
            tanggal_lahir_anak, 
            TIMESTAMPDIFF(YEAR, tanggal_lahir_anak, CURDATE()) AS umur,
            jenis_kelamin_anak, 
            pendidikan_anak
        FROM anak
    )
    SELECT * 
    FROM anak_data
    ORDER BY $sort_column $sort_order;
";

$result = $conn->query($query);

// Simpan hasil query ke dalam array
$data_anak = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_anak[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Anak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body>
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
                <a href="staff.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">User & Donasi</a>
            </li>
            <li>
                <a href="userdata.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Staff</a>
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
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All Anak</h1>
        </div>
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
                                <a href="?sort_column=id_anak&sort_order=<?php echo ($sort_column == 'id_anak' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    ID Anak <?php echo ($sort_column == 'id_anak') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=nama_anak&sort_order=<?php echo ($sort_column == 'nama_anak' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Nama Anak <?php echo ($sort_column == 'nama_anak') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=tanggal_lahir_anak&sort_order=<?php echo ($sort_column == 'tanggal_lahir_anak' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Tanggal Lahir <?php echo ($sort_column == 'tanggal_lahir_anak') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                <a href="?sort_column=umur&sort_order=<?php echo ($sort_column == 'umur' && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                    Umur <?php echo ($sort_column == 'umur') ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                </a>
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Jenis Kelamin
                            </th>
                            <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Pendidikan Anak
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php foreach ($data_anak as $anak): ?>
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($anak['id_anak']); ?></td>
                                <td class="p-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($anak['nama_anak']); ?></td>
                                <td class="p-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($anak['tanggal_lahir_anak']); ?></td>
                                <td class="p-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($anak['umur']); ?> tahun</td>
                                <td class="p-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($anak['jenis_kelamin_anak']); ?></td>
                                <td class="p-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($anak['pendidikan_anak']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>      
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
