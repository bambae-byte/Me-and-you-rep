<?php
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch clients
$client_id = $_GET['client_id']; // Get client ID from URL
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

if (!$result_client) {
    die("Query failed: " . $conn->error);
}

$client = $result_client->fetch_assoc();

if (!$client) {
    die("Client not found.");
}

// Update client information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $sql_update = "UPDATE client SET full_name = '$full_name', contact = '$contact', address = '$address' WHERE client_id = $client_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Client information updated successfully.');</script>";
        header("Location: contact_info.php?client_id=$client_id"); // Redirect after successful update
        exit;
    } else {
        echo "<script>alert('Error updating client information: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Client Information</title>
    <style>
        /* General Reset */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #2D3E50, #465E73);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .content-container {
            max-width: 400px;
            width: 100%;
            background-color: #1E293B;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25);
        }

        h2 {
            text-align: center;
            color: #F0AFAF;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #A5B4FC;
        }

        form input[type="text"],
        form input[type="submit"] {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            background: #34495E;
            border: none;
            color: #FFF;
            font-size: 14px;
            margin-bottom: 15px;
        }

        form input[type="submit"] {
            background: linear-gradient(to right, #F2709C, #FF9472);
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        form input[type="submit"]:hover {
            background: linear-gradient(to right, #FF9472, #F2709C);
        }

        a {
            display: block;
            text-align: center;
            color: #94D4B4;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <h2>Edit Client Information</h2>
        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($client['full_name']) ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($client['contact']) ?>" required>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($client['address']) ?>" required>

            <input type="submit" value="Update">
        </form>
        <a href="contact_info.php?client_id=<?= $client_id ?>">Cancel</a>
    </div>
</body>
</html>
