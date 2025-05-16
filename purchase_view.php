<?php
session_start();
include 'db.php'; // Include your database connection file

// Initialize a message variable
$message = "";

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!empty($_POST['purchase_ids'])) {
        // Sanitize the input to prevent SQL injection
        $idsToDelete = implode(",", array_map('intval', $_POST['purchase_ids']));
        $deleteSql = "DELETE FROM purchases WHERE purchase_id IN ($idsToDelete)";
        
        if ($conn->query($deleteSql) === TRUE) {
            $message = "Purchases deleted successfully."; // Set success message
        } else {
            $message = "Error deleting purchases: " . $conn->error; // Set error message
        }
    } else {
        $message = "No purchases selected for deletion."; // Set no selection message
    }
}

// Fetch purchases
$sql = "
    SELECT 
        purchases.purchase_id,
        client.full_name,
        client.address,
        client.contact,
        products.product_name,
        products.price,
        purchases.quantity,
        purchases.date,
        client.client_id
    FROM 
        purchases
    INNER JOIN 
        client ON purchases.client_id = client.client_id
    INNER JOIN 
        products ON purchases.product_id = products.product_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase History</title>
    <style>
        /* Body styling */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #2D3E50, #465E73); /* Gradient background */
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Header styling */
        header {
            background-color: #1E293B; /* Dark background */
            color: #F0AFAF; /* Light text */
            padding: 20px; /* Padding around the header */
            text-align: center; /* Center the text */
            font-size: 28px; /* Larger font size */
            margin-bottom: 20px; /* Spacing from the content */
            text-transform: uppercase; /* Uppercase text */
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Navigation bar styling */
        nav {
            background-color: #333; /* Dark background */
            width: 100%;
            text-align: center;
        }

        nav a {
            display: inline-block; /* Make links appear as inline-block */
            color: white; /* White text */
            text-align: center; /* Centered text */
            padding: 14px 16px; /* Padding for links */
            text-decoration: none; /* No underline */
            margin: 0 10px;
        }

        nav a:hover {
            background-color: #ddd; /* Light background on hover */
            color: black; /* Dark text on hover */
        }

        /* Table styling */
        table {
            width: 90%; /* Full width for table */
            margin: 20px auto; /* Center table with auto margins */
            border-collapse: collapse; /* Merge table borders */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        th, td {
            border: 1px solid #ddd; /* Light grey border */
            padding: 12px; /* Padding for table cells */
            text-align: left; /* Left align text */
        }

        th {
            background-color: #4CAF50; /* Green background for header */
            color: white; /* White text for header */
        }

        /* Form button styling */
        button {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background: linear-gradient(to right, #F2709C, #FF9472);
            color: #FFF;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(to right, #FF9472, #F2709C);
            transform: translateY(-2px);
        }

        /* Footer styling */
        footer {
            background-color: #f1f1f1; /* Light grey background */
            color: #555; /* Dark grey text */
            text-align: center; /* Centered text */
            padding: 10px; /* Padding around the footer */
            position: relative; /* Positioning for footer */
            bottom: 0; /* Stick to the bottom */
            width: 100%; /* Full width */
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow on top */
        }

        /* No data message styling */
        .message {
            text-align: center;
            color: #FFD700;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <header>Purchase History</header>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="purchase_view.php">View Purchases</a>
        <a href="add_product.php">Add Product</a>
    </nav>

    <div>
        <form method="POST" action="">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Select</th>
                        <th>Purchase ID</th>
                        <th>Client Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Client Details</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="purchase_ids[]" value="<?= htmlspecialchars($row['purchase_id']) ?>"></td>
                            <td><?= htmlspecialchars($row['purchase_id']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td>₱<?= number_format(htmlspecialchars($row['price']), 2) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><a href="contact_info.php?client_id=<?= htmlspecialchars($row['client_id']) ?>">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <button type="submit" name="delete">Delete Selected</button>
            <?php else: ?>
                <p class="message">No purchases found.</p>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($message)): ?>
        <script>
            alert("<?= addslashes($message) ?>");
        </script>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
