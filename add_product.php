<?php 
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $caption = $_POST['caption'];  // New caption variable
    
    // Image upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the target directory
        $target_dir = "uploads/";
        // Generate a unique filename
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["image"]["name"]);
        // Move the uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        $image_path = null;  // Set to null if no image is uploaded
    }

    // Insert product details into the database
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, quantity, image, caption) VALUES (?, ?, ?, ?, ?)"); // Include caption
    $stmt->bind_param("sdiss", $product_name, $price, $quantity, $image_path, $caption);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #2D3E50, #465E73); /* Gradient background */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        form {
            max-width: 400px;
            width: 100%;
            background-color: #1E293B; /* Dark card background */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25);
        }

        h2 {
            text-align: center;
            color: #F0AFAF;
            font-size: 28px; /* Adjusted the font size for better visibility */
            margin-bottom: 20px; /* Reduced margin to better fit the page */
            text-transform: uppercase; /* Uppercase text for a bolder look */
            position: absolute; /* Fixed position above the form */
            top: 30px; /* Adjust top distance */
            width: 100%;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #A5B4FC;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background: #34495E; /* Input field background */
            color: #FFF;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: none; /* Disable resize */
        }

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

        a {
            color: #94D4B4;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Add Product Form -->
    <h2>Add Product</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required><br>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required><br>

        <label for="caption">Product Caption:</label>
        <textarea name="caption" rows="4" cols="50" required placeholder="Enter product caption here..."></textarea><br> 

        <label for="image">Product Image:</label>
        <input type="file" name="image" accept="image/*"><br>

        <button type="submit" class="add-button">Add Product</button>
    </form>
</body>
</html>
