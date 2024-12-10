<?php
require_once('db.php');

// Menentukan kolom dan arah sorting
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id_donasi';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Validasi kolom dan arah pengurutan
$valid_columns = ['id_donasi', 'nama_donatur', 'email', 'tgl_donasi', 'jumlah_donasi', 'nama_program'];
if (!in_array($sort_column, $valid_columns)) {
    $sort_column = 'id_donasi';
}
$sort_order = ($sort_order == 'DESC') ? 'DESC' : 'ASC';

// Query SQL untuk mengambil data dan mengurutkannya
$query4 = "
    WITH sorting_data AS (
        SELECT d.id_donasi, u.nama_lengkap AS nama_donatur, u.email, d.tgl_donasi, d.jumlah_donasi, p.nama_program
        FROM donasi d
        JOIN user u ON d.id_user = u.id_user
        JOIN program p ON d.id_program = p.id_program
    )
    SELECT * FROM sorting_data
    ORDER BY $sort_column $sort_order;
";

$result1 = $conn->query($query4);

// cursor
$search_result = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search_key'])) {
    $search_key = $_POST['search_key'];

    $stmt = $conn->prepare("CALL search_user_by_keyword(?)");
    $stmt->bind_param("s", $search_key);
    $stmt->execute();

    // Ambil hasil dari stored procedure
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $search_result[] = $row['Search Result'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User & Donasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <div>
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">All User & Donasi</h1>
        </div>
        <form action="" method="POST" class="mt-4 sm:mt-0">
            <div class="flex items-center">
                <input type="text" name="search_key" placeholder="Cari nama user..." class="p-2 border border-gray-300 rounded-lg">
                <button type="submit" class="ml-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Cari</button>
            </div>
        </form>
    </div>

    <?php if (!empty($search_result)): ?>
        <div class="p-4 bg-gray-50 border rounded-lg text-gray-800 dark:bg-gray-700 dark:text-white">
            <h2 class="text-lg font-bold mb-2">Hasil Pencarian:</h2>
            <ul>
                <?php foreach ($search_result as $result): ?>
                    <li><?php echo htmlspecialchars($result); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="p-4 bg-gray-50 border rounded-lg text-gray-800 dark:bg-gray-700 dark:text-white">
            <p>No Data Found</p>
        </div>
    <?php endif; ?>

    <div class="flex flex-col mt-6">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <?php foreach ($valid_columns as $column): ?>
                                    <th class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        <a href="?sort_column=<?php echo $column; ?>&sort_order=<?php echo ($sort_column == $column && $sort_order == 'ASC') ? 'DESC' : 'ASC'; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $column)); ?>
                                            <?php echo ($sort_column == $column) ? ($sort_order == 'ASC' ? '↑' : '↓') : ''; ?>
                                        </a>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            <?php
                            if ($result1->num_rows > 0) {
                                while ($row = $result1->fetch_assoc()) {
                                    echo "<tr class='hover:bg-gray-100 dark:hover:bg-gray-700'>";
                                    foreach ($valid_columns as $column) {
                                        echo "<td class='p-4 text-sm font-normal text-gray-900 dark:text-white'>" . htmlspecialchars($row[$column]) . "</td>";
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='" . count($valid_columns) . "' class='p-4 text-center text-gray-500'>No data found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
