<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM client WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $client = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $client['password'])) {
            $_SESSION['client_id'] = $client['client_id'];
            $_SESSION['full_name'] = $client['full_name'];

            header("Location: product_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found.'); window.location.href='login.php';</script>";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
    <form action="login.php" method="POST">
        <h2>Client Login</h2>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
        <a href="signup.php">Signup</a>
    </form>
</body>
</html>
