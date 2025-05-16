<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #1e3c57, #1a2a3a);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #0f172a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 370px; /* Increased width slightly for better spacing */
            color: white;
            text-align: center;
        }

        h3 {
            font-weight: bold; /* Made bold */
            color: white; /* Changed to white */
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #94a3b8;
        }

        input, textarea {
            width: calc(100% - 20px); /* Added spacing on both sides */
            padding: 10px;
            border-radius: 5px;
            border: none;
            background: #1e293b;
            color: white;
            margin-bottom: 15px;
        }

        button {
            background: linear-gradient(to right, #ec4899, #f97316);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            opacity: 0.9;
        }

        img {
            max-width: 100%;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Edit Product</h3>
        <form action="edit_product.php?id=<?= htmlspecialchars($product['product_id'] ?? '') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'] ?? '') ?>">
            <label>Product Name:</label>
            <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name'] ?? '') ?>" required>
            <label>Price:</label>
            <input type="number" name="price" value="<?= htmlspecialchars($product['price'] ?? '') ?>" step="0.01" required>
            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity'] ?? '') ?>" required>
            <label>Caption:</label>
            <textarea name="caption" required><?= htmlspecialchars($product['caption'] ?? '') ?></textarea>
            <label>Image:</label>
            <input type="file" name="image">
            <button type="submit">Update Product</button>
        </form>
        <?php if (!empty($product['image'])): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image">
        <?php endif; ?>
    </div>
</body>
</html>
