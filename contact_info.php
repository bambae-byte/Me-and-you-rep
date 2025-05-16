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

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Validate the file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($file['type'], $allowed_types) && $file['error'] == 0) {
        // Define a directory to store uploaded files
        $upload_dir = 'uploads/';
        $file_name = $upload_dir . basename($file['name']);
        
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($file['tmp_name'], $file_name)) {
            // Update the database with the file path
            $sql_update = "UPDATE client SET profile_picture = '$file_name' WHERE client_id = $client_id";
            if ($conn->query($sql_update) === TRUE) {
                echo "<script>alert('Profile picture uploaded successfully.');</script>";
            } else {
                echo "<script>alert('Error updating database: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the file.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type or upload error.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Information</title>
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

        h2, h3 {
            text-align: center;
            color: #F0AFAF;
            margin-bottom: 30px;
        }

        p {
            font-size: 14px;
            line-height: 1.8;
            color: #A5B4FC;
        }

        .profile-picture-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
        }

        label {
            font-weight: bold;
            color: #A5B4FC;
        }

        input[type="file"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            background: #34495E;
            border: none;
            color: #FFF;
            font-size: 14px;
            margin-top: 15px;
        }

        input[type="submit"] {
            background: linear-gradient(to right, #F2709C, #FF9472);
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #FF9472, #F2709C);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #94D4B4;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div class="profile-picture-container">
            <?php if (!empty($client['profile_picture'])): ?>
                <img class="profile-picture" src="<?= htmlspecialchars($client['profile_picture']) ?>" alt="Profile Picture">
            <?php else: ?>
                <img class="profile-picture" src="default-profile.png" alt="Default Profile Picture">
            <?php endif; ?>
        </div>

        <h2>Client Details</h2>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($client['full_name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($client['contact']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($client['username']) ?></p>
        <p><strong>Password:</strong> <em>********</em></p>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" required>
            <input type="submit" value="Upload">
        </form>

        <a href="edit_client.php?client_id=<?= $client_id ?>">Edit Information</a>
        <a href="product_dashboard.php?client_id=<?= $client_id ?>">Back to Dashboard</a>
    </div>
</body>
</html>
