<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch recently signed-up clients
$sql_clients = "SELECT * FROM client ORDER BY signup_date DESC LIMIT 1000"; // Adjust LIMIT as needed
$result_clients = $conn->query($sql_clients);

if (!$result_clients) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Dashboard</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #2D3E50, #465E73); /* Gradient background */
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Header Styling */
        header {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #F0AFAF;
            background-color: #1E293B; /* Matches form design */
            border-radius: 10px;
            margin-bottom: 30px;
        }

        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 10px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background: linear-gradient(to right, #F2709C, #FF9472); /* Button gradient */
            font-weight: bold;
            transition: all 0.3s ease;
        }

        nav a:hover {
            background: linear-gradient(to right, #FF9472, #F2709C); /* Reverse gradient */
        }

        /* Product Cards Container */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Individual Product Card */
        .product-card {
            background: #1E293B; /* Dark card background */
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25); /* Soft shadow */
            width: 300px;
            text-align: center;
            color: #FFF;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px); /* Lift effect */
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-info {
            margin-top: 10px;
        }

        .product-info h4 {
            font-size: 18px;
            color: #F0AFAF; /* Header color matching login design */
            margin-bottom: 10px;
        }

        .product-info p {
            font-size: 14px;
            color: #A5B4FC; /* Light blue text */
            margin-bottom: 5px;
        }

        /* Buttons Styling */
        .product-card button,
        .product-card input[type="submit"] {
            width: 48%;
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-button {
            background: linear-gradient(to right, #94D4B4, #62A786); /* Soft green gradient */
            color: #FFF;
        }

        .edit-button:hover {
            background: linear-gradient(to right, #62A786, #94D4B4); /* Reverse gradient */
        }

        .delete-button {
            background: linear-gradient(to right, #E74C3C, #C0392B); /* Red gradient */
            color: #FFF;
        }

        .delete-button:hover {
            background: linear-gradient(to right, #C0392B, #E74C3C);
        }

        .add-button {
            background: linear-gradient(to right, #2980B9, #3498DB); /* Blue gradient */
            color: #FFF;
        }

        .add-button:hover {
            background: linear-gradient(to right, #3498DB, #2980B9);
        }

        /* Footer Styling */
        footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            background-color: #1E293B;
            color: #94D4B4;
            border-radius: 10px;
            box-shadow: 0px -4px 10px rgba(0, 0, 0, 0.25); /* Soft shadow */
        }
    </style>
</head>
<body>
    <header>
        Products Dashboard
    </header>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="purchase_view.php">View Purchases</a> 
        <a href="add_product.php">Add Product</a>
    </nav>

    <div class="product-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-info">
                    <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                    <p>Price: ₱<?= number_format(htmlspecialchars($row['price']), 2) ?></p>
                    <p>Remaining Stock: <?= htmlspecialchars($row['quantity']) ?></p>
                    <div>
                        <a href="edit_product.php?id=<?= $row['product_id'] ?>">
                            <button class="edit-button">Edit</button>
                        </a>
                        <a href="delete_product.php?id=<?= $row['product_id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">
                            <button class="delete-button">Delete</button>
                        </a>
                    </div>
                    <form action="add_stock.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <label for="stock_quantity">Add Stock:</label>
                        <input type="number" name="stock_quantity" min="1" required>
                        <button type="submit" class="add-button">Add Stock</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
