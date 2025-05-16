<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "ezi";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind parameters
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Check if the username, full name, or email already exists
    $check_sql = "SELECT * FROM client WHERE full_name = '$full_name' OR email = '$email' OR username = '$username'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Error: Full name, email, or username already exists.');
            window.location.href = 'signup.php'; // Redirect to the signup page
        </script>";
    } else {
        // Handle file upload
        $profile_picture = $_FILES['profile_picture'];
        $upload_dir = 'picture/';
        $upload_file = $upload_dir . basename($profile_picture['name']);
        
        if ($profile_picture['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($profile_picture['tmp_name'], $upload_file)) {
                // Insert query including username and password fields
                $sql = "INSERT INTO client (full_name, contact, address, email, username, password, profile_picture) VALUES ('$full_name', '$contact', '$address', '$email', '$username', '$password', '$upload_file')";

                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    echo "<script>
                        alert('New record created successfully');
                        window.location.href = 'contact_info.php?client_id=" . $last_id . "'; 
                    </script>";
                } else {
                    echo "<script>
                        alert('Error: " . $conn->error . "');
                        window.location.href = 'signup.php'; 
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Error uploading file.');
                    window.location.href = 'signup.php'; 
                </script>";
            }
        } else {
            echo "<script>
                alert('File upload error: " . $profile_picture['error'] . "');
                window.location.href = 'signup.php'; 
            </script>";
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Signup Form</title>
    <style>
        /* Reset body styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #2D3E50, #465E73); /* Gradient background */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            padding: 20px; /* Add padding for small screens */
            box-sizing: border-box;
        }

        /* Form container styling */
        form {
            max-width: 400px; /* Form width */
            width: 100%; /* Responsive */
            background-color: #1E293B; /* Dark card background */
            border-radius: 15px; /* Rounded corners */
            padding: 30px; /* Increased padding */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.25); /* Soft shadow */
        }

        /* Header text styling */
        h2 {
            text-align: center;
            color: #F0AFAF; /* Header color matching the image design */
            font-size: 24px;
            margin-bottom: 30px; /* Increased spacing below the header */
        }

        /* Label styling */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px; /* Spacing between label and input */
            color: #A5B4FC; /* Light blue labels */
        }

        /* Input field styling */
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px; /* Slightly larger padding */
            margin-bottom: 20px; /* Increased spacing between fields */
            border: none;
            border-radius: 5px;
            background: #34495E; /* Input field background */
            color: #FFF;
            font-size: 14px;
            box-sizing: border-box; /* Ensure consistent sizing */
        }

        textarea {
            resize: none; /* Disable resize */
        }

        /* Button styling */
        button {
            width: 100%;
            padding: 15px; /* Larger padding */
            margin-top: 20px; /* Space above the button */
            background: linear-gradient(to right, #F2709C, #FF9472); /* Gradient matching the button */
            color: #FFF;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(to right, #FF9472, #F2709C); /* Reverse gradient */
            transform: translateY(-2px); /* Lift effect */
        }

        /* Centered login link */
        a {
            color: #94D4B4; /* Soft green */
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px; /* Space above link */
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form action="signup.php" method="POST" enctype="multipart/form-data">
        <h2>Client Signup Form</h2>
        <div>
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        <div>
            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" required></textarea>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
        </div>
        <button type="submit">Sign Up</button>
        <a href="login.php">Login</a>
    </form>
</body>
</html>
