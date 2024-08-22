<?php
session_start();
include '../includes/db_connection.php'; // Pastikan jalur ini benar

// Fetch borrowed books data from the database
$query = "
    SELECT b.title, u.name, bl.borrow_date, bl.return_date
    FROM borrowings bl
    JOIN books b ON bl.book_id = b.id
    JOIN users u ON bl.user_id = u.user_id
    ORDER BY bl.borrow_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle CSV export
if (isset($_POST['export_csv'])) {
    $filename = "borrowed_books_report_" . date('Ymd') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen("php://output", "w");
    fputcsv($output, array('Book Title', 'Borrower Name', 'Borrow Date', 'Return Date'));

    foreach ($borrowed_books as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Borrowed Books Report</title>
    <style>
        body {
            background-color: #1f2937;
            color: #f9fafb;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <?php include '../includes/header.php'; ?>

    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-4">Borrowed Books Report</h1>

            <!-- Export CSV Form -->
            <form method="post" class="mb-4">
                <button type="submit" name="export_csv" class="bg-green-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-700">
                    Export to CSV
                </button>
            </form>

            <!-- Borrowed Books Report Table -->
            <table class="mt-4 w-full border border-gray-700">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="px-4 py-2 border-b border-gray-600">Book Title</th>
                        <th class="px-4 py-2 border-b border-gray-600">Borrower Name</th>
                        <th class="px-4 py-2 border-b border-gray-600">Borrow Date</th>
                        <th class="px-4 py-2 border-b border-gray-600">Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowed_books as $row): ?>
                        <tr class="bg-gray-700">
                            <td class="px-4 py-2 border-b border-gray-600 text-center"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center"><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                            <td class="px-4 py-2 border-b border-gray-600 text-center"><?php echo htmlspecialchars($row['return_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
