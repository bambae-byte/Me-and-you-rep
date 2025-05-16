<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch clients
$client_id = 1; // Assuming you want to display the dashboard for client with ID 1
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);
$client = $result_client->fetch_assoc();

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        /* Global reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4; /* Soft background color */
            color: #333; /* Dark text for readability */
            line-height: 1.6;
        }

        /* Header styling */
        header {
            background-color: #2C3E50; /* Dark blue-grey */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Navigation bar styling */
        nav {
            background-color: #34495E; /* Slightly lighter grey-blue */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            margin: 0 10px;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav a:hover {
            background-color: #1ABC9C; /* Teal highlight */
            color: #fff;
        }

        /* Product card container */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 30px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Product card styling */
        .product-card {
            width: 250px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Hover effect */
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-info h4 {
            font-size: 18px;
            color: #2C3E50; /* Dark text */
            margin-bottom: 10px;
        }

        .product-info p {
            color: #7F8C8D; /* Lighter grey text */
            margin-bottom: 15px;
        }

        /* Button styling */
        .product-card button {
            background-color: #1ABC9C; /* Teal button */
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 0 0 8px 8px;
            transition: background-color 0.3s;
        }

        .product-card button:hover {
            background-color: #16A085; /* Darker teal on hover */
        }

        /* Footer styling */
        footer {
            background-color: #34495E;
            color: white;
            text-align: center;
            padding: 20px;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }

        footer p {
            font-size: 14px;
            color: #BDC3C7; /* Lighter grey text */
        }
    </style>
</head>
<body>
    <header>
        Welcome, <?= htmlspecialchars($client['full_name']) ?>!
    </header>

    <!-- Navigation bar -->
    <nav>
        <a href="contact_info.php?client_id=<?= $client_id ?>">Contact Information</a>
        <a href="client.php">Product Dashboard</a>
        <a href="signup.php">Sign Up</a>
    </nav>

    <!-- Product cards container -->
    <div class="product-container">
        <?php while ($row = $result_products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-info">
                    <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                    <p>Price: ₱<?= htmlspecialchars($row['price']) ?></p>
                    <a href="purchase.php?id=<?= $row['product_id'] ?>">
                        <button>Buy Now</button>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
