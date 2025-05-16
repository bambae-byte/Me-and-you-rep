<?php 
session_start();
include 'db.php';

// Check if the client is logged in; otherwise, redirect to the signup page
if (!isset($_SESSION['client_id'])) {
    header("Location: signup.php");
    exit();
}

// Retrieve client ID from session
$client_id = $_SESSION['client_id'];

// Fetch client information
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

// Check if client data was fetched successfully
if ($result_client && $result_client->num_rows > 0) {
    $client = $result_client->fetch_assoc();
    $client_name = htmlspecialchars($client['full_name']);
} else {
    $client_name = "Guest"; // Default name if client data not found
}

// Fetch products
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

if (!$result_products) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <style>
        /* Reset and layout */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #2D3E50, #465E73);
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header styling */
        header {
            text-align: center;
            font-size: 28px;
            padding: 20px;
            background: linear-gradient(to right, #F2709C, #FF9472);
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25);
        }

        /* Navigation bar styling */
        nav {
            background: #1E293B;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        nav a {
            color: white;
            padding: 14px 16px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            background: #F2709C;
            color: #FFF;
        }

        /* Product container styling */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            flex-grow: 1;
        }

        /* Product card styling */
        .product-card {
            background-color: #34495E;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25);
            text-align: center;
            width: 220px;
            overflow: hidden;
        }

        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-info {
            padding: 15px;
        }

        .product-info h4 {
            font-size: 18px;
            color: #A5B4FC;
            margin-bottom: 10px;
        }

        .product-info p {
            color: #EEE;
            font-size: 14px;
            margin: 5px 0;
        }

        /* Buttons */
        .product-card button {
            width: 100%;
            padding: 10px;
            background: linear-gradient(to right, #F2709C, #FF9472);
            border: none;
            color: #FFF;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .product-card button:hover {
            transform: translateY(-2px);
            background: linear-gradient(to right, #FF9472, #F2709C);
        }

        /* Footer styling */
        footer {
            text-align: center;
            background: #1E293B;
            color: #FFF;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0px -4px 15px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <header>
        Welcome, <?= $client_name ?>! Thank you for visiting our store.
    </header>

    <nav>
        <a href="#">Product Dashboard</a>
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>
    </nav>

    <div class="product-container">
        <?php while ($row = $result_products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-info">
                    <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                    <p><?= htmlspecialchars($row['caption']) ?></p>
                    <p>Price: ₱<?= number_format(htmlspecialchars($row['price']), 2) ?></p>
                    <form action="purchase.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" min="1" value="1" required>
                        <button type="submit">Buy Now</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
